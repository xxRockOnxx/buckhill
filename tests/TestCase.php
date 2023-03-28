<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Assert that the response matches the success macro.
     *
     * @param  \Illuminate\Testing\TestResponse  $response
     * @param  array  $data
     * @param  array  $extra
     * @return \Illuminate\Testing\TestResponse
     */
    public function assertSuccessResponseMacro($response, $data = [], $extra = [])
    {
        $response->assertOk();

        $response->assertJson([
            'success' => 1,
            'data' => $data,
            'error' => null,
            'errors' => [],
            'extra' => $extra,
        ]);

        return $response;
    }

    public function assertErrorResponseMacro($response, $code = 500, $error = null, $errors = [], $trace = [])
    {
        $response->assertStatus($code);

        $response->assertJson([
            'success' => 0,
            'data' => [],
            'error' => $error,
            'errors' => $errors,
            'trace' => $trace,
        ]);

        return $response;
    }
}
