<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

class ErrorStore
{
    /**
     * 没有错误
     * @var int
     */
    const ERROR_OK = UPLOAD_ERR_OK;

    /**
     * 文件大小超过php.ini设置上限
     * @var int
     */
    const ERROR_INI_SIZE = UPLOAD_ERR_INI_SIZE;

    /**
     * 文件大小超过表单设置上限
     * @var int
     */
    const ERROR_FROM_SIZE = UPLOAD_ERR_FORM_SIZE;

    /**
     * 部分文件被上传
     * @var int
     */
    const ERROR_PARTIAL = UPLOAD_ERR_PARTIAL;

    /**
     * 没有文件被上传
     * @var int
     */
    const ERROR_NO_FILE = UPLOAD_ERR_NO_FILE;

    /**
     * 没有找到临时目录
     * @var int
     */
    const ERROR_NO_TMP_DIR = UPLOAD_ERR_NO_TMP_DIR;

    /**
     * 文件写入失败
     * @var int
     */
    const ERROR_CANT_WRITE = UPLOAD_ERR_CANT_WRITE;

    /**
     * 文件大小不在自定义范围内
     * @var int
     */
    const ERROR_CUSTOM_SIZE = 10;

    /**
     * 文件类型不在自定义范围内
     * @var int
     */
    const ERROR_CUSTOM_MIME_TYPE = 11;

    /**
     * 文件扩展名不在自定义范围内
     * @var int
     */
    const ERROR_CUSTOM_EXT = 12;

    /**
     * 保存位置存在同名文件
     * @var int
     */
    const ERROR_SAME_NAME_FILE = 13;
}
