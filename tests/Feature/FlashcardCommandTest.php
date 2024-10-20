<?php

/**
 * FlashcardCommandTest class file
 *
 * PHP Version 8.3
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\MenuService;
use App\Services\FlashcardService;
use App\Models\Flashcard;
use App\Models\FlashcardPractice;
use App\Enums\FlashcardPracticeStatus;
use App\Enums\MenuItem;

/**
 * FlashcardCommandTest class
 *
 * This class contains test cases related to flashcard command
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
class FlashcardCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test case for flashcard creation via command
     * @return void
     */
    public function test_flashcard_creation_command(): void
    {
        $menuService = new MenuService();
        $options = $menuService->getMenu();

        $this->artisan('flashcard:interactive 1')
            ->expectsQuestion('Select Menu', MenuItem::CREATE_FLASHCARD->value)
            ->expectsQuestion('Enter the question', '2+2')
            ->expectsQuestion('Enter the answer', '4')
            ->expectsOutput('Flashcard created successfully.')
            ->expectsQuestion('Select Menu', MenuItem::EXIT->value)
            ->assertExitCode(0);
    }

    /**
     * Test case for list flashcards via command
     * @return void
     */
    public function test_list_flashcards_command(): void
    {
        Flashcard::factory()->count(3)->create();

        $flashcardService = new FlashcardService;
        $flashcards = $flashcardService->getFlashcards();
        $this->artisan('flashcard:interactive 1')
            ->expectsQuestion('Select Menu', MenuItem::LIST_FLASHCARD->value)
            ->expectsTable(
                ['ID', 'Question', 'Answer'],
                $flashcards->toArray()
            )
            ->expectsQuestion('Select Menu', MenuItem::EXIT->value)
            ->assertExitCode(0);
    }

    /**
     * Test case for practice flashcards
     * @return void
     */
    public function test_practice_flashcards_command(): void
    {
        $userId = 1;
        $flashcard = Flashcard::factory()->create(['question' => '2+2', 'answer' => '4']);

        $this->artisan('flashcard:interactive ' . $userId)
            ->expectsQuestion('Select Menu', MenuItem::PRACTICE->value)
            ->expectsOutput('Correct percentage: 0')
            ->expectsQuestion('Please enter the Flashcard ID to answer', $flashcard->id)
            ->expectsQuestion('Please enter your answer', $flashcard->answer)
            ->expectsOutput('Your answer is: correct')
            ->expectsQuestion('Select Menu', MenuItem::EXIT->value)
            ->assertExitCode(0);
    }

    /**
     * Test case to reset flashcards practice data
     * @return void
     */
    public function test_reset_flashcard_practice_command(): void
    {
        $userId = 1;
        $flashcard = Flashcard::factory()->create();
        FlashcardPractice::factory()->create(
            [
                'flashcard_id' => $flashcard->id,
                'answer' => $flashcard->answer,
                'user_id' => $userId,
                'status' => FlashcardPracticeStatus::CORRECT->value
            ]
        );

        $this->artisan('flashcard:interactive ' . $userId)
            ->expectsQuestion('Select Menu', 'Reset')
            ->expectsOutput('Reset Done')
            ->expectsQuestion('Select Menu', MenuItem::EXIT->value)
            ->assertExitCode(0);

        $this->assertDatabaseMissing('flashcard_practices', ['user_id' => $userId]);
    }
}
