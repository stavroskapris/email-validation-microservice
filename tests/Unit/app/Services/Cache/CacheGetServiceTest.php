<?php

namespace Tests\Unit\app\Services;

use App\Http\Contracts\CacheInterface;
use App\Providers\Cache\AbsentCache;
use App\Providers\Cache\MemCachedCache;
use App\Providers\Cache\RedisCache;
use App\Services\Cache\CacheGetService;


use Tests\TestCase;

/**
 * Class CacheGetServiceTest
 *
 * @package Tests\Unit\app\Services
 */
class CacheGetServiceTest extends TestCase
{
    /**
     * @test
     * @see CacheGetService::getCacheProvider()
     */
    public function getCacheProviderReturnsInstanceOfStoreInterface()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cacheGetServiceTest = app()->make(CacheGetService::class);

        $cacheProvider = $cacheGetServiceTest->getCacheProvider();

        $this->assertIsObject($cacheProvider);
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(CacheInterface::class, $cacheProvider);
    }

    /**
     * @test
     * @see CacheGetService::getCacheProvider()
     */
    public function getCacheProviderReturnsCacheProviderAsExpected()
    {

        /** @noinspection PhpUnhandledExceptionInspection */
        $cacheGetServiceTest = app()->make(CacheGetService::class);
        $cacheProvider = $cacheGetServiceTest->getCacheProvider();
        if (class_exists('Redis')) {
            $this->assertIsObject($cacheProvider);
            /** @noinspection PhpParamsInspection */
            $this->assertInstanceOf(RedisCache::class, $cacheProvider);
        } elseif (class_exists('Memcached')) {
            $this->assertIsObject($cacheProvider);
            /** @noinspection PhpParamsInspection */
            $this->assertInstanceOf(MemCachedCache::class, $cacheProvider);
        } else {
            $this->assertIsObject($cacheProvider);
            /** @noinspection PhpParamsInspection */
            $this->assertInstanceOf(AbsentCache::class, $cacheProvider);
        }
    }
}
