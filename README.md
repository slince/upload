# Upload handler component

文件上传处理组件

### 安装

在composer.json中添加

    {
        "require": {
            "slince/upload": "dev-master@dev"
        }
    }

### 用法

    use Slince\Upload\Registry;
    use Slince\Upload\Exception\UploadException;
    use Slince\Upload\FileInfo;
    use Slince\Upload\Rule\ExtRule;


    $registry = new Registry('./savepath');

    //设置同名文件覆盖
    $registry->setOverride(true);

    //自定义文件生成路径
    $registry->setFilenameGenerator(function(FileInfo $file) use ($registry){
        return $registry->getSavePath() . time() . $file->getOriginName();
    });
    
    //设置文件大小限制
    $registry->addRule(RuleFactory::create(RuleFactory::RULE_SIZE), [80, 100]);
    //如果你只打算设置上限
    $registry->addRule(RuleFactory::create(RuleFactory::RULE_SIZE), [100]);

    //设置文件类型限制
    $registry->addRule(RuleFactory::create(RuleFactory::RULE_MIME_TYPE), [['image/jpeg', 'text/planin']]);

    //设置扩展名限制
    $registry->addRule(RuleFactory::create(RuleFactory::RULE_MIME_TYPE), [['jpg', 'text']]);
   
    try {
        $file = $registry->process($_FILES['upload']);
        if (! $file->hasError) {
            var_dump($file);
        } else {
            echo $file->getErrorCode() , ':', $file->getErrorMsg();
        }
    } catch (UploadException $e) {
        exit($e->getMessage());
    }
    //*如果是多文件上传，那么Registry::process()返回的将是个数组
     