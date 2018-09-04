<?php
include __DIR__ . '/bootstrap.php';

use Slince\Upload\UploadHandlerBuilder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

$builder = new UploadHandlerBuilder();
$handler = $builder
    //Custom namer
    ->naming(function (UploadedFile $file) {
        return date('Y/md') . '/' . uniqid() . '.' . $file->getClientOriginalExtension();
    })

    //add constraints
    ->sizeBetween('10m', '20m')
    ->allowExtensions(['jpg', 'txt'])
    ->allowMimeTypes(['image/*', 'text/plain'])

    ->saveTo(__DIR__ . '/dst')
    ->getHandler();

$files = $handler->handle();

foreach ($files as $file) {
    if ($file instanceof \Exception) {
        echo 'upload error: ' . $file->getMessage(), PHP_EOL;
    } else {
        echo 'upload ok, path:' . $file->getPathname();
    }
}

