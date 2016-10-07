<?php
/**
 * slince upload handler component
 *
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

use Slince\Upload\Exception\UploadException;
use Slince\Upload\Rule\RuleInterface;

/**
 * 接收处理程序
 */
class Registry
{
    /**
     * 是否覆盖
     * @var boolean
     */
    protected $override = false;

    /**
     * 保存位置
     * @var string
     */
    protected $savePath = './';

    /**
     * 是否使用随机名
     * @var boolean
     */
    protected $isRandName;

    /**
     * 验证规则数组
     *
     * @var array
     */
    protected $rules = [];

    protected $filenameGenerator;

    public function __construct($path = './')
    {
        $this->setSavePath($path);
        $this->addRule(RuleFactory::create(RuleFactory::RULE_SYSTEM));
    }

    /**
     * 设置是否覆盖指示
     * @param boolean $val
     */
    public function setOverride($val)
    {
        $this->override = $val;
    }

    /**
     * 是否覆盖
     * @return boolean
     */
    public function getOverride()
    {
        return $this->override;
    }

    /**
     * 设置保存位置
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
     * 获取保存位置
     * @return string
     */
    public function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * 设置是否启用随机名
     * @param boolean $val
     */
    public function setIsRandName($val)
    {
        $this->isRandName = $val;
    }

    /**
     * 获取是否采用随机名
     * @return boolean
     */
    public function getIsRandName()
    {
        return $this->isRandName;
    }

    /**
     * 设置文件名生成器
     * @param callable $generator
     */
    public function setFilenameGenerator(callable $generator)
    {
        $this->filenameGenerator = $generator;
    }

    /**
     * 获取当前文件名生成器
     * @return string
     */
    public function getFilenameGenerator()
    {
        return $this->filenameGenerator;
    }

    /**
     * 添加一个验证规则
     * @param RuleInterface $rule
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * 获取所有的验证规则
     * @return array
     */
    public function getRules()
    {
        return (array)$this->rules;
    }

    /**
     * 处理上传
     * @param array $files
     * @throws UploadException
     * @return FileInfo|FileInfo[];
     */
    public function process($files)
    {
        if (empty($files)) {
            throw new UploadException('File array is not valid');
        }
        // 多文件上传
        if (is_array($files['name'])) {
            $_files = [];
            foreach ($files['name'] as $key => $fileName) {
                $_file = array(
                    'error' => $files['error'][$key],
                    'name' => $fileName,
                    'size' => $files['size'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'type' => $files['type'][$key]
                );
                $_files[] = $this->receive($_file);
            }
            return $_files;
        } else {
            return $this->receive($files);
        }
    }

    /**
     * 接收处理
     * @param array $info
     * @return FileInfo;
     */
    public function receive(array $info)
    {
        $file = FileInfo::createFromArray($info);
        if ($this->validateUpload($file)) {
            $this->moveUploadFile($file);
        }
        return $file;
    }

    /**
     * 验证文件上传
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
     * 移动文件
     * 非合法上传文件和因其它未知原因造成的无法移动会抛出异常
     * @param FileInfo $file
     * @return boolean
     * @throws UploadException
     */
    protected function moveUploadFile(FileInfo $file)
    {
        $tmpName = $file->getTmpName();
        $dest = $this->generateFilename($file);
        if (is_uploaded_file($tmpName)) {
            if (!file_exists($dest) || $this->override) {
                if (!@move_uploaded_file($tmpName, $dest)) {
                    throw new UploadException('Failed to move file');
                }
                $file->setPath($dest);
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
     * 获取默认的文件名生成器
     * @return callable
     */
    protected function getDefaultRandFilenameGenerator()
    {
        return function (FileInfo $file) {
            return $this->savePath . time() . rand(10, 99) . '.' . $file->getExtension();
        };
    }

    /**
     * 获取默认的源文件名路径生成器
     * @return callable
     */
    protected function getDefaultOriginFilenameGenerator()
    {
        return function (FileInfo $file) {
            return $this->savePath . $file->getOriginName();
        };
    }

    /**
     * 生成新的文件名
     * @param FileInfo $file
     * @return string
     */
    protected function generateFilename(FileInfo $file)
    {
        if (is_null($this->filenameGenerator)) {
            if ($this->isRandName) {
                $this->filenameGenerator = $this->getDefaultRandFilenameGenerator();
            } else {
                $this->filenameGenerator = $this->getDefaultOriginFilenameGenerator();
            }
        }
        $path = call_user_func($this->filenameGenerator, $file);
        return $path;
    }
}
