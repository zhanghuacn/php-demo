<?php
/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/15
 * Time: 5:24 下午
 */


namespace App\middleware;

use core\Request;

class WebMiddleWare
{
    /**
     * @param RequestInterface $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        echo  123;
        return $next($request);
    }
}