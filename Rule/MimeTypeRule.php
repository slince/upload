<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;
use Slince\Upload\ErrorStore;

class MimeTypeRule extends AbstractRule
{
    /**
     * allowed mime types
     * @var array
     */
    protected $allowedTypes = [];

    public function __construct($allowedTypes)
    {
        if (is_array($allowedTypes)) {
            $this->allowedTypes = $allowedTypes;
        } else {
            $this->allowedTypes[] = $allowedTypes;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(FileInfo $file)
    {
        $result = false;
        foreach ($this->allowedTypes as $mimeType) {
            if ($mimeType == $file->getMimeType()
                || (strpos($mimeType, '*') !== false
                    && explode('/', $mimeType)[0] == explode('/', $file->getMimeType())[0])
            ) {
                $result = true;
                break;
            }
        }
        if (!$result) {
            $this->errorCode = ErrorStore::ERROR_CUSTOM_MIME_TYPE;
            $this->errorMsg = 'File type is not valid';
            return false;
        }
        return true;
    }
}
