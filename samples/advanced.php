<?php
include __DIR__ . '/bootstrap.php';

use Slince\Upload\UploadHandlerBuilder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

$builder = new UploadHandlerBuilder();
$handler = $builder

    ->overwrite(true) //open overwrite mode.

    //Custom namer
    ->naming(function (UploadedFile $file) {
        return date('Y/md') . '/' . uniqid() . '.' . $file->getClientOriginalExtension();
    })

    //add constraints
    ->sizeBetween('10m', '20m') //filter file size
    ->allowExtensions(['jpg', 'txt']) //filter ext
    ->allowMimeTypes(['image/*', 'text/plain']) //filter mime type

    // save to local
    ->saveTo(__DIR__ . '/dst')
    ->getHandler();

$files = $handler->handle();

//Gets all 'UploadedFile' objects
$uploadedFiles = $handler->getUploadedFiles();
print_r($uploadedFiles);

foreach ($files as $file) {
    $uploadedFile = $file->getUploadedFile();
    if ($file->isUploaded()) {
        echo $uploadedFile->getClientOriginalName() . ' upload ok, path:' . $file->getDetails()->getPathname();
    } else {
        echo $uploadedFile->getClientOriginalName() . ' upload error: ' . $file->getException()->getMessage();
    }
    echo PHP_EOL;
}

exit('ok');