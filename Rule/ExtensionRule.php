<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;
use Slince\Upload\ErrorStore;

class ExtensionRule extends AbstractRule
{
    /**
     * allowed extensions
     * @var array
     */
    protected $allowedExtensions = [];

    public function __construct($allowedExtensions)
    {
        if (is_array($allowedExtensions)) {
            $this->allowedExtensions = $allowedExtensions;
        } else {
            $this->allowedExtensions[] = $allowedExtensions;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(FileInfo $file)
    {
        if (!in_array($file->getExtension(), $this->allowedExtensions)) {
            $this->errorCode = ErrorStore::ERROR_CUSTOM_EXT;
            $this->errorMsg = 'File extension is not valid';
            return false;
        }
        return true;
    }
}
