<?php
/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/15
 * Time: 4:35 下午
 */

$router->get('/test/{name}', function ($params) {
    echo $params['name'];
}, [
    'name' => '\w+', // 添加参数匹配限制。若不添加对应的限制，将会自动设置为匹配除了'/'外的任何字符
]);

$router->get('/index/{id}', 'App\Controller\UserController@index');

// 开始调度运行
$router->dispatch();