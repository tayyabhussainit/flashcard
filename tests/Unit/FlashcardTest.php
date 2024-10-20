<?php

/**
 * FlashcardTest class file
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
use App\Services\FlashcardService;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * FlashcardTest class
 *
 * This class contains test cases related to flashcard
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
class FlashcardTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test case for creating flashcard
     * 
     * @return void
     */
    public function test_create_flashcard(): void
    {
        $question = 'Test question?';
        $answer = 'Test answer';
        $flashcardService = new FlashcardService();
        $status = $flashcardService->createFlashcard($question, $answer);
        $this->assertArrayHasKey('success', $status);
        $this->assertEquals(true, $status['success']);
        $this->assertDatabaseHas('flashcards', compact('question', 'answer'));
    }

    /**
     * Test case for getting flashcard  
     * 
     * @return void
     */
    public function test_get_flashcards(): void
    {
        Flashcard::factory()->count(5)->create();
        $flashcardService = new FlashcardService();

        $flashcards = $flashcardService->getFlashcards();
        $this->assertCount(5, $flashcards);
    }

}
