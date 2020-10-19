<?php

use core\view\ViewInterface;

/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/15
 * Time: 3:56 下午
 */

if (! function_exists('response')) {
    function response()
    {
        return app('response');
    }
}

function app($name = null)
{
    if( $name) // 如果选择了具体实例
        return App::getContainer()->get($name);
    return  App::getContainer();
}

function endView()
{
    $time = microtime(true) - FRAME_START_TIME;
    $memory = memory_get_usage() - FRAME_START_MEMORY;

    echo '<br/><br/><br/><br/><br/><hr/>';
    echo "运行时间: ". round($time * 1000,2) .'ms<br/>';
    echo "消耗内存: ". round($memory / 1024 / 1024,2) . 'm';
}


function config($key = null)
{
    if( $key)
        return app('config')->get($key) ;
    return app('config');
}

function view($path,$params = [])
{
    return app(ViewInterface::class)->render($path,$params);
}
