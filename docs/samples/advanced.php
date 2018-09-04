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
    ->sizeBetween('10m', '20m')
    ->allowExtensions(['jpg', 'txt'])
    ->allowMimeTypes(['image/*', 'text/plain'])

    // save to local
    ->saveTo(__DIR__ . '/dst')
    ->getHandler();

$files = $handler->handle();

//Gets all 'UploadedFile' objects
$uploadedFiles = $handler->getUploadedFiles();
print_r($uploadedFiles);

foreach ($files as $name => $file) {

    //You can access some client attribute of the file.
    $uploadedFile = $uploadedFiles[$name];

    if ($file instanceof \Exception) {
        echo $uploadedFile->getClientOriginalName() . ' upload error: ' . $file->getMessage(), PHP_EOL;
    } else {
        echo $uploadedFile->getClientOriginalName() . 'upload ok, path:' . $file->getPathname();
    }
}
exit('ok');
