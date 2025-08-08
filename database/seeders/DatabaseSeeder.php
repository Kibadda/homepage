<?php

namespace Database\Seeders;

use App\Models\Memory;
use App\Models\Timeline;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        Timeline::factory(2)
            ->has(
                Memory::factory()
                    ->count(10)
            )
            ->create();
    }
}
