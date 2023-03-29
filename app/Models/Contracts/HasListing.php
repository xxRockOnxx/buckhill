<?php

namespace App\Models\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface HasListing
{
    public function scopeListing(Builder $query, Request $request): LengthAwarePaginator;

    public function getSortableColumns(): array;
}
