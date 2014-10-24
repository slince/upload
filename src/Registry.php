<?php
/**
 * 
 * @author Administrator
 *
 */
namespace Slince\Upload;

/**
 * 接收处理程序
 */
class Registry
{
    private $_override = false;
    
    private $_savePath = './';
    
    const RULE_SIZE = 'size';
    
    const RULE_MIME = 'mime';
    
    const RULE_EXT = 'ext';
    
    private $_rules = [];
    
    private $_errorMsg = '';
    
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
            throw new Exception\UploadException(sprintf('Path "%s" is not valid', $path));
        }
        $this->_savePath = rtrim($path, '\\/') . DIRECTORY_SEPARATOR;
    }
    function getSavePath()
    {
        return $this->_savePath;
    }
    function addRule($type, array $args, $msg = null)
    {
        $this->_rules[] = RuleFactory::create($type, $args, $msg);
    }
    function recive($files)
    {
        $file = FileInfo::createFromArray($info);
    }
    
    private function _validate(FileInfo $file)
    {
        foreach ($this->_rules as $rule) {
            if (! $rule->validate($file)) {
                $msg = $rule->getErrorMsg();
                return [
                    'status'=>false,
                    'msg' => $rule->getErrorMsg(),
                    'code' => $rule->getErrorCode()
                ];
            }
        }
        return true;
    }
    
    private function _move(FileInfo $file)
    {
        
    }
}