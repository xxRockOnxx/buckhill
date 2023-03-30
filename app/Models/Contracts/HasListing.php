<?php

namespace App\Models\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 */
interface HasListing
{
    /**
     * @param Builder<TModelClass> $query
     * @return LengthAwarePaginator<TModelClass>
     */
    public function scopeListing(Builder $query, Request $request): LengthAwarePaginator;

    /**
     * @return array<int, string>
     */
    public function getSortableColumns(): array;
}
