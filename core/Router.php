<?php
/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/16
 * Time: 7:40 下午
 */

namespace core;


class Router
{
    private $router;

    /**
     * Router constructor.
     * @param $router
     */
    public function __construct()
    {
        $this->router = new \Inhere\Route\Router();
    }

    public function group(\Closure $callback)
    {
        call_user_func($callback, $this->router);
    }
}