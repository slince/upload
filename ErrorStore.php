<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

final class ErrorStore
{
    /**
     * no error
     * @var int
     */
    const ERROR_OK = UPLOAD_ERR_OK;

    /**
     * The file size exceeds the php.ini setting limit
     * @var int
     */
    const ERROR_INI_SIZE = UPLOAD_ERR_INI_SIZE;

    /**
     * The file size exceeds the form setting limit
     * @var int
     */
    const ERROR_FROM_SIZE = UPLOAD_ERR_FORM_SIZE;

    /**
     * Only uploaded some of the files
     * @var int
     */
    const ERROR_PARTIAL = UPLOAD_ERR_PARTIAL;

    /**
     * No files are uploaded
     * @var int
     */
    const ERROR_NO_FILE = UPLOAD_ERR_NO_FILE;

    /**
     * No temporary directory found
     * @var int
     */
    const ERROR_NO_TMP_DIR = UPLOAD_ERR_NO_TMP_DIR;

    /**
     * Can not write file
     * @var int
     */
    const ERROR_CANT_WRITE = UPLOAD_ERR_CANT_WRITE;

    /**
     * The file size is not within the custom range
     * @var int
     */
    const ERROR_CUSTOM_SIZE = 10;

    /**
     * The file type is not within the custom range
     * @var int
     */
    const ERROR_CUSTOM_MIME_TYPE = 11;

    /**
     * The file extension is not within the custom range
     * @var int
     */
    const ERROR_CUSTOM_EXT = 12;

    /**
     * There is a file with the same name
     * @var int
     */
    const ERROR_SAME_NAME_FILE = 13;
}
