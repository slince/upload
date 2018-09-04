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

foreach ($files as $name => $file) {

    //you can access some client attribute, like original name, extension, or mime type..
    $uploadedFile = $uploadedFiles[$name];

    if ($file instanceof \Exception) {
        echo $uploadedFile->getClientOriginalName() . ' upload error: ' . $file->getMessage(), PHP_EOL;
    } else {
        echo $uploadedFile->getClientOriginalName() . 'upload ok, path:' . $file->getPathname();
    }
}
exit('ok');