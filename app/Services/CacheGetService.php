<?php

namespace App\Services;

use App\Http\Models\LogExceptions;
use App\Services\Cache\AbsentCacheService\AbsentCacheService;
use Cache;
use Exception;
use Illuminate\Contracts\Cache\Store;

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
     * @var Store
     */
    private $cacheStore;

    public function __construct(AbsentCacheService $absentCacheService)
    {
        $this->cacheStore = $absentCacheService;
    }

    /**
     * @return Store
     * @throws Exception
     */
    public function getCacheProvider(): Store
    {
        try {

            if (class_exists('RedisStore')) {
                $this->cacheStore = Cache::driver('redis')
                    ->getStore();
            } elseif (class_exists('MemcachedStore')) {
                $this->cacheStore = Cache::driver('memcached')
                    ->getStore();
            }

            return $this->cacheStore;
        } catch (Exception $e) {
            //log exception and continue without cache
            LogExceptions::log($e, self::EXCEPTION_TYPE);

            return $this->cacheStore;
        }
    }
}
