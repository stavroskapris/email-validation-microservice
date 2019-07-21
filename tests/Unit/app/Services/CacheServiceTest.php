<?php

namespace Tests\Unit\app\Services;

use App\Services\Cache\AbsentCacheService\AbsentCacheService;
use App\Services\CacheGetService;
use App\Services\CacheService;
use Illuminate\Cache\RedisStore;

use /** @noinspection PhpComposerExtensionStubsInspection */
    Memcached;
use Mockery;
use Tests\TestCase;

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

        if (class_exists('RedisStore')) {
            $mockRedisStore = Mockery::mock(RedisStore::class);
            $mockRedisStore->shouldReceive('get')
                ->withArgs(['hotmail.com'])
                ->once()
                ->andReturn(['hotmail.com' => false]);
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockRedisStore);
        } elseif (class_exists('MemcachedStore')) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $mockMemcachedStore = Mockery::mock(Memcached::class);
            $mockMemcachedStore->shouldReceive('get')
                ->withArgs(['hotmail.com'])
                ->once()
                ->andReturn(['hotmail.com' => false]);
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockMemcachedStore);
        } else {
            $mockAbsentStore = Mockery::mock(AbsentCacheService::class);
            $mockAbsentStore->shouldReceive('get')
                ->withArgs(['hotmail.com'])
                ->once()
                ->andReturn(['hotmail.com' => false]);
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockAbsentStore);
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
    public function putCallsProperProvider()
    {
        $mockCacheGetService = Mockery::mock(CacheGetService::class);
        $this->app->instance(CacheGetService::class, $mockCacheGetService);

        if (class_exists('RedisStore')) {
            $mockRedisStore = Mockery::mock(RedisStore::class);
            $mockRedisStore->shouldReceive('put')
                ->withArgs(['hotmail.com', false, config('cache.ttl')])
                ->once()
                ->andReturnNull();
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockRedisStore);
        } elseif (class_exists('MemcachedStore')) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $mockMemcachedStore = Mockery::mock(Memcached::class);
            $mockMemcachedStore->shouldReceive('put')
                ->withArgs(['hotmail.com', false, config('cache.ttl')])
                ->once()
                ->andReturnNull();
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockMemcachedStore);
        } else {
            $mockAbsentStore = Mockery::mock(AbsentCacheService::class);
            $mockAbsentStore->shouldReceive('put')
                ->withArgs(['hotmail.com', false, config('cache.ttl')])
                ->once()
                ->andReturnNull();
            $mockCacheGetService->shouldReceive('getCacheProvider')
                ->once()
                ->andReturn($mockAbsentStore);
        }
        /** @noinspection PhpUnhandledExceptionInspection */
        $cacheServiceTest = app()->make(CacheService::class);
        $res = $cacheServiceTest->set('hotmail.com', false, config('cache.ttl'));
        $this->assertNull($res);
    }
}
