<?php
use Slince\Upload\Registry;
use Slince\Upload\Exception\UploadException;
use Slince\Upload\FileInfo;
use Slince\Upload\Rule\ExtensionRule;

include __DIR__ . '/../vendor/autoload.php';

$upload = new Registry();
$upload->setFilenameGenerator(function (FileInfo $file) use ($upload) {
    return $upload->getSavePath() . time() . $file->getOriginName();
});
$upload->addRule(new ExtensionRule(['txt', 'pdf']));
try {
    $file = $upload->process($_FILES['upload']);
    if (!$file->hasError) {
        var_dump($file);
    } else {
        echo $file->getErrorCode(), ':', $file->getErrorMsg();
    }
} catch (UploadException $e) {
    exit($e->getMessage());
}
