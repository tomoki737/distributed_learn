<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                'question' => $this->faker->text(),
                'answer' => $this->faker->text(),
                'share' => false,
                'user_id' => function() {
                    return User::factory()->create()->id;
                },
            ];
    }
}
