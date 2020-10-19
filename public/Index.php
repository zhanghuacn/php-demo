<?php


require_once __DIR__ . '/../vendor/autoload.php'; // 引入Composer实现自动加载
require_once __DIR__ . '/../app.php'; //加载容器

app()->bind('', function () {
    return app()->get('router');
});
endView();