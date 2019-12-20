<?php

namespace Tests\Unit\app\Providers\Validation;

use App\Providers\Validation\OpenKickBoxIo;
use App\Services\Cache\CacheService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;
use Mockery;

/**
 * Class OpenKickBoxIoProviderTest
 *
 * @package Tests\Unit\app\Providers\Validation
 */
class OpenKickBoxIoProviderTest extends TestCase
{
    /**
     * @test
     * @throws BindingResolutionException
     * @throws GuzzleException
     * @see OpenKickBoxIo::validateByRequest()
     */
    public function openKickBoxIoValidateByRequestReturnsFalseAsExpected()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockCacheService = Mockery::mock(CacheService::class);

        $this->app->instance(Client::class, $mockClient);
        $this->app->instance(CacheService::class, $mockCacheService);

        /** @var OpenKickBoxIo $validationProvider */
        $validationProvider = app()->make(OpenKickBoxIo::class);

        $mockCacheService->shouldReceive('set')
            ->once()
            ->withArgs(['hotmail.com', 'false', config('cache.ttl')])
            ->andReturnNull();

        $apiResponseBody = '{"disposable": false}';
        $mockClient->shouldReceive('request')
            ->once()
            ->andReturn(New Response(200, [], $apiResponseBody))
            ->getMock();
        $result = $validationProvider->validateByRequest('hotmail.com');
        $this->assertIsString($result);
        $this->assertEquals("false", $result);
    }

    /**
     * @test
     * @throws BindingResolutionException
     * @throws GuzzleException
     * @see OpenKickBoxIo::validateByRequest()
     */
    public function openKickBoxIoValidateByRequestReturnsTrueAsExpected()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockCacheService = Mockery::mock(CacheService::class);

        $this->app->instance(Client::class, $mockClient);
        $this->app->instance(CacheService::class, $mockCacheService);

        /** @var OpenKickBoxIo $validationProvider */
        $validationProvider = app()->make(OpenKickBoxIo::class);

        $mockCacheService->shouldReceive('set')
            ->once()
            ->withArgs(['malinator.com', "true", config('cache.ttl')])
            ->andReturnNull();

        $apiResponseBody = '{"disposable": "true"}';
        $mockClient->shouldReceive('request')
            ->once()
            ->andReturn(New Response(200, [], $apiResponseBody))
            ->getMock();
        $result = $validationProvider->validateByRequest('malinator.com');
        $this->assertIsString($result);
        $this->assertEquals("true", $result);
    }
}
