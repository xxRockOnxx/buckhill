<?php

namespace Tests\Unit\Stubs;

use App\Models\Contracts\HasListing as HasListingContract;
use App\Models\Traits\HasListing;

class HasListingStub implements HasListingContract
{
    use HasListing;

    public function getSortableColumns(): array
    {
        return ['created_at', 'updated_at', 'title'];
    }
}
