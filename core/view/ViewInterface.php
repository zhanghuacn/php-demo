<?php
/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/15
 * Time: 5:34 下午
 */


namespace core\view;

interface ViewInterface
{

    // 初始化模板
    public function init();


    // 解析模板模板
    function render($path);

}