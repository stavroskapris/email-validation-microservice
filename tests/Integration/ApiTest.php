<?php

namespace Tests\Integration;

use Tests\TestCase;

/**
 * Class ApiTest
 *
 * @package Tests\Integration
 */
class ApiTest extends TestCase
{
    /**
     * @test
     */
    public function apiAcceptsPostRequest()
    {
        $response = $this->post('/api/validate', ['email' => 'hotmail.com', 'provider' => 'debounce']);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function apiNotAcceptsGetRequest()
    {
        $response = $this->get('/api/validate', ['email' => 'hotmail.com', 'provider' => 'debounce']);
        $response->assertStatus(405);
    }

    /**
     * @test
     */
    public function emailParamIsRequired()
    {
        $response = $this->post('/api/validate', ['provider' => 'debounce']);
        $response->assertStatus(422);
        $responseArray = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $responseArray);
        $this->assertSame('The email param is required', $responseArray['message']);
    }

    /**
     * @test
     */
    public function providerParamIsOptional()
    {
        $response = $this->post('/api/validate', ['email' => 'hotmail.com']);
        $response->assertStatus(200);
        $responseArray = json_decode($response->getContent(), true);
        $this->assertArrayNotHasKey('message', $responseArray);
        $this->assertArrayHasKey('disposable', $responseArray);
    }
}
