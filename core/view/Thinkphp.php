<?php
/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/15
 * Time: 5:33 下午
 */

namespace core\view;


// tp的模板引擎
use think\Template;

class Thinkphp implements ViewInterface
{

    protected $template;

    public function init()
    {
        $config = \config('view');
        $this->template = new Template([
            'view_path' => $config['view_path'],
            'cache_path' => $config['cache_path']
        ]);

    }

    public function render($path,array $params = [])
    {
        $this->template->assign($params);
        $path = str_replace('.','/',$path); // .替换成/
        return $this->template->fetch($path);
    }

}