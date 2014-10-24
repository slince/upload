<?php
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
        $ruleCalss = '';
        switch ($type) {
            case Registry::RULE_SIZE :
                $ruleCalss = 'SizeRule';
                break;
            case Registry::RULE_MIME:
                $ruleCalss = 'MimeTypeRule';
                break;
            case Registry::RULE_EXT:
                $ruleCalss = 'ExtensionRule';
                break;
        }
        $ruleCalss = "Rule\\{$ruleCalss}";
        $rule = new $ruleClass();
        try {
            $ruleReflection = new \ReflectionClass($ruleCalss);
            if ($rule->getConstructor() != null) {
                $instance = $ruleReflection->newInstanceArgs($args); 
            } else {
                $instance = $ruleReflection->newInstanceWithoutConstructor();
            }
            return $instance;
        } catch (\ReflectionException $e) {
            throw new UploadException(sprintf('Rule "%s" does not support', $type));
        }
    }
}