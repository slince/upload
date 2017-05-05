<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

use Slince\Upload\Exception\UploadException;
use Slince\Upload\Rule\RuleInterface;
use Slince\Upload\Rule\SystemRule;

class Uploader
{
    /**
     * Whether to overwrite if there is a file with the same name
     * @var boolean
     */
    protected $override = false;

    /**
     * The saved path of the uploaded files
     * @var string
     */
    protected $savePath = './';

    /**
     * Whether to enable random file name
     * @var boolean
     */
    protected $isRandName = false;

    /**
     * The rules collection
     * @var RuleInterface[]
     */
    protected $rules = [];

    /**
     * File name generator
     * @var callable
     */
    protected $filenameGenerator;

    public function __construct($path = './')
    {
        $this->setSavePath($path);
        $this->addRule(new SystemRule());
    }

    /**
     * Sets override mode
     * @param boolean $override
     */
    public function setOverride($override)
    {
        $this->override = $override;
    }

    /**
     * Gets whether to enabled override mode
     * @return boolean
     */
    public function getOverride()
    {
        return $this->override;
    }

    /**
     * Set saved path for uploaded files
     * @param string $path
     * @throws UploadException
     */
    public function setSavePath($path)
    {
        if (!file_exists($path)) {
            @mkdir($path, 0777, true);
        }
        if (!is_dir($path)) {
            throw new UploadException(sprintf('Path "%s" is not valid', $path));
        }
        $this->savePath = rtrim($path, '\\/') . DIRECTORY_SEPARATOR;
    }

    /**
     * Get the saved path of uploaded files
     * @return string
     */
    public function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * Sets whether to enable rand-name mode
     * @param $result
     */
    public function setRandName($result)
    {
        $this->isRandName = (boolean)$result;
    }

    /**
     * Sets whether to enable rand-name mode
     * @param boolean $result
     * @deprecated Use "setRandName" instead
     */
    public function setIsRandName($result)
    {
        $this->setRandName($result);
    }

    /**
     * Checks whether the rand-name mode is used
     * @return boolean
     */
    public function isRandName()
    {
        return $this->isRandName;
    }

    /**
     * Checks whether the rand-name mode is used
     * @return boolean
     * @deprecated Use "isRandName" instead
     */
    public function getIsRandName()
    {
        return $this->isRandName();
    }

    /**
     * Set filename generator
     * @param callable $generator
     */
    public function setFilenameGenerator(callable $generator)
    {
        $this->filenameGenerator = $generator;
    }

    /**
     * Gets the current filename generator
     * @return string
     */
    public function getFilenameGenerator()
    {
        if (is_null($this->filenameGenerator)) {
            $this->filenameGenerator = $this->isRandName ? $this->makeDefaultRandFilenameGenerator()
                : $this->makeDefaultOriginFilenameGenerator();
        }
        return $this->filenameGenerator;
    }

    /**
     * Add a rule
     * @param RuleInterface $rule
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * Gets all rules
     * @return array
     */
    public function getRules()
    {
        return (array)$this->rules;
    }

    /**
     * Process upload
     * @param array $files
     * @throws UploadException
     * @return FileInfo|FileInfo[];
     */
    public function process($files)
    {
        if (empty($files)) {
            throw new UploadException('File array is not valid');
        }
        //multi files
        if (is_array($files['name'])) {
            $_files = [];
            foreach ($files['name'] as $key => $fileName) {
                $file = array(
                    'error' => $files['error'][$key],
                    'name' => $fileName,
                    'size' => $files['size'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'type' => $files['type'][$key]
                );
                $_files[] = $this->processUpload($file);
            }
            return $_files;
        } else {
            return $this->processUpload($files);
        }
    }

    /**
     * Process the uploaded file
     * @param array $info
     * @return FileInfo;
     */
    protected function processUpload(array $info)
    {
        $file = FileInfo::fromArray($info);
        if ($this->validateUploadedFile($file)) {
            $newFilePath = $this->generateFilename($file);
            $result = $this->moveUploadedFile($file, $newFilePath);
            if ($result) {
                $file->setPath($newFilePath);
                $file->setHasError(false);
            }
        } else {
            $file->setHasError(true);
        }
        return $file;
    }

    /**
     * Validates the uploaded file
     * @param FileInfo $file
     * @return boolean
     */
    protected function validateUploadedFile(FileInfo $file)
    {
        foreach ($this->rules as $rule) {
            if (!$rule->validate($file)) {
                $file->setErrorCode($rule->getErrorCode());
                $file->setErrorMsg($rule->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    /**
     * Moves the uploaded file
     * Illegal upload files and other unsolicited causes can not be moved to throw an exception
     * @param FileInfo $file
     * @param string $newFilePath
     * @throws UploadException
     * @return boolean
     */
    protected function moveUploadedFile(FileInfo $file, $newFilePath)
    {
        $tmpName = $file->getTmpName();
        if (is_uploaded_file($tmpName)) {
            if (!file_exists($newFilePath) || $this->override) {
                if (!@move_uploaded_file($tmpName, $newFilePath)) {
                    throw new UploadException('Failed to move file');
                }
                return true;
            } else {
                throw new UploadException(sprintf('File "%s" already exists', $file->getOriginName()), ErrorStore::ERROR_SAME_NAME_FILE);
            }
        }
        throw new UploadException('The uploaded file is invalid');
    }

    /**
     * Makes default rand filename generator
     * @return callable
     */
    protected function makeDefaultRandFilenameGenerator()
    {
        return function (FileInfo $file) {
            return $this->savePath . time() . rand(10, 99) . '.' . $file->getExtension();
        };
    }

    /**
     * Makes default origin filename generator
     * @return callable
     */
    protected function makeDefaultOriginFilenameGenerator()
    {
        return function (FileInfo $file) {
            return $this->savePath . $file->getOriginName();
        };
    }

    /**
     * Generates an new file path
     * @param FileInfo $file
     * @return string
     */
    protected function generateFilename(FileInfo $file)
    {
        $generator = $this->getFilenameGenerator();
        $path = call_user_func($generator, $file);
        return $path;
    }
}
