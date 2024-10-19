<?php

namespace App\Services;

use App\Models\Flashcard;
use App\Models\FlashcardPractice;
use App\Enums\FlashcardPracticeStatus;

class FlashcardPracticeService
{
    public function getFlashcardsPractice($userId)
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

    public function saveFlashcarPractice($flashcard_id, $user_answer, $userId, $status)
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

    public function footerProgress($flashcards)
    {
        $total = $flashcards->count();
        $correctCount = $flashcards->where('status', FlashcardPracticeStatus::CORRECT->value)->count();

        return ($correctCount / $total) * 100;
    }

    public function preparePracticeTableData($flashcards)
    {
        return $flashcards->map(function ($flashcard) {
            return [
                'id' => $flashcard->id,
                'question' => $flashcard->question,
                'answer' => $flashcard->user_answer,
                'status' => is_null($flashcard->status) ? FlashcardPracticeStatus::NOT_ANSWERED->value : $flashcard->status,
            ];
        })->toArray();

    }

    public function resetPractice($userId)
    {
        FlashcardPractice::where('user_id', $userId)->delete();
    }

    public function getStats($userId)
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
