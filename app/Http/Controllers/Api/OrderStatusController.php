<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Response;

class OrderStatusController
{
    /**
     * @return LengthAwarePaginator<OrderStatus>
     */
    public function index(): LengthAwarePaginator
    {
        return OrderStatus::query()
            ->orderBy(request()->input('sortBy', 'created_at'), request()->boolean('desc') ? 'desc' : 'asc')
            ->paginate(request()->input('limit', 10), ['*'], 'page', request()->input('page', 1));
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
