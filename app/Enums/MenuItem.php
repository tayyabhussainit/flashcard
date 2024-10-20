<?php

namespace App\Enums;

enum MenuItem: string
{
    case CREATE_FLASHCARD = 'Create Flashcard';
    case LIST_FLASHCARD = 'List Flashcards';
    case PRACTICE = 'Practice';
    case STATS = 'Stats';
    case RESET = 'Reset';
    case EXIT = 'Exit';
}