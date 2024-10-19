<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase; ERROR
use Tests\TestCase;
use App\Models\Flashcard;
use App\Models\FlashcardPractice;
use App\Services\FlashcardPracticeService;
use App\Enums\FlashcardPracticeStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlashcardPracticeTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_flashcards_practice()
    {
        $userId = 1;

        $this->_addFlashcardsWithPractice(3, FlashcardPracticeStatus::CORRECT->value, $userId);

        $flashcardPracticeService = new FlashcardPracticeService();
        $flashcards = $flashcardPracticeService->getFlashcardsPractice($userId);

        $this->assertCount(3, $flashcards);
    }

    public function test_save_flashcard_practice()
    {
        $userId = 1;
        $flashcard = Flashcard::factory()->create();
        $flashcardPracticeService = new FlashcardPracticeService();

        $flashcardPracticeService->saveFlashcarPractice(
            $flashcard->id,
            $flashcard->answer,
            $userId,
            FlashcardPracticeStatus::CORRECT->value
        );
        $this->assertDatabaseHas(
            'flashcard_practices',
            [
                'flashcard_id' => $flashcard->id,
                'user_id' => $userId,
                'answer' => $flashcard->answer,
                'status' => FlashcardPracticeStatus::CORRECT->value
            ]
        );
    }

    public function test_reset_practice()
    {
        $userId = 1;
        $flashcard = Flashcard::factory()->create();
        $flashcardPracticeService = new FlashcardPracticeService();

        $flashcardPracticeService->saveFlashcarPractice(
            $flashcard->id,
            $flashcard->answer,
            $userId,
            FlashcardPracticeStatus::CORRECT->value
        );
        $flashcardPracticeService->resetPractice($userId);
        $this->assertDatabaseMissing('flashcard_practices', ['user_id' => $userId]);
    }

    public function test_stats()
    {
        $userId = 1;

        $this->_addFlashcardsWithPractice(4, FlashcardPracticeStatus::CORRECT->value, $userId);
        $this->_addFlashcardsWithPractice(4, FlashcardPracticeStatus::INCORRECT->value, $userId);

        //not answered
        $flashcards = Flashcard::factory()->count(2)->create();

        $flashcardPracticeService = new FlashcardPracticeService();
        $stats = $flashcardPracticeService->getStats($userId);


        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('attempted', $stats);
        $this->assertArrayHasKey('correct', $stats);

        $this->assertEquals($stats['total'], 10);
        $this->assertEquals($stats['correct'], 4);
        $this->assertEquals($stats['attempted'], 8);
    }

    private function _addFlashcardsWithPractice($count, $status, $userId)
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
