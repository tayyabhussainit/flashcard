<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Flashcard;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flashcard>
 */
class FlashcardFactory extends Factory
{
    protected $model = Flashcard::class;

    public function definition()
    {
        return [
            'question' => $this->faker->sentence,
            'answer' => $this->faker->sentence,
        ];
    }
}
