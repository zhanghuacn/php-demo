<?php

use App\middleware\WebMiddleWare;
use core\Config;
use core\Database;
use core\PipeLine;
use core\Request;
use core\request\RequestInterface;
use core\Response;
use core\Router;
use core\view\Thinkphp;
use core\view\ViewInterface;
use Psr\Container\ContainerInterface;

define('FRAME_BASE_PATH', __DIR__); // 框架目录
define('FRAME_START_TIME', microtime(true)); // 开始时间
define('FRAME_START_MEMORY', memory_get_usage()); // 开始内存

/**
 * 实现容器接口
 * Created by Zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/15
 * Time: 3:45 下午
 */
class App implements ContainerInterface
{

    public $binding = []; // 绑定关系
    private static $instance; // 这个类的实例
    protected $instances = []; // 所有实例的存放

    private function __construct()
    {
        self::$instance = $this; // App类的实例
        $this->register();  // 注册绑定
        $this->boot(); // 服务注册了 才能启动

    }

    /**
     * 获取服务
     * @param string $abstract
     * @return mixed
     */
    public function get($abstract)
    {
        if (isset($this->instances[$abstract])) // 此服务已经实例化过了
            return $this->instances[$abstract];

        $instance = $this->binding[$abstract]['concrete']($this); // 因为服务是闭包 加()就可以执行了
        if ($this->binding[$abstract]['is_singleton']) // 设置为单例
            $this->instances[$abstract] = $instance;

        return $instance;
    }

    /**
     * 是否有此服务
     * @param string $abstract
     * @return bool|void
     */
    public function has($abstract)
    {
        // TODO: Implement has() method.
    }

    /**
     * 单例模式
     * @return App
     */
    public static function getContainer()
    {
        return self::$instance ?? self::$instance = new self();
    }

    /**
     * 绑定服务
     * @param $abstract
     * @param $concrete
     * @param false $is_singleton
     */
    public function bind($abstract, $concrete, $is_singleton = false)
    {
        if (!$concrete instanceof Closure) // 如果具体实现不是闭包  那就生成闭包
            $concrete = function ($app) use ($concrete) {
                return $app->build($concrete);
            };
        $this->binding[$abstract] = compact('concrete', 'is_singleton'); // 存到$binding大数组里面
    }

    /**
     * 获取当前类的所有依赖
     * @param $parameters
     * @return array
     */
    protected function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter)
            if ($parameter->getClass())
                $dependencies[] = $this->get($parameter->getClass()->name);
        return $dependencies;
    }

    /**
     * 解析依赖
     * @param $concrete
     * @return mixed
     */
    public function build($concrete)
    {
        $reflector = new ReflectionClass($concrete); // 反射
        $constructor = $reflector->getConstructor(); // 获取构造函数
        if (is_null($constructor)) {
            return $reflector->newInstance(); // 没有构造函数？ 那就是没有依赖 直接返回实例
        }
        $dependencies = $constructor->getParameters(); // 获取构造函数的参数
        $instances = $this->getDependencies($dependencies);  // 当前类的所有实例化的依赖
        return $reflector->newInstanceArgs($instances); // 跟new 类($instances); 一样了
    }

    /**
     * 注册
     */
    protected function register()
    {
        $register = [
            'db' => Database::class,
            'response' => Response::class,
            'router' => Router::class,
            'pipeline' => PipeLine::class,
            'config' => Config::class,
            'redis' => \core\Redis::class,
            ViewInterface::class => Thinkphp::class
        ];

        foreach ($register as $name => $concrete) {
            $this->bind($name, $concrete, true);
        }
    }

    /**
     * 自动执行
     */
    protected function boot()
    {
        app('config')->init();
        app('db');
        app(ViewInterface::class)->init();
        app('router')->group(function ($router) {
            require_once FRAME_BASE_PATH . '/routes/web.php';
        });
    }

}