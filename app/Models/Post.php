<?php

namespace App\Models;

use App\Models\Contracts\HasListing as HasListingContract;
use App\Models\Traits\HasListing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements HasListingContract
{
    use HasFactory, HasListing;

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $sortable = [
        'title',
        'slug',
        'created_at',
        'updated_at',
    ];

    public function getSortableColumns(): array
    {
        return $this->sortable;
    }
}
