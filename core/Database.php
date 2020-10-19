<?php
/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/16
 * Time: 4:11 下午
 */

namespace core;


use think\facade\Db;

class Database
{
    /**
     * Database constructor.
     */
    public function __construct()
    {
        Db::setConfig(config('database'));
    }
}