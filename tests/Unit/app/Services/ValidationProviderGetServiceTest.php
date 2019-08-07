<?php

namespace Tests\Unit\app\Services;

use App\Providers\Validation\Debounce;
use App\Providers\Validation\OpenKickBoxIo;
use App\Services\ValidationProviderGetService;
use Mockery;
use Tests\TestCase;

/**
 * Class ValidationProviderGetServiceTest
 * @package Tests\Unit\app\Services
 */
class ValidationProviderGetServiceTest extends TestCase
{
    /**
     * @test
     * @see ValidationProviderGetService::getValidationProvider()
     */
    public function getValidationProviderReturnsOpenKickBoxIoInstanceAsDefault()
    {
        $mockOpenKickBoxIo = Mockery::mock(OpenKickBoxIo::class);
        $mockDebounce = Mockery::mock(Debounce::class);
        $this->app->instance(OpenKickBoxIo::class, $mockOpenKickBoxIo);
        $this->app->instance(Debounce::class, $mockDebounce);

        /** @noinspection PhpUnhandledExceptionInspection */
        $validationProviderGetService = app()->make(ValidationProviderGetService::class);

        $validationProvider = $validationProviderGetService->getValidationProvider(null);
        $this->assertIsObject($validationProvider);
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(OpenKickBoxIo::class, $validationProvider);
    }

    /**
     * @test
     * @see ValidationProviderGetService::getValidationProvider()
     */
    public function getValidationProviderReturnsOpenKickBoxIoInstanceAsExpected()
    {
        $mockOpenKickBoxIo = Mockery::mock(OpenKickBoxIo::class);
        $mockDebounce = Mockery::mock(Debounce::class);
        $this->app->instance(OpenKickBoxIo::class, $mockOpenKickBoxIo);
        $this->app->instance(Debounce::class, $mockDebounce);

        /** @noinspection PhpUnhandledExceptionInspection */
        $validationProviderGetService = app()->make(ValidationProviderGetService::class);

        $validationProvider = $validationProviderGetService->getValidationProvider(null);
        $this->assertIsObject($validationProvider);
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(OpenKickBoxIo::class, $validationProvider);
    }

    /**
     * @test
     * @see ValidationProviderGetService::getValidationProvider()
     */
    public function getValidationProviderReturnsDebounceInstanceAsExpected()
    {
        $mockOpenKickBoxIo = Mockery::mock(OpenKickBoxIo::class);
        $mockDebounce = Mockery::mock(Debounce::class);
        $this->app->instance(OpenKickBoxIo::class, $mockOpenKickBoxIo);
        $this->app->instance(Debounce::class, $mockDebounce);

        /** @noinspection PhpUnhandledExceptionInspection */
        $validationProviderGetService = app()->make(ValidationProviderGetService::class);

        $validationProvider = $validationProviderGetService->getValidationProvider('debounce');
        $this->assertIsObject($validationProvider);
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(Debounce::class, $validationProvider);
    }
}
