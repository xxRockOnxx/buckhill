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

        // In this example app, all the listable models have a `created_at` column
        // so this will do for now. We can make this dynamic if needed
        // but it reduces PHPInsights complexity score.
        if (! in_array($sortBy, $this->getSortableColumns())) {
            $sortBy = 'created_at';
        }

        // The default values can be dynamic but for now, we'll just use these ones.
        // It reduces PHPInsights complexity score.
        $direction = $request->boolean('desc', false) ? 'desc' : 'asc';
        $limit = max(1, $request->input('limit', 10));
        $page = max(1, $request->input('page', 1));

        return $query
            ->orderBy($sortBy, $direction)
            ->paginate($limit, ['*'], 'page', $page);
    }
}
