<?php

namespace App\Models;

use App\Models\Contracts\HasListing as HasListingContract;
use App\Models\Traits\HasListing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @method static LengthAwarePaginator listing(Request $request)
 */
class Post extends Model implements HasListingContract
{
    use HasFactory, HasListing;

    protected $casts = [
        'metadata' => 'array',
    ];

    public function getSortableColumns(): array
    {
        return [
            'title',
            'slug',
            'created_at',
            'updated_at',
        ];
    }
}
