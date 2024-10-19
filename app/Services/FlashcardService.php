<?php

namespace App\Services;

use App\Models\Flashcard;
use Validator;

class FlashcardService
{
    public function createFlashcard($question, $answer)
    {
        $validator = Validator::make(compact('question', 'answer'), [
            'question' => 'required',
            'answer' => 'required',
        ]);
        $status['success'] = true;
        if ($validator->fails()) {
            $status['success'] = false;
            $status['message'] = $validator->errors()->first();
        } else {
            $status['flashcard'] = Flashcard::create(compact('question', 'answer'));
        }

        return $status;
    }

    public function getFlashcards()
    {
        return Flashcard::all(['id', 'question', 'answer']);
    }
}
