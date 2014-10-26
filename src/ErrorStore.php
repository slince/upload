<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

class ErrorStore
{

    const ERROR_OK = UPLOAD_ERR_OK;

    const ERROR_INI_SIZE = UPLOAD_ERR_INI_SIZE;

    const ERROR_FROM_SIZE = UPLOAD_ERR_FORM_SIZE;

    const ERROR_PARTIAL = UPLOAD_ERR_PARTIAL;

    const ERROR_NO_FILE = UPLOAD_ERR_NO_FILE;

    const ERROR_NO_TMP_DIR = UPLOAD_ERR_NO_TMP_DIR;

    const ERROR_CANT_WRITE = UPLOAD_ERR_CANT_WRITE;

    const ERROR_CUSTOM_SIZE = 10;

    const ERROR_CUSTOM_MIME_TYPE = 11;

    const ERROR_CUSTOM_EXT = 12;

    const ERROR_SAME_NAME_FILE = 13;
}