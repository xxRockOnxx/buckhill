<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'open',
            'pending payment',
            'paid',
            'shipped',
            'cancelled',
        ];

        $data = array_map(function ($status) {
            return [
                'uuid' => Str::uuid(),
                'title' => $status,
            ];
        }, $statuses);

        OrderStatus::insert($data);
    }
}
