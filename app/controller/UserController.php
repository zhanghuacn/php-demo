<?php
/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/15
 * Time: 5:28 下午
 */


namespace App\Controller;

use App\model\User;
use core\Controller;
use Inhere\Validate\Validation;

/**
 * Class UserController
 * @package App\controller
 */
class UserController extends Controller
{
    public function indexAction($params)
    {
        $v = Validation::check($_GET, [
            ['title', 'max', 20],
            ['name', 'int'],
        ]);
        if ($v->isFail()) {
            var_dump($v->getErrors());
            die();
        }
        $str = $name = User::find(1)->name;
        return view('thinkphp.index', compact('str', 'name'));
    }
}