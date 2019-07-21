<?php


namespace App\Providers\Cache;


use App\Http\Contracts\CacheInterface;
use App\Http\Models\LogExceptions;
use Memcached;

/**
 * Class MemCachedCache
 * @package App\Providers\Cache
 */
class MemCachedCache implements CacheInterface
{

    /**
     * @var Memcached
     */
    private $memcached;

    /**
     * Throwable
     */
    const EXCEPTION_TYPE = 'memcached_initialization_failure';

    /**
     * Memcached constructor.
     */
    public function __construct()
    {
        try {
            //create memcached instance
            $this->memcached = new Memcached();
            //connect with server and port
            $this->memcached->addServer(
                config('cache.stores.memcached.servers.host'),
                config('cache.stores.memcached.servers.port')
            );

        } catch (\Throwable $e) {
            LogExceptions::log($e, self::EXCEPTION_TYPE);
            return false;
        }

    }

    /**
     * Retrieve a cached item
     * if present
     * @param string $key
     * @return bool|string
     */
    public function get(string $key)
    {
        $this->memcached->get($key);
    }

    /**
     * Cache an item
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return mixed|void
     */
    public function set(string $key, string $value, $ttl = 10)
    {
        $this->memcached->set($key, $value, $ttl);
    }
}