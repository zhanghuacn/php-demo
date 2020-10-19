<?php
/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/15
 * Time: 4:52 下午
 */

namespace core;

class Config
{

    protected $config = [];

    // 扫描 config 文件夹,加入到配置的大数组
    public function init()
    {
        foreach (glob(FRAME_BASE_PATH . '/config/*.php') as $file) {
            $key = str_replace('.php', '', basename($file));
            $this->config[$key] = require $file;
        }
    }

    // 获取配置
    public function get($key)
    {
        $keys = explode('.', $key);
        $config = $this->config;
        foreach ($keys as $key){
            $config = $config[$key];
        }
        return $config;
    }


    // 重置配置的值
    public function set($key, $val)
    {
        $keys = explode('.', $key);

        $newconfig = &$this->config;
        foreach ($keys as $key)
            $newconfig = &$newconfig[$key]; // 传址

        $newconfig = $val;
    }


}