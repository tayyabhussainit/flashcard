<?php

/**
 * FlashcardPracticeTest class file
 *
 * PHP Version 8.3
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Flashcard;
use App\Services\FlashcardPracticeService;
use App\Enums\FlashcardPracticeStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\TestHelperTrait;

/**
 * FlashcardPracticeTest class
 *
 * This class contains test cases related to flashcard practice
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
class FlashcardPracticeTest extends TestCase
{
    use RefreshDatabase, TestHelperTrait;

    /**
     * Test case for flashcard practice data
     *
     * @return void
     */
    public function test_get_flashcards_practice(): void
    {
        $userId = 1;

        $this->addFlashcardsWithPractice(3, FlashcardPracticeStatus::CORRECT->value, $userId);

        $flashcardPracticeService = new FlashcardPracticeService();
        $flashcards = $flashcardPracticeService->getFlashcardsPractice($userId);

        $this->assertCount(3, $flashcards);
    }

    /**
     * Test case for saving flashcard practice
     *
     * @return void
     */
    public function test_save_flashcard_practice(): void
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

    /**
     * Test case for reset practice practice data
     *
     * @return void
     */
    public function test_reset_practice(): void
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

    /**
     * Test case for practice data stats
     *
     * @return void
     */
    public function test_stats()
    {
        $userId = 1;

        $this->addFlashcardsWithPractice(4, FlashcardPracticeStatus::CORRECT->value, $userId);
        $this->addFlashcardsWithPractice(4, FlashcardPracticeStatus::INCORRECT->value, $userId);

        //not answered
        Flashcard::factory()->count(2)->create();

        $flashcardPracticeService = new FlashcardPracticeService();
        $stats = $flashcardPracticeService->getStats($userId);


        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('attempted', $stats);
        $this->assertArrayHasKey('correct', $stats);

        $this->assertEquals($stats['total'], 10);
        $this->assertEquals($stats['correct'], 4);
        $this->assertEquals($stats['attempted'], 8);
    }
}
