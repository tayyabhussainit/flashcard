<?php

namespace App\Services;

use App\Models\Flashcard;

class FlashcardService
{
    public function createFlashcard($data)
    {
        return Flashcard::create($data);
    }

    public function getFlashcards()
    {
        return Flashcard::all(['id', 'question', 'answer']);
    }
}
