<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderStatus;

class OrderStatusController
{
    public function index()
    {
        return OrderStatus::query()
            ->orderBy(request()->input('sortBy', 'created_at'), request()->boolean('desc') ? 'desc' : 'asc')
            ->paginate(request()->input('limit', 10), ['*'], 'page', request()->input('page', 1));
    }

    public function show(string $uuid)
    {
        $status = OrderStatus::query()->where('uuid', $uuid)->first();

        if ($status === null) {
            return response()->error(404, 'Order status not found');
        }

        return response()->success(200, $status->toArray());
    }
}
