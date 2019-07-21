<?php

namespace Tests\Unit\app\Providers;

use App\Providers\ValidationProviders\OpenKickBoxIo;
use App\Services\CacheService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

use Tests\TestCase;
use Mockery;

class OpenKickBoxIoProviderTest extends TestCase
{
    /**
     * @test
     * @see OpenKickBoxIo::validateByRequest()
     */
    public function openKickBoxIoValidateByRequestReturnsFalseAsExpected()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockCacheService = Mockery::mock(CacheService::class);

        $this->app->instance(Client::class, $mockClient);
        $this->app->instance(CacheService::class, $mockCacheService);

        /** @noinspection PhpUnhandledExceptionInspection */
        $validationProvider = app()->make(OpenKickBoxIo::class);

        $mockCacheService->shouldReceive('set')
            ->once()
            ->withArgs(['hotmail.com', false, config('cache.ttl')])
            ->andReturnNull();

        $apiResponseBody = '{"disposable": false}';
        $mockClient->shouldReceive('request')
            ->once()
            ->andReturn(New Response(200, [], $apiResponseBody))
            ->getMock();

        $this->assertFalse($validationProvider->validateByRequest('hotmail.com'));
    }

    /**
     * @test
     * @see OpenKickBoxIo::validateByRequest()
     */
    public function openKickBoxIoValidateByRequestReturnsTrueAsExpected()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockCacheService = Mockery::mock(CacheService::class);

        $this->app->instance(Client::class, $mockClient);
        $this->app->instance(CacheService::class, $mockCacheService);

        /** @noinspection PhpUnhandledExceptionInspection */
        $validationProvider = app()->make(OpenKickBoxIo::class);

        $mockCacheService->shouldReceive('set')
            ->once()
            ->withArgs(['hotmail.com', true, config('cache.ttl')])
            ->andReturnNull();

        $apiResponseBody = '{"disposable": true}';
        $mockClient->shouldReceive('request')
            ->once()
            ->andReturn(New Response(200, [], $apiResponseBody))
            ->getMock();

        $this->assertTrue($validationProvider->validateByRequest('hotmail.com'));
    }
}
