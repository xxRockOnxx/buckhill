<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@buckhill.co.uk',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'),
        ]);
    }
}
