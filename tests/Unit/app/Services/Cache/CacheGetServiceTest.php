<?php

namespace Tests\Unit\app\Services;

use App\Http\Contracts\CacheInterface;
use App\Providers\Cache\AbsentCache;
use App\Providers\Cache\MemCachedCache;
use App\Providers\Cache\RedisCache;
use App\Services\Cache\CacheGetService;

use Illuminate\Contracts\Container\BindingResolutionException;
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
     * @throws BindingResolutionException
     * @see CacheGetService::getCacheProvider()
     */
    public function getCacheProviderReturnsInstanceOfStoreInterface()
    {
        /** @var  CacheGetService $cacheGetServiceTest */
        $cacheGetServiceTest = app()->make(CacheGetService::class);

        $cacheProvider = $cacheGetServiceTest->getCacheProvider();

        $this->assertIsObject($cacheProvider);
        $this->assertInstanceOf(CacheInterface::class, $cacheProvider);
    }

    /**
     * @test
     * @throws BindingResolutionException
     * @see CacheGetService::getCacheProvider()
     */
    public function getCacheProviderReturnsCacheProviderAsExpected()
    {

        /** @var CacheGetService $cacheGetServiceTest */
        $cacheGetServiceTest = app()->make(CacheGetService::class);
        $cacheProvider = $cacheGetServiceTest->getCacheProvider();
        if (class_exists('Redis')) {
            $this->assertIsObject($cacheProvider);
            $this->assertInstanceOf(RedisCache::class, $cacheProvider);
        } elseif (class_exists('Memcached')) {
            $this->assertIsObject($cacheProvider);
            $this->assertInstanceOf(MemCachedCache::class, $cacheProvider);
        } else {
            $this->assertIsObject($cacheProvider);
            $this->assertInstanceOf(AbsentCache::class, $cacheProvider);
        }
    }
}
