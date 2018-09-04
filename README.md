# FileUpload

[![Build Status](https://img.shields.io/travis/slince/upload/master.svg?style=flat-square)](https://travis-ci.org/slince/upload)
[![Coverage Status](https://img.shields.io/codecov/c/github/slince/upload.svg?style=flat-square)](https://codecov.io/github/slince/upload)
[![Latest Stable Version](https://img.shields.io/packagist/v/slince/upload.svg?style=flat-square&label=stable)](https://packagist.org/packages/slince/upload)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/slince/upload.svg?style=flat-square)](https://scrutinizer-ci.com/g/slince/upload/?branch=master)

Process uploaded files.

### Install

Install via composer

```
composer require slince/upload
```

### Usage

Assume a file is uploaded with this HTML form:

```html
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="foo" value=""/>
    <input type="file" name="bar[]" value=""/>
    <input type="file" name="bar[]" value=""/>
    <input type="submit" value="Upload File"/>
</form>
```

#### Basic usage

```php
use Slince\Upload\UploadHandlerBuilder;

$builder = new UploadHandlerBuilder(); //create a builder.

$handler = $builder
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
```

if you want access some attributes of the file saved in the client, you can use like this.

```php

//Gets all 'UploadedFile' objects

$uploadedFiles = $handler->getUploadedFiles();
print_r($uploadedFiles);

$uploadedFiles['foo']->getClientOriginalName(); // original name
$uploadedFiles['bar'][0]->getClientOriginalExtension(); // original  extension
$uploadedFiles['bar'][1]->getClientMimeType(); // original  mime type
```

### Advanced usage

```php

$builder = new UploadHandlerBuilder();
$handler = $builder

    ->overwrite(true) // open overwrite mode. 
    
    //Custom namer
    ->naming(function (UploadedFile $file) {
        return date('Y/md') . '/' . uniqid() . '.' . $file->getClientOriginalExtension();
    })

    //add constraints
    ->sizeBetween('10m', '20m')
    ->allowExtensions(['jpg', 'txt'])
    ->allowMimeTypes(['image/*', 'text/plain'])

    ->saveTo(__DIR__ . '/dst') //save to local
    ->getHandler();

$files = $handler->handle();
```

## License
 
The MIT license. See [MIT](https://opensource.org/licenses/MIT)