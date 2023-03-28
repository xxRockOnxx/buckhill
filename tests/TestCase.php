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
}
