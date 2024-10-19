<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;  ERROR
use Tests\TestCase;
use App\Models\Flashcard;
use App\Services\FlashcardService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlashcardTest extends TestCase
{

    use RefreshDatabase;

    public function test_create_flashcard()
    {
        $question = 'Test question?';
        $answer = 'Test answer';
        $flashcardService = new FlashcardService();
        $status = $flashcardService->createFlashcard($question, $answer);
        $this->assertArrayHasKey('success', $status);
        $this->assertEquals(true, $status['success']);
        $this->assertDatabaseHas('flashcards', compact('question', 'answer'));
    }

    public function test_get_flashcards()
    {
        Flashcard::factory()->count(5)->create();
        $flashcardService = new FlashcardService();

        $flashcards = $flashcardService->getFlashcards();
        $this->assertCount(5, $flashcards);
    }

}
