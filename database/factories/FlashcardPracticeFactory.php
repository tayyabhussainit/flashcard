<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FlashcardPractice;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlashcardPractice>
 */
class FlashcardPracticeFactory extends Factory
{
    protected $model = FlashcardPractice::class;

    public function definition()
    {
        return [
            'flashcard_id' => 1,
            'user_id' => 1,
            'answer' => $this->faker->sentence,
            'status' => true
        ];
    }
}
