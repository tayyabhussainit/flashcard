<?php

/**
 * TestHelperTrait trait file
 *
 * PHP Version 8.3
 *
 * @category Trait
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */


namespace Tests\Helpers;

use App\Models\Flashcard;
use App\Models\FlashcardPractice;

/**
 * TestHelperTrait trait
 *
 * This trait contains helper functions
 *
 * @category Trait
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
trait TestHelperTrait
{

    /**
     * Create test flashcards and flashcard practice data
     * 
     * @param int    $count  count of flashcards to be created
     * @param string $status status of flashcard practice data
     * @param int    $userId user id
     * 
     * @return void
     */
    public function addFlashcardsWithPractice(int $count, string $status, int $userId): void
    {
        $flashcards = Flashcard::factory()->count($count)->create();

        foreach ($flashcards as $flashcard) {
            FlashcardPractice::factory()->create(
                [
                    'flashcard_id' => $flashcard->id,
                    'user_id' => $userId,
                    'answer' => $flashcard->answer,
                    'status' => $status
                ]
            );
        }
    }
}
