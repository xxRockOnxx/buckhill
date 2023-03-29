<?php

namespace App\Models\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

trait HasListing
{
    public function scopeListing(Builder $query, Request $request): LengthAwarePaginator
    {
        $sortBy = $request->input('sortBy', 'created_at');

        if (! in_array($sortBy, $this->getSortableColumns())) {
            $sortBy = 'created_at';
        }

        $direction = $request->boolean('desc') ? 'desc' : 'asc';
        $limit = max(1, $request->input('limit', 10));
        $page = max(1, $request->input('page', 1));

        return $query
            ->orderBy($sortBy, $direction)
            ->paginate($limit, ['*'], 'page', $page);
    }
}
