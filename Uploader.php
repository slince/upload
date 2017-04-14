<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

use Slince\Upload\Exception\UploadException;
use Slince\Upload\Rule\RuleInterface;

class Uploader
{
    /**
     * Whether to overwrite if there is a file of the same name
     * @var boolean
     */
    protected $override = false;

    /**
     * save path
     * @var string
     */
    protected $savePath = './';

    /**
     * Whether to enable random file name
     * @var boolean
     */
    protected $isRandName;

    /**
     * rules
     * @var RuleInterface[]
     */
    protected $rules = [];

    protected $filenameGenerator;

    public function __construct($path = './')
    {
        $this->setSavePath($path);
        $this->addRule(RuleFactory::create(RuleFactory::RULE_SYSTEM));
    }

    /**
     * set override mode
     * @param boolean $override
     */
    public function setOverride($override)
    {
        $this->override = $override;
    }

    /**
     * get override mode
     * @return boolean
     */
    public function getOverride()
    {
        return $this->override;
    }

    /**
     * set save path
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
     * get save path
     * @return string
     */
    public function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * set rand name mode
     * @param boolean $val
     */
    public function setIsRandName($val)
    {
        $this->isRandName = $val;
    }

    /**
     * get rand name mode
     * @return boolean
     */
    public function getIsRandName()
    {
        return $this->isRandName;
    }

    /**
     * set filename generator
     * @param callable $generator
     */
    public function setFilenameGenerator(callable $generator)
    {
        $this->filenameGenerator = $generator;
    }

    /**
     * get current filename generator
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
     * add rule
     * @param RuleInterface $rule
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * gets all rules
     * @return array
     */
    public function getRules()
    {
        return (array)$this->rules;
    }

    /**
     * go, process upload
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
     * process
     * @param array $info
     * @return FileInfo;
     */
    protected function processUpload(array $info)
    {
        $file = FileInfo::fromArray($info);
        if ($this->validateUpload($file)) {
            $this->moveUploadFile($file);
        }
        return $file;
    }

    /**
     * validate rules
     * @param FileInfo $file
     * @return boolean
     */
    protected function validateUpload(FileInfo $file)
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
     * move file
     * Illegal upload files and other unsolicited causes can not be moved to throw an exception
     * @param FileInfo $file
     * @return boolean
     * @throws UploadException
     */
    protected function moveUploadFile(FileInfo $file)
    {
        $tmpName = $file->getTmpName();
        $dst = $this->generateFilename($file);
        if (is_uploaded_file($tmpName)) {
            if (!file_exists($dst) || $this->override) {
                if (!@move_uploaded_file($tmpName, $dst)) {
                    throw new UploadException('Failed to move file');
                }
                $file->setPath($dst);
                $file->hasError = false;
                return true;
            } else {
                $file->setErrorCode(ErrorStore::ERROR_SAME_NAME_FILE);
                $file->setErrorMsg(sprintf('File "%s" already exists', $file->getOriginName()));
                return false;
            }
        }
        throw new UploadException('Upload file is not valid');
    }

    /**
     * make default rand filename generator
     * @return callable
     */
    protected function makeDefaultRandFilenameGenerator()
    {
        return function (FileInfo $file) {
            return $this->savePath . time() . rand(10, 99) . '.' . $file->getExtension();
        };
    }

    /**
     * make default origin filename generator
     * @return callable
     */
    protected function makeDefaultOriginFilenameGenerator()
    {
        return function (FileInfo $file) {
            return $this->savePath . $file->getOriginName();
        };
    }

    /**
     * make new filepath
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
