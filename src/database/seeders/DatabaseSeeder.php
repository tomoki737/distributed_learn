<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create(['email' => 'demo1@example.com']);
        \App\Models\User::factory()->create(['email' => 'demo2@example.com']);
        \App\Models\User::factory()->create(['email' => 'demo3@example.com']);
    }
}
