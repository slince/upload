<?php
include __DIR__ . '/bootstrap.php';

use Slince\Upload\UploadHandlerBuilder;

$builder = new UploadHandlerBuilder();
$handler = $builder
    ->saveTo(__DIR__ . '/dst')
    ->getHandler();

$files = $handler->handle();

//Gets all 'UploadedFile' objects
$uploadedFiles = $handler->getUploadedFiles();
print_r($uploadedFiles);

foreach ($files as $file) {
    //you can access some client attribute, like original name, extension, or mime type.
    $uploadedFile = $file->getUploadedFile();
    if ($file->isUploaded()) {
        echo $uploadedFile->getClientOriginalName() . ' upload ok, path:' . $file->getDetails()->getPathname();
    } else {
        echo $uploadedFile->getClientOriginalName() . ' upload error: ' . $file->getException()->getMessage();
    }
    echo PHP_EOL;
}
exit('ok');