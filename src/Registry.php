<?php
/**
 * slince upload handler component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

use Slince\Upload\Exception\UploadException;

/**
 * 接收处理程序
 */
class Registry
{

    /**
     * 是否覆盖
     * 
     * @var boolean
     */
    private $_override = false;

    /**
     * 保存位置
     * 
     * @var string
     */
    private $_savePath = './';

    /**
     * 是否随机名
     * 
     * @var boolean
     */
    private $_randName = false;

    /**
     * 系统验证规则
     * 
     * @var string
     */
    const RULE_SYS = 'sys';

    /**
     * 文件大小限制验证
     * 
     * @var string
     */
    const RULE_SIZE = 'size';

    /**
     * 文件类型验证
     * 
     * @var string
     */
    const RULE_MIME = 'mime';

    /**
     * 扩展名验证
     * 
     * @var string
     */
    const RULE_EXT = 'ext';

    /**
     * 验证规则数组
     * 
     * @var array
     */
    private $_rules = [];

    function __construct($path = './')
    {
        $this->setSavePath($path);
        $this->addRule(RuleFactory::create(self::RULE_SYS));
    }

    /**
     * 设置是否覆盖指示
     * @param boolean $val
     */
    function setOverride($val)
    {
        $this->_override = $val;
    }

    /**
     * 是否覆盖
     * 
     * @return boolean
     */
    function getOverride()
    {
        return $this->_override;
    }

    /**
     * 设置保存位置
     * 
     * @param string $path
     * @throws UploadException
     */
    function setSavePath($path)
    {
        if (! file_exists($path)) {
            @mkdir($path, '0777', true);
        }
        if (is_dir($path)) {
            throw new UploadException(sprintf('Path "%s" is not valid', $path));
        }
        $this->_savePath = rtrim($path, '\\/') . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取保存位置
     * 
     * @return string
     */
    function getSavePath()
    {
        return $this->_savePath;
    }

    /**
     * 设置是否启用随机名
     * 
     * @param boolean $val
     */
    function setRandName($val)
    {
        $this->_randName = $val;
    }

    /**
     * 获取是否采用随机名
     * 
     * @return boolean
     */
    function getRandName()
    {
        return $this->_randName;
    }

    /**
     * 添加一个验证规则
     * 
     * @param RuleInterface $rule
     */
    function addRule(RuleInterface $rule)
    {
        $this->_rules[] = $rule;
    }
    
    /**
     * 获取所有的验证规则
     * 
     * @return array
     */
    function getRules()
    {
        return $this->_rules;
    }

    /**
     * 处理上传
     * 
     * @param array $files
     * @throws UploadException
     * @return \Slince\Upload\FileInfo|array;
     */
    function process($files)
    {
        if (empty($files)) {
            throw new UploadException('File array is not valid');
        }
        //多文件上传
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
                $_files[] = $this->_receive($_file);
            }
            return $_files;
        } else {
            return $this->_receive($files);
        }
    }

    /**
     * 接收处理
     * 
     * @param array $files
     * @return FileInfo;
     */
    function _receive(array $info)
    {
        $file = FileInfo::createFromArray($info);
        if ($this->_validate($file)) {
            $this->_move($file);
        }
        return $file;
    }

    /**
     * 验证文件
     * 
     * @param FileInfo $file
     * @return boolean
     */
    private function _validate(FileInfo $file)
    {
        foreach ($this->_rules as $rule) {
            if (! $rule->validate($file)) {
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
     *
     * @param FileInfo $file            
     * @return boolean
     * @throws UploadException
     */
    private function _move(FileInfo $file)
    {
        $tmpName = $file->getTmpName();
        $dest = $this->_generateName($file);
        if (is_uploaded_file($tmpName)) {
            if (! file_exists($dest) || $this->_override) {
                if (! @move_uploaded_file($tmpName, $dest)) {
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
     * 生成新的保存路径
     *
     * @param FileInfo $file            
     * @return string
     */
    private function _generateName(FileInfo $file)
    {
        if ($this->_randName) {
            $path = $this->_savePath . time() . rand(10, 99) . '.' . $file->getExtension();
        } else {
            $path = $this->_savePath . $file->getOriginName();
        }
        return $path;
    }
}