<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;

class OrderStatusController
{
    /**
     * @return LengthAwarePaginator<OrderStatus>
     */
    public function index(): LengthAwarePaginator
    {
        return OrderStatus::listing(request());
    }

    public function show(string $uuid): JsonResponse
    {
        $status = OrderStatus::query()->where('uuid', $uuid)->first();

        if ($status === null) {
            return Response::error(404, 'Order status not found');
        }

        return Response::success(200, $status->toArray());
    }
}
