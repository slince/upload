<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;
use Slince\Upload\ErrorStore;

class SystemRule extends AbstractRule
{

    /**
     * messages
     * @var array
     */
    protected $messages = [
        ErrorStore::ERROR_INI_SIZE => 'File size exceeds the limit value in php.ini.',
        ErrorStore::ERROR_FROM_SIZE => 'File size exceeds the limit value in form',
        ErrorStore::ERROR_NO_TMP_DIR => 'Can not find a temporary directory',
        ErrorStore::ERROR_PARTIAL => 'Part of the file to be uploaded',
        ErrorStore::ERROR_NO_FILE => 'No file was uploaded',
        ErrorStore::ERROR_CANT_WRITE => 'File write failed'
    ];

    /**
     * {@inheritdoc}
     */
    public function validate(FileInfo $file)
    {
        if (($code = $file->getError()) != 0) {
            $this->errorCode = $code;
            $this->errorMsg = $this->messages[$code];
            return false;
        }
        return true;
    }
}
