# Upload handler component

[![Build Status](https://img.shields.io/travis/slince/upload/master.svg?style=flat-square)](https://travis-ci.org/slince/upload)
[![Coverage Status](https://img.shields.io/codecov/c/github/slince/upload.svg?style=flat-square)](https://codecov.io/github/slince/upload)
[![Total Downloads](https://img.shields.io/packagist/dt/slince/upload.svg?style=flat-square)](https://packagist.org/packages/slince/upload)
[![Latest Stable Version](https://img.shields.io/packagist/v/slince/upload.svg?style=flat-square&label=stable)](https://packagist.org/packages/slince/upload)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/slince/upload.svg?style=flat-square)](https://scrutinizer-ci.com/g/slince/upload/?branch=master)

Process uploaded files.

### Install

Install via composer

```
composer require slince/upload
```

### Usage

- Basic usage
```
use Slince\Upload\Registry;
use Slince\Upload\Exception\UploadException;
use Slince\Upload\FileInfo;

$uploader = new Uploader('./savepath');

try {
    $fileInfo = $uploader->process($_FILES['upfile']);
    
    if ($fileInfo->hasError()) {
        echo $fileInfo->getErrorCode();
        echo $fileInfo->getErrorMsg();
    }
} catch (UploadException $exception) {
     exit($e->getMessage());
}

```

- Advanced usage

```
//Override old files if there is a file of the same name  
$uploader->setOverride(true);

//Generate new file name using random mode
$uploader->setIsRandName(true);

//Customize the file path
$uploader->setFilenameGenerator(function(FileInfo $file) use ($registry){
    return $registry->getSavePath() . time() . $file->getOriginName();
});

//Limit file size, Include boundary values
$uploader->addRule(new SizeRule(1000, 2000));


//Limit the file mime type
$uploader->addRule(new MimeTypeRule(['image/*', 'text/planin']));

//Limit the extension
$uploader->addRule(new ExtensionRule(['jpg', 'text']));
```

- Multi-file upload

`$uploader->process($_FILES)` will return an array containing all the fileinfo