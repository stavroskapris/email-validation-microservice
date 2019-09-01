<?php

namespace Tests\Unit\app\Services;

use App\Providers\Cache\AbsentCache;
use App\Providers\Cache\MemCachedCache;
use App\Providers\Cache\RedisCache;


use App\Services\Cache\CacheGetService;
use App\Services\Cache\CacheService;

use Mockery;
use Tests\TestCase;

/**
 * Class CacheServiceTest
 *
 * @package Tests\Unit\app\Services
 */
class CacheServiceTest extends TestCase
{
    /**
     * @test
     * @see CacheService::get()
     */
    public function getCallsProperProvider()
    {
        $mockCacheGetService = Mockery::mock(CacheGetService::class);
        $this->app->instance(CacheGetService::class, $mockCacheGetService);

        if (class_exists('Redis')) {
            $mockRedisCache = Mockery::mock(RedisCache::class);
            $mockRedisCache->shouldReceive('get')
                ->withArgs(['hotmail.com'])
                ->once()
                ->andReturn(['hotmail.com' => false]);
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockRedisCache);
        } elseif (class_exists('Memcached')) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $mockMemcachedCache = Mockery::mock(MemCachedCache::class);
            $mockMemcachedCache->shouldReceive('get')
                ->withArgs(['hotmail.com'])
                ->once()
                ->andReturn(['hotmail.com' => false]);
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockMemcachedCache);
        } else {
            $mockAbsentCache = Mockery::mock(AbsentCache::class);
            $mockAbsentCache->shouldReceive('get')
                ->withArgs(['hotmail.com'])
                ->once()
                ->andReturn(['hotmail.com' => false]);
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockAbsentCache);
        }
        /** @noinspection PhpUnhandledExceptionInspection */
        $cacheServiceTest = app()->make(CacheService::class);
        $res = $cacheServiceTest->get('hotmail.com');
        $this->assertIsArray($res);
        $this->assertArrayHasKey('hotmail.com', $res);
    }

    /**
     * @test
     * @see CacheService::set()
     */
    public function setCallsProperProvider()
    {
        $mockCacheGetService = Mockery::mock(CacheGetService::class);
        $this->app->instance(CacheGetService::class, $mockCacheGetService);

        if (class_exists('Redis')) {
            $mockRedisCache = Mockery::mock(RedisCache::class);
            $mockRedisCache->shouldReceive('set')
                ->withArgs(['hotmail.com', 'false', config('cache.ttl')])
                ->once()
                ->andReturnNull();
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockRedisCache);
        } elseif (class_exists('Memcached')) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $mockMemcachedCache = Mockery::mock(MemCachedCache::class);
            $mockMemcachedCache->shouldReceive('set')
                ->withArgs(['hotmail.com', 'false', config('cache.ttl')])
                ->once()
                ->andReturnNull();
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockMemcachedCache);
        } else {
            $mockAbsentCache = Mockery::mock(AbsentCache::class);
            $mockAbsentCache->shouldReceive('set')
                ->withArgs(['hotmail.com', 'false', config('cache.ttl')])
                ->once()
                ->andReturnNull();
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockAbsentCache);
        }
        /** @noinspection PhpUnhandledExceptionInspection */
        $cacheServiceTest = app()->make(CacheService::class);
        $res = $cacheServiceTest->set('hotmail.com', 'false', config('cache.ttl'));
        $this->assertNull($res);
    }
}
