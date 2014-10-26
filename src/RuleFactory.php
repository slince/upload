<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

use Slince\Upload\Rule;
use Slince\Upload\Exception\UploadException;

class RuleFactory
{
    /**
     * 实例化验证规则
     * 
     * @param string $type
     * @param array $args
     * @param string $msg
     * @throws UploadException
     * @return object
     */
    static function create($type, $args = null)
    {
        $ruleClass = '';
        switch ($type) {
            case Registry::RULE_SIZE :
                $ruleClass = 'SizeRule';
                break;
            case Registry::RULE_MIME:
                $ruleClass = 'MimeTypeRule';
                break;
            case Registry::RULE_EXT:
                $ruleClass = 'ExtRule';
                break;
            case Registry::RULE_SYS:
                $ruleClass = 'SysRule';
                break;
        }
        $ruleClass = "Slince\\Upload\\Rule\\{$ruleClass}";
        try {
            $ruleReflection = new \ReflectionClass($ruleClass);
            if ($ruleReflection->getConstructor() != null) {
                $instance = $ruleReflection->newInstanceArgs($args); 
            } else {
                $instance = $ruleReflection->newInstanceWithoutConstructor();
            }
            return $instance;
        } catch (\ReflectionException $e) {
            echo $e->getMessage();exit;
            throw new UploadException(sprintf('Rule "%s" does not support', $type));
        }
    }
}