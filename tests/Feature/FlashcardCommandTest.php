<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\MenuService;
use App\Services\FlashcardService;
use App\Models\Flashcard;
use App\Models\FlashcardPractice;
use App\Enums\FlashcardPracticeStatus;

class FlashcardCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_flashcard_creation_command()
    {
        $menuService = new MenuService();
        $options = $menuService->getMenu();

        $this->artisan('flashcard:interactive 1')
             ->expectsQuestion('Select Menu', MenuService::OPTION_CREATE_FLASHCARD)
             ->expectsQuestion('Enter the question', '2+2')
             ->expectsQuestion('Enter the answer', '4')
            ->expectsOutput('Flashcard created successfully.')
             ->expectsQuestion('Select Menu', MenuService::OPTION_EXIT)
            ->assertExitCode(0);
    }

    public function test_list_flashcards_command()
    {
        Flashcard::factory()->count(3)->create();
        
        $flashcardService = new FlashcardService;
        $flashcards = $flashcardService->getFlashcards();
        $this->artisan('flashcard:interactive 1')
            ->expectsQuestion('Select Menu', MenuService::OPTION_LIST_FLASHCARD)
            ->expectsTable(
                ['ID', 'Question', 'Answer'],
                $flashcards->toArray()
            )
            ->expectsQuestion('Select Menu', MenuService::OPTION_EXIT)
            ->assertExitCode(0);
    }

    public function test_practice_flashcards_command()
    {
        $userId = 1;
        $flashcard = Flashcard::factory()->create(['question' => '2+2', 'answer' => '4']);
        
        $this->artisan('flashcard:interactive ' . $userId)
            ->expectsQuestion('Select Menu', MenuService::OPTION_PRACTICE)
            ->expectsOutput('Correct percentage: 0')
            ->expectsQuestion('Please enter the Flashcard ID to answer', $flashcard->id)
            ->expectsQuestion('Please enter your answer', $flashcard->answer)
            ->expectsOutput('Your answer is: correct')
            ->expectsQuestion('Select Menu', MenuService::OPTION_EXIT)
            ->assertExitCode(0);
    }

    public function test_reset_flashcard_practice_command()
    {
        $userId = 1;
        $flashcard = Flashcard::factory()->create();
        FlashcardPractice::factory()->create(
            [
                'flashcard_id'  => $flashcard->id,
                'answer'        => $flashcard->answer,
                'user_id'       => $userId,
                'status'        => FlashcardPracticeStatus::CORRECT->value
            ]
        );

        $this->artisan('flashcard:interactive ' . $userId)
            ->expectsQuestion('Select Menu', 'Reset')
            ->expectsOutput('Reset Done')
            ->expectsQuestion('Select Menu', MenuService::OPTION_EXIT)
            ->assertExitCode(0);
        
        $this->assertDatabaseMissing('flashcard_practices', ['user_id' => $userId]);
    }

}
