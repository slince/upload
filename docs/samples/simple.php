<?php
include __DIR__ . '/bootstrap.php';

use Slince\Upload\UploadHandlerBuilder;

$builder = new UploadHandlerBuilder();
$handler = $builder->overwrite()
    ->saveTo(__DIR__ . '/dst')
    ->getHandler();

$files = $handler->handle();

var_dump($files);