<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlashcardService;
use App\Services\FlashcardPracticeService;
use App\Services\MenuService;

use App\Enums\FlashcardPracticeStatus;

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
    protected $description = 'Command description';

    private $_userId;

    public function __construct(
        private FlashcardService $flashcardService,
        private FlashcardPracticeService $flashcardPracticeService,
        private MenuService $menuService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->_user_id = $this->argument('user_id');
        $this->_openMenu();
    }

    private function _openMenu()
    {
        while (true) {
            $choice = $this->choice(
                'Select Menu',
                $this->menuService->getMenu()
            );

            switch ($choice) {
                case MenuService::OPTION_CREATE_FLASHCARD:
                    $this->_createFlashcard();
                    break;
                case MenuService::OPTION_LIST_FLASHCARD:
                    $this->_listFlashcards();
                    break;
                case MenuService::OPTION_PRACTICE:
                    $this->_practice();
                    break;
                case MenuService::OPTION_STATS:
                    $this->_stats();
                    break;
                case MenuService::OPTION_RESET:
                    $this->reset();
                    break;
                case MenuService::OPTION_EXIT:
                    $this->_exit();
                    return;
                default:
                    $this->_exit();
                    return;
            }
        }
    }

    private function _createFlashcard()
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

    private function _listFlashcards()
    {
        $flashcards = $this->flashcardService->getFlashcards();
        if ($flashcards->count() === 0) {
            $this->error('No Flashcard');
            return;
        }
        $this->table(['ID', 'Question', 'Answer'], $flashcards->toArray());
    }

    private function _practice()
    {
        $userId = $this->_user_id;

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

        $flashcard_id = $this->ask('Please enter the Flashcard ID to answer');

        $flashcard = $flashcards->firstWhere('id', $flashcard_id);

        if (!$flashcard) {
            $this->error('Invalid flashcard ID');
            return;
        }

        if ($flashcard->status === FlashcardPracticeStatus::CORRECT->value) {
            $this->error('This question has already been answered correctly');
            return;
        }

        $user_answer = $this->ask('Please enter your answer');
        if (empty($user_answer)) {
            $this->error('Answer cannot be empty.');
            return;
        }

        $status = ($user_answer == $flashcard->answer) ? FlashcardPracticeStatus::CORRECT->value : FlashcardPracticeStatus::INCORRECT->value;

        $this->flashcardPracticeService->saveFlashcarPractice($flashcard_id, $user_answer, $userId, $status);

        $this->info("Your answer is: $status");
    }

    private function reset()
    {
        $userId = $this->_user_id;
        $this->flashcardPracticeService->resetPractice($userId);
        $this->info('Reset Done');
    }

    private function _stats()
    {
        $userId = $this->_user_id;
        $stats = $this->flashcardPracticeService->getStats($userId);

        $this->info('Total Flashcards: ' . $stats['total']);
        $this->info('Attempted Flashcards: ' . $stats['attempted'] . ' (' . $stats['attempted_percentage'] . '%)');
        $this->info('Correct Answers: ' . $stats['correct'] . ' (' . $stats['correct_percentage'] . '%)');
    }

    private function _exit()
    {
        $this->info('Exiting');
    }
}
