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

```
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="foo" value=""/>
    <input type="submit" value="Upload File"/>
</form>
```

- Basic usage
```
use Slince\Upload\Registry;
use Slince\Upload\Exception\UploadException;
use Slince\Upload\FileInfo;

$uploader = new Uploader('./savepath');

try {
    $fileInfo = $uploader->process($_FILES['foo']);
    
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

//Limit file size, Include boundary values;(unit: byte.) 
//You can alse use human readable size express,e.g 5M (use "B", "K", M", or "G"))
$uploader->addRule(new SizeRule(1000, 2000));


//Limit the file mime type
$uploader->addRule(new MimeTypeRule(['image/*', 'text/planin']));

//Limit the extension
$uploader->addRule(new ExtensionRule(['jpg', 'text']));
```

- Multi-file upload  

```
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="foo[]" value=""/>
    <input type="file" name="foo[]" value=""/>
    <input type="submit" value="Upload File"/>
</form>
```
`$uploader->process($_FILES['foo'])` will return an array containing all the fileinfo

```
try {
    $fileInfos = $uploader->process($_FILES['foo']);
    var_dump($fileInfos);
} catch (UploadException $exception) {
     exit($e->getMessage());
}
```