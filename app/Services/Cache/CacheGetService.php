<?php

namespace App\Services\Cache;

use App\Http\Contracts\CacheInterface;
use App\Http\Models\LogExceptions;
use App\Providers\Cache\AbsentCache;
use App\Providers\Cache\MemCachedCache;
use App\Providers\Cache\RedisCache;

use Exception;


/**
 * Class CacheGetService
 *
 * @package App\Services
 */
class CacheGetService
{
    /**
     * Exception
     */
    const EXCEPTION_TYPE = 'cacheGetService_failure';

    /**
     * @var CacheInterface
     */
    private $cacheStore;


    /**
     * @return CacheInterface
     */
    public function getCacheProvider(): CacheInterface
    {
        try {

            if (class_exists('Memcached')) {
                $this->cacheStore = new MemCachedCache();
            } elseif (class_exists('Redis')) {
                $this->cacheStore = new RedisCache();
            }

            return $this->cacheStore ? $this->cacheStore : new AbsentCache();
        } catch (Exception $e) {
            //log exception and continue without cache
            LogExceptions::log($e, self::EXCEPTION_TYPE);

            return new AbsentCache();
        }
    }
}
