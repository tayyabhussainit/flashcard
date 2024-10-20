<?php

/**
 * FlashcardService class file
 *
 * PHP Version 8.3
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */

namespace App\Services;

use App\Models\Flashcard;
use Validator;
use Illuminate\Database\Eloquent\Collection;

/**
 * FlashcardService class
 *
 * This class is to keep the business logic related to flashcards
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
class FlashcardService
{

    /**
     * Create flashcard record
     * 
     * @param string|null $question flashcard question
     * @param string|null $answer   flashcard answer
     * 
     * @return array
     */
    public function createFlashcard(string|null $question, string|null $answer): array
    {
        $validator = Validator::make(
            compact('question', 'answer'),
            [
                'question' => 'required',
                'answer' => 'required',
            ]
        );
        $status['success'] = true;
        if ($validator->fails()) {
            $status['success'] = false;
            $status['message'] = $validator->errors()->first();
        } else {
            $status['flashcard'] = Flashcard::create(compact('question', 'answer'));
        }

        return $status;
    }

    /**
     * Get flashcard records
     * 
     * @return Collection
     */
    public function getFlashcards(): Collection
    {
        return Flashcard::all(['id', 'question', 'answer']);
    }
}
