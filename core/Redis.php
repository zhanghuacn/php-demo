<?php

namespace core;

/**
 * TODO 待完善其他命令
 */
class Redis
{
    /**
     * @var array|null
     */
    private static ?array $instance = null;

    /**
     * @var array 所有Redis连接
     */
    private array $connections = [];

    /**
     * @var \Redis 当前Redis连接
     */
    private \Redis $connection;

    /**
     * @var array 配置
     */
    protected $config
        = [
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => '',
            'select' => 0,
            'timeout' => 0,
            'expire' => 0,
            'persistent' => false,
            'prefix' => '',
            'serialize' => true,
        ];

    /**
     * @var int 缓存读写次数
     */
    private int $writeTimes = 0;

    /**
     * @var int 缓存读取次数
     */
    private int $readTimes = 0;

    /**
     * redis constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            $this->config = array_merge($this->config, $config);
        }
    }

    /**
     * 连接Redis
     * @return \Redis
     * @throws \Exception
     */
    public function connect()
    {
        if (!extension_loaded('redis')) {
            throw new \Exception('not support: redis');
        }

        $config = $this->config;

        $key = md5(serialize($config));

        if (!isset($this->connections[$key])) {
            $redis = new \Redis();
            if ($config['persistent']) {
                $redis->pconnect($config['host'], $config['port'], $config['timeout'], 'persistent_id_' . $config['select']);
            } else {
                $redis->connect($config['host'], $config['port'], $config['timeout']);
            }

            if ('' != $config['password']) {
                $redis->auth($config['password']);
            }

            if (0 != $config['select']) {
                $redis->select($config['select']);
            }

            $this->connections[$key] = $redis;
        }
        // 记录当前连接
        $this->connection = $this->connections[$key];

        return $this->connection;
    }

    /**
     * 选择库
     * @param int $select
     * @return $this
     */
    public function select($select = 0)
    {
        if (0 != $select) {
            $this->connection->select($select);
        }

        return $this;
    }

    /**
     * 获取实际的缓存标识
     * @param string $name 缓存名
     * @return string
     */
    protected function getKey(string $name)
    {
        return $this->config['prefix'] . $name;
    }

    /**
     * 判断缓存
     * @param string $name 缓存变量名
     * @return bool|int
     * @throws \Exception
     */
    public function has(string $name)
    {
        $this->connect();

        return $this->connection->exists($this->getKey($name));
    }

    /**
     * 读取缓存
     * @param string $name 缓存变量名
     * @param mixed $default 默认值
     * @return false|mixed
     * @throws \Exception
     */
    public function get(string $name, $default = false)
    {
        $this->connect();

        $this->readTimes++;

        $value = $this->connection->get($this->getKey($name));

        if (is_null($value) || false === $value) {
            return $default;
        }

        return unserialize($value);
    }

    /**
     * 写入缓存
     * @param string $name 缓存变量名
     * @param mixed $value 存储数据
     * @param int|null $ttl
     * @return bool
     * @throws \Exception
     */
    public function set(string $name, $value, $ttl = null)
    {
        $this->connect();

        $this->writeTimes++;

        if (is_null($ttl)) {
            $ttl = $this->config['expire'];
        }

        $key = $this->getKey($name);
        $value = serialize($value);

        if ($ttl) {
            $result = $this->connection->setex($key, $ttl, $value);
        } else {
            $result = $this->connection->set($key, $value);
        }

        return $result;
    }

    /**
     * 关闭redis连接
     */
    public function __destruct()
    {
        foreach ($this->connections as $connection) {
            $connection->close();
        }
    }

    public function __call($method, $parameters)
    {
        return $this->connect()->$method(...$parameters);
    }

}