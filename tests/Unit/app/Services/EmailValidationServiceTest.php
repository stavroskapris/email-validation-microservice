<?php

namespace Tests\Unit\app\Services;

use App\Providers\Validation\Debounce;
use App\Providers\Validation\OpenKickBoxIo;
use App\Services\EmailValidationService;
use App\Services\ValidationProviderGetService;
use Mockery;
use Tests\TestCase;

/**
 * Class EmailValidationServiceTest
 * @package Tests\Unit\app\Services
 */
class EmailValidationServiceTest extends TestCase
{
    /**
     * @test
     * @see EmailValidationService::validateEmail()
     */
    public function validateEmailUsesOpenKickBoxIoParamAsExpected()
    {
        $mockOpenKickBoxIo = Mockery::mock(OpenKickBoxIo::class)
            ->shouldReceive('validate')
            ->withArgs(['hotmail.com'])
            ->once()
            ->andReturn(true)
            ->getMock();
        $mockValidationProviderGetService = Mockery::mock(ValidationProviderGetService::class)
            ->shouldReceive("getValidationProvider")
            ->withArgs(['kickbox'])
            ->once()
            ->andReturn($mockOpenKickBoxIo)
            ->getMock();

        $this->app->instance(ValidationProviderGetService::class, $mockValidationProviderGetService);
        /** @noinspection PhpUnhandledExceptionInspection */
        $emailValidationService = app()->make(EmailValidationService::class);

        $result = $emailValidationService->validateEmail('hotmail.com', 'kickbox');

        $this->assertTrue($result);
    }

    /**
     * @test
     * @see EmailValidationService::validateEmail()
     */
    public function validateEmailUsesDebounceParamAsExpected()
    {
        $mockOpenKickBoxIo = Mockery::mock(Debounce::class)
            ->shouldReceive('validate')
            ->withArgs(['foo.com'])
            ->once()
            ->andReturn(true)
            ->getMock();
        $mockValidationProviderGetService = Mockery::mock(ValidationProviderGetService::class)
            ->shouldReceive("getValidationProvider")
            ->withArgs(['debounce'])
            ->once()
            ->andReturn($mockOpenKickBoxIo)
            ->getMock();

        $this->app->instance(ValidationProviderGetService::class, $mockValidationProviderGetService);
        /** @noinspection PhpUnhandledExceptionInspection */
        $emailValidationService = app()->make(EmailValidationService::class);

        $result = $emailValidationService->validateEmail('foo.com', 'debounce');

        $this->assertTrue($result);
    }
}
