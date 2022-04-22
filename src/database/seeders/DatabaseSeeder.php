<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\QuestionSeeder;

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
        $this->call([
            QuestionSeeder::class,
        ]);
    }
}
