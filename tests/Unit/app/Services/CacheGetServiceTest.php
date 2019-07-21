<?php

namespace Tests\Unit\app\Services;

use App\Services\Cache\AbsentCacheService\AbsentCacheService;
use App\Services\CacheGetService;
use Cache;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\MemcachedStore;
use Illuminate\Cache\RedisStore;
use Illuminate\Contracts\Cache\Store;
use Memcached;
use Mockery;
use Tests\TestCase;

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
        $this->assertInstanceOf(Store::class, $cacheProvider);
    }

    /**
     * @test
     * @see CacheGetService::getCacheProvider()
     */
    public function getCacheProviderReturnsCacheProviderAsExpected()
    {
        $mockCacheManager = Mockery::mock(CacheManager::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $cacheGetServiceTest = app()->make(CacheGetService::class);
        $cacheProvider = $cacheGetServiceTest->getCacheProvider();
        if (class_exists('RedisStore')) {
            $mockRedisStore = Mockery::mock(RedisStore::class);

            $mockCacheManager->shouldReceive('getStore')
                ->andReturn($mockRedisStore);
            Cache::shouldReceive('driver')
                ->once()
                ->with('redis')
                ->andReturn($mockCacheManager);
            $this->assertIsObject($cacheProvider);
            /** @noinspection PhpParamsInspection */
            $this->assertInstanceOf(RedisStore::class, $cacheProvider);
        } elseif (class_exists('MemcachedStore')) {
            $mockMemcachedStore = Mockery::mock(Memcached::class);
            $mockCacheManager->shouldReceive('getStore')
                ->andReturn($mockMemcachedStore);
            Cache::shouldReceive('driver')
                ->once()
                ->with('memcached')
                ->andReturn($mockCacheManager);
            $this->assertIsObject($cacheProvider);
            /** @noinspection PhpParamsInspection */
            $this->assertInstanceOf(MemcachedStore::class, $cacheProvider);
        } else {
            $this->assertIsObject($cacheProvider);
            /** @noinspection PhpParamsInspection */
            $this->assertInstanceOf(AbsentCacheService::class, $cacheProvider);
        }
    }
}
