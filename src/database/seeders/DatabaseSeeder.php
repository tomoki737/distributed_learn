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
        // \App\Models\User::factory()->create();
        // \App\Models\User::factory()->create();
        // \App\Models\User::factory()->create();
        $this->call([
            QuestionSeeder::class,
        ]);
    }
}
