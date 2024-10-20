<?php

/**
 * FlashcardPracticeService class file
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
use App\Models\FlashcardPractice;
use App\Enums\FlashcardPracticeStatus;
use Illuminate\Database\Eloquent\Collection;

/**
 * FlashcardPracticeService class
 *
 * This class is to keep the business logic related to flashcard practice
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
class FlashcardPracticeService
{
    /**
     * Function to get flashcard practice data against a user
     * 
     * @param int $userId To get user specific information
     * 
     * @return Collection
     */
    public function getFlashcardsPractice(int $userId): Collection
    {
        return Flashcard::leftJoin(
            'flashcard_practices',
            function ($join) use ($userId) {
                $join->on('flashcards.id', '=', 'flashcard_practices.flashcard_id')
                    ->where('flashcard_practices.user_id', '=', $userId);
            }
        )->select(
                'flashcards.id',
                'flashcards.question',
                'flashcards.answer',
                'flashcard_practices.answer as user_answer',
                'flashcard_practices.status',
                'flashcard_practices.user_id'
            )->get();
    }

    /**
     * Function to save flashcard practice against a user
     * 
     * @param int        $flashcard_id To get user specific information
     * @param int|string $user_answer  user input answer
     * @param int        $userId       user id
     * @param string     $status       status of flashcard practice
     * 
     * @return void
     */
    public function saveFlashcarPractice(int $flashcard_id, string $user_answer, int $userId, string $status): void
    {

        FlashcardPractice::updateOrCreate(
            [
                'flashcard_id' => $flashcard_id,
                'user_id' => $userId
            ],
            [
                'answer' => $user_answer,
                'status' => $status
            ]
        );
    }

    /**
     * Function to flashcard practice progress
     * 
     * @param Collection $flashcards flashcards collection
     * 
     * @return int correct questions percentage
     */
    public function footerProgress(Collection $flashcards): float
    {
        $total = $flashcards->count();
        $correctCount = $flashcards->where('status', FlashcardPracticeStatus::CORRECT->value)->count();

        return ($correctCount / $total) * 100;
    }

    /**
     * Function to prepate dataset for practice table
     * 
     * @param Collection $flashcards flashcards collection
     * 
     * @return array practice data
     */
    public function preparePracticeTableData($flashcards): array
    {
        return $flashcards->map(
            function ($flashcard): array {
                return [
                    'id' => $flashcard->id,
                    'question' => $flashcard->question,
                    'answer' => $flashcard->user_answer,
                    'status' => is_null($flashcard->status) ? FlashcardPracticeStatus::NOT_ANSWERED->value : $flashcard->status,
                ];
            }
        )->toArray();

    }

    /**
     * Function to delete user practice data
     * 
     * @param int $userId user information
     * 
     * @return void
     */
    public function resetPractice(int $userId): void
    {
        FlashcardPractice::where('user_id', $userId)->delete();
    }

    /**
     * Function to get stats of user practice
     * 
     * @param int $userId user information
     * 
     * @return array stats
     */
    public function getStats(int $userId): array
    {

        $flashcards = $this->getFlashcardsPractice($userId);

        $total = $flashcards->count();
        $attempted = $flashcards->whereNotNull('status')->count();
        $correct = $flashcards->where('user_id', $userId)->where('status', FlashcardPracticeStatus::CORRECT->value)->count();

        return [
            'total' => $total,
            'attempted' => $attempted,
            'correct' => $correct,
            'attempted_percentage' => $total ? ($attempted / $total) * 100 : 0,
            'correct_percentage' => $total ? ($correct / $total) * 100 : 0,
        ];
    }
}
