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

    $registry = new Registry('./savepath');

    //设置同名文件覆盖
    $registry->setOverride(true);

    //设置文件大小限制
    $registry->addRule(RuleFactory::create(Registry::RULE_SIZE), [80, 100]);
    如果你只打算设置上限
    $registry->addRule(RuleFactory::create(Registry::RULE_SIZE), [100]);

    //设置文件类型限制
    $registry->addRule(RuleFactory::create(Registry::RULE_MIME), [['image/jpeg', 'text/planin']]);

    //设置扩展名限制
    $registry->addRule(RuleFactory::create(Registry::RULE_MIME), [['jpg', 'text']]);
   
    $file = $registry->process($_FILES['userfile']);
    if ($file->hasError) {
        echo $file->getErrorCode();
        echo $file->getErrorMsg();
    } else {
        $newPath = $file->getPath();
    }
    //*如果是多文件上传，那么Registry::process()返回的将是个数组
     