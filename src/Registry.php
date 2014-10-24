<?php
/**
 * 
 * @author Administrator
 *
 */
namespace Slince\Upload;

use Slince\Upload\Exception\UploadException;

/**
 * 接收处理程序
 */
class Registry
{
    private $_override = false;
    
    private $_savePath = './';
    
    private $_randName = false;
    
    const RULE_SYS = 'sys';
    
    const RULE_SIZE = 'size';
    
    const RULE_MIME = 'mime';
    
    const RULE_EXT = 'ext';
    
    private $_rules = [];
    
    private $_errorMsg = '';
    
    function __construct($path = './')
    {
        $this->_savePath = $path;
        $this->addRule(RuleFactory::create(self::RULE_SYS));
    }
    function setOverride($val)
    {
        $this->_override = $val;
    }
    
    function getOverride()
    {
        return $this->_override;
    }
    
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
    function getSavePath()
    {
        return $this->_savePath;
    }
    
    function setRandName($val)
    {
        $this->_randName = $val;
    }
    
    function getRandName()
    {
        return $this->_randName;
    }
    
    function addRule(RuleInterface $rule)
    {
        $this->_rules[] = $rule;
    }
    function process($files)
    {
        if (empty($file)) {
            throw new UploadException(sprintf('Path "%s" is not valid', $path));
        }
        if (is_array($files['name'])) {
            
        } else {
            return $this->_receive($files);
        }
    }
    
    /**
     * 
     * @param array $files
     * @return FileInfo;
     */
    function _receive(array $files)
    {
        $file = FileInfo::createFromArray($info);
        if ($this->_validate($file)) {
            $this->_move($file);
        }
        return $file;
    }
    
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
                return true;
            } else {
                $file->setErrorCode(ErrorStore::ERROR_SAME_NAME_FILE);
                $file->setErrorMsg(sprintf('File "%s" already exists', $file->getOriginName()));
                return false;
            }
        }
        throw new UploadException('Failed to move file');
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
            $path = $this->_savePath . time() . rand(10, 99) . $file->getExtension();
        } else {
            $path = $this->_savePath . $file->getOriginName();
        }
        return $path;
    }
}