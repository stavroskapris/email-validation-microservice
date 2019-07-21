<?php

namespace Tests\Unit\app\Providers;

use App\Providers\ValidationProviders\Debounce;
use App\Services\CacheService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Mockery;

class DebounceValidationProviderTest extends TestCase
{
    /**
     * @test
     * @see Debounce::validateByRequest()
     */
    public function debounceValidateByRequestReturnsFalseAsExpected()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockCacheService = Mockery::mock(CacheService::class);

        $this->app->instance(Client::class, $mockClient);
        $this->app->instance(CacheService::class, $mockCacheService);

        /** @noinspection PhpUnhandledExceptionInspection */
        $validationProvider = app()->make(Debounce::class);

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
     * @see Debounce::validateByRequest()
     */
    public function debounceValidateByRequestReturnsTrueAsExpected()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockCacheService = Mockery::mock(CacheService::class);

        $this->app->instance(Client::class, $mockClient);
        $this->app->instance(CacheService::class, $mockCacheService);

        /** @noinspection PhpUnhandledExceptionInspection */
        $validationProvider = app()->make(Debounce::class);

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
