<?php

namespace App\Models;

use App\Models\Contracts\HasListing as HasListingContract;
use App\Models\Traits\HasListing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderStatus extends Model implements HasListingContract
{
    use HasFactory, HasListing;

    protected $fillable = [
        'uuid',
        'title',
    ];

    protected $sortable = [
        'title',
        'created_at',
        'updated_at',
    ];

    public function getSortableColumns(): array
    {
        return $this->sortable;
    }

    /**
     * @return HasMany<Order>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
