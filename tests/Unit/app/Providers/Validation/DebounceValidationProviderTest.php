<?php

namespace Tests\Unit\app\Providers\Validation;

use App\Providers\Validation\Debounce;
use App\Services\Cache\CacheService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;
use Mockery;

/**
 * Class DebounceValidationProviderTest
 *
 * @package Tests\Unit\app\Validation\Providers
 */
class DebounceValidationProviderTest extends TestCase
{
    /**
     * @test
     * @throws BindingResolutionException
     * @throws GuzzleException
     * @see Debounce::validateByRequest()
     */
    public function debounceValidateByRequestReturnsFalseAsExpected()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockCacheService = Mockery::mock(CacheService::class);

        $this->app->instance(Client::class, $mockClient);
        $this->app->instance(CacheService::class, $mockCacheService);

        /** @var Debounce $validationProvider */
        $validationProvider = app()->make(Debounce::class);

        $mockCacheService->shouldReceive('set')
            ->once()
            ->withArgs(['hotmail.com', 'false', config('cache.ttl')])
            ->andReturnNull();

        $apiResponseBody = '{"disposable": "false"}';
        $mockClient->shouldReceive('request')
            ->once()
            ->andReturn(New Response(200, [], $apiResponseBody))
            ->getMock();
        $result = $validationProvider->validateByRequest('hotmail.com');
        $this->assertIsString($result);
        $this->assertEquals('false', $result);
    }

    /**
     * @test
     * @throws GuzzleException
     * @throws BindingResolutionException
     * @see Debounce::validateByRequest()
     */
    public function debounceValidateByRequestReturnsTrueAsExpected()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockCacheService = Mockery::mock(CacheService::class);

        $this->app->instance(Client::class, $mockClient);
        $this->app->instance(CacheService::class, $mockCacheService);

        /** @var Debounce $validationProvider */
        $validationProvider = app()->make(Debounce::class);

        $mockCacheService->shouldReceive('set')
            ->once()
            ->withArgs(['hotmail.com', 'true', config('cache.ttl')])
            ->andReturnNull();

        $apiResponseBody = '{"disposable": "true"}';
        $mockClient->shouldReceive('request')
            ->once()
            ->andReturn(New Response(200, [], $apiResponseBody))
            ->getMock();
        $result = $validationProvider->validateByRequest('hotmail.com');
        $this->assertIsString($result);
        $this->assertEquals('true', $result);
    }
}
