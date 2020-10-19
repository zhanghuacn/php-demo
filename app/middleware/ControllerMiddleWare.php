<?php
/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/15
 * Time: 5:30 下午
 */


namespace App\middleware;


use core\Request;

class ControllerMiddleWare
{
    /**
     * @param RequestInterface $request
     * @param \Closure $next 匿名函数
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        echo "<hr/>controller middleware<hr/>";
        return $next($request);
    }
}
