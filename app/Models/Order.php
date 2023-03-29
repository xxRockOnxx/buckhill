<?php

namespace App\Models;

use App\Models\Contracts\HasListing as HasListingContract;
use App\Models\Traits\HasListing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * @method static LengthAwarePaginator listing(Request $request)
 */
class Order extends Model implements HasListingContract
{
    use HasFactory, HasListing;

    protected $casts = [
        'products' => 'array',
        'address' => 'array',
        'shipped_at' => 'datetime',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'payment_id',
        'order_status_id',
    ];

    public function getSortableColumns(): array
    {
        return [
            'delivery_fee',
            'amount',
            'created_at',
            'updated_at',
            'shipped_at',
        ];
    }

    /**
     * @return BelongsTo<Payment, Order>
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * @return BelongsTo<OrderStatus, Order>
     */
    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    /**
     * @return BelongsTo<User, Order>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
