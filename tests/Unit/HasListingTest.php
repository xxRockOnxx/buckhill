<?php

namespace Tests\Unit;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Mock;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Stubs\HasListingStub;

class HasListingTest extends TestCase
{
    public function test_should_use_default_values_when_no_query_params_are_provided()
    {
        $request = Request::create('/');

        $builder = Mockery::spy(Builder::class, function (MockInterface $mock) {
            $paginator = Mockery::mock(LengthAwarePaginator::class);
            $mock->shouldReceive('orderBy')->with('created_at', 'asc')->andReturnSelf();
            $mock->shouldReceive('paginate')->with(10, ['*'], 'page', 1)->andReturn($paginator);
        });

        (new HasListingStub())->scopeListing($builder, $request);

        $this->assertTrue(true);
    }

    public function test_should_use_fallback_values_when_invalid_query_params_are_provided()
    {
        $request = Request::create('/');

        $request->query->add([
            'sortBy' => 'invalid-abc-def',
            'limit' => -999,
            'page' => -999,
        ]);

        $builder = Mockery::mock(Builder::class, function (MockInterface $mock) {
            $paginator = Mockery::mock(LengthAwarePaginator::class);
            $mock->shouldReceive('orderBy')->with('created_at', 'asc')->andReturnSelf();
            $mock->shouldReceive('paginate')->with(1, ['*'], 'page', 1)->andReturn($paginator);
        });

        (new HasListingStub())->scopeListing($builder, $request);

        $this->assertTrue(true);
    }

    public function test_should_use_given_valid_query_params()
    {
        $request = Request::create('/');

        $request->query->add([
            'sortBy' => 'updated_at',
            'desc' => 'true',
            'limit' => 50,
            'page' => 2,
        ]);

        $builder = Mockery::mock(Builder::class, function (MockInterface $mock) {
            $paginator = Mockery::mock(LengthAwarePaginator::class);
            $mock->shouldReceive('orderBy')->with('updated_at', 'desc')->andReturnSelf();
            $mock->shouldReceive('paginate')->with(50, ['*'], 'page', 2)->andReturn($paginator);
        });

        (new HasListingStub())->scopeListing($builder, $request);

        $this->assertTrue(true);
    }
}
