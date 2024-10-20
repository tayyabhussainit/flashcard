<?php

/**
 * FlashcardInteractive class file
 *
 * PHP Version 8.3
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlashcardService;
use App\Services\FlashcardPracticeService;
use App\Services\MenuService;
use App\Enums\FlashcardPracticeStatus;
use App\Enums\MenuItem;

/**
 * FlashcardInteractive class
 *
 * This class is to manage flashcard command operations with user interaction
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
class FlashcardInteractive extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:interactive {user_id}';

    /**
     * The console command description.
     *
     * @var string  
     */
    protected $description = 'Flashcard interactive command';

    /**
     * Command argument userId 
     * 
     * @var integer
     */
    private int $_userId;

    /**
     * Constructor
     * 
     * @param FlashcardService $flashcardService         FlashcardService injected
     * @param FlashcardService $flashcardPracticeService FlashcardPracticeService injected
     * @param FlashcardService $menuService              MenuService injected
     */
    public function __construct(
        private FlashcardService $flashcardService,
        private FlashcardPracticeService $flashcardPracticeService,
        private MenuService $menuService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * 
     * @return void
     */
    public function handle(): void
    {
        $argUserId = $this->argument('user_id');
        if (!is_numeric($argUserId) || intval($argUserId) != $argUserId) {
            $this->error('user_id must be an integer');
            return;
        }
        $this->_userId = $this->argument('user_id');
        $this->_openMenu();
    }

    /**
     * Open menu for interactive command
     * 
     * @return void
     */
    private function _openMenu(): void
    {
        while (true) {
            $choice = $this->choice(
                'Select Menu',
                $this->menuService->getMenu()
            );

            switch ($choice) {
            case MenuItem::CREATE_FLASHCARD->value:
                $this->_createFlashcard();
                break;
            case MenuItem::LIST_FLASHCARD->value:
                $this->_listFlashcards();
                break;
            case MenuItem::PRACTICE->value:
                $this->_practice();
                break;
            case MenuItem::STATS->value:
                $this->_stats();
                break;
            case MenuItem::RESET->value:
                $this->_reset();
                break;
            case MenuItem::EXIT->value:
                $this->_exit();
                return;
            default:
                $this->_exit();
                return;
            }
        }
    }

    /**
     * Menu option to create flashcard
     * 
     * @return void
     */
    private function _createFlashcard(): void
    {
        $question = $this->ask('Enter the question');
        $answer = $this->ask('Enter the answer');

        $status = $this->flashcardService->createFlashcard($question, $answer);

        if ($status['success']) {
            $this->info('Flashcard created successfully.');
        } else {
            $this->error($status['message']);
        }
    }

    /**
     * Menu option to list flashcards
     * 
     * @return void
     */
    private function _listFlashcards(): void
    {
        $flashcards = $this->flashcardService->getFlashcards();
        if ($flashcards->count() === 0) {
            $this->error('No Flashcard');
            return;
        }
        $this->table(['ID', 'Question', 'Answer'], $flashcards->toArray());
    }

    /**
     * Menu option to practice flashcards
     * 
     * @return void
     */
    private function _practice(): void
    {
        $userId = $this->_userId;

        $flashcards = $this->flashcardPracticeService->getFlashcardsPractice($userId);

        if ($flashcards->count() === 0) {
            $this->error('No Flashcard available for practice');
            return;
        }

        $this->table(
            ['ID', 'Question', 'Answer', 'Status'],
            $this->flashcardPracticeService->preparePracticeTableData($flashcards)
        );

        $correctPercentage = $this->flashcardPracticeService->footerProgress($flashcards);
        $this->info("Correct percentage: $correctPercentage");

        $flashcardId = $this->ask('Please enter the Flashcard ID to answer');

        $flashcard = $flashcards->firstWhere('id', $flashcardId);

        if (!$flashcard) {
            $this->error('Invalid flashcard ID');
            return;
        }

        if ($flashcard->status === FlashcardPracticeStatus::CORRECT->value) {
            $this->error('This question has already been answered correctly');
            return;
        }

        $userAnswer = $this->ask('Please enter your answer');
        if (empty($userAnswer)) {
            $this->error('Answer cannot be empty.');
            return;
        }

        $status = ($userAnswer == $flashcard->answer) ? FlashcardPracticeStatus::CORRECT->value : FlashcardPracticeStatus::INCORRECT->value;

        $this->flashcardPracticeService->saveFlashcarPractice($flashcardId, $userAnswer, $userId, $status);

        $this->info("Your answer is: $status");
    }

    /**
     * Menu option to reset flashcards practice
     * 
     * @return void
     */
    private function _reset(): void
    {
        $userId = $this->_userId;
        $this->flashcardPracticeService->resetPractice($userId);
        $this->info('Reset Done');
    }

    /**
     * Menu option to show practice stats
     * 
     * @return void
     */
    private function _stats(): void
    {
        $userId = $this->_userId;
        $stats = $this->flashcardPracticeService->getStats($userId);

        $this->info('Total Flashcards: ' . $stats['total']);
        $this->info('Attempted Flashcards: ' . $stats['attempted'] . ' (' . $stats['attempted_percentage'] . '%)');
        $this->info('Correct Answers: ' . $stats['correct'] . ' (' . $stats['correct_percentage'] . '%)');
    }

    /**
     * Menu option to exit the command
     * 
     * @return void
     */
    private function _exit(): void
    {
        $this->info('Exiting');
    }
}
