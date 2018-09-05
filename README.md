# FileUpload

[![Build Status](https://img.shields.io/travis/slince/upload/master.svg?style=flat-square)](https://travis-ci.org/slince/upload)
[![Coverage Status](https://img.shields.io/codecov/c/github/slince/upload.svg?style=flat-square)](https://codecov.io/github/slince/upload)
[![Latest Stable Version](https://img.shields.io/packagist/v/slince/upload.svg?style=flat-square&label=stable)](https://packagist.org/packages/slince/upload)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/slince/upload.svg?style=flat-square)](https://scrutinizer-ci.com/g/slince/upload/?branch=master)

Process uploaded files with multiple and extensible validation rules.

## Installation

Install via composer

```bash
$ composer require slince/upload
```

## Quick view

```php
$builder = new Slince\Upload\UploadHandlerBuilder(); //create a builder.
$handler = $builder
    ->saveTo(__DIR__ . '/dst')
    ->getHandler();

$files = $handler->handle();
print_r($files);
```

## Usage

Assume files are uploaded with this HTML form:

```html
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="foo" value=""/>
    <input type="file" name="bar[baz][]" value=""/>
    <input type="file" name="bar[baz][]" value=""/>
    <input type="submit" value="Upload File"/>
</form>
```

Server:
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

foreach ($files as $file) {
    $uploadedFile = $file->getUploadedFile();
    if ($file->isUploaded()) {
        echo $uploadedFile->getClientOriginalName() . ' upload ok, path:' . $file->getDetails()->getPathname();
    } else {
        echo $uploadedFile->getClientOriginalName() . ' upload error: ' . $file->getException()->getMessage();
    }
    echo PHP_EOL;
}
```
File details `$file->getDetails()` is provided by storage layer. 
if you are using `Local`, it is an instance of `SplFileInfo`. 

If you want access attributes of the file saved in the client, you can use like this.
```php
$files['foo']->getUploadedFile()->getClientOriginalName(); // original name
$files['bar']['baz'][0]->getUploadedFile()->getClientOriginalExtension(); // original  extension
$files['bar']['baz'][1]->getUploadedFile()->getClientMimeType(); // original  mime type
```

### Integration with [flysystem](https://github.com/thephpleague/flysystem)

```php
function createS3Flysystem()
{
    $client = new Aws\S3\S3Client([
        'credentials' => [
            'key'    => 'your-key',
            'secret' => 'your-secret'
        ],
        'region' => 'your-region',
        'version' => 'latest|version',
    ]);
    $adapter = new League\Flysystem\AwsS3v3\AwsS3Adapter($client, 'your-bucket-name');
    $flysystem = new League\Flysystem\Filesystem($adapter);
    return $flysystem;
}

$builder = new UploadHandlerBuilder();
$handler = $builder->setFilesystem(createS3Flysystem())
    ->getHandler();

$handler->handle();
```

## License
 
The MIT license. See [MIT](https://opensource.org/licenses/MIT)