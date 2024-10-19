<?php

namespace App\Services;

class MenuService
{
    public const OPTION_CREATE_FLASHCARD = 'Create Flashcard';
    public const OPTION_LIST_FLASHCARD = 'List Flashcards';
    public const OPTION_PRACTICE = 'Practice';
    public const OPTION_STATS = 'Stats';
    public const OPTION_RESET = 'Reset';
    public const OPTION_EXIT = 'Exit';

    public function getMenu()
    {
        return [
            self::OPTION_CREATE_FLASHCARD,
            self::OPTION_LIST_FLASHCARD,
            self::OPTION_PRACTICE,
            self::OPTION_STATS,
            self::OPTION_RESET,
            self::OPTION_EXIT,
        ];
    }
}
