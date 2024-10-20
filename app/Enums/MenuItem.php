<?php

/**
 * MenuItem enum file
 *
 * PHP Version 8.3
 *
 * @category Enum
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
namespace App\Enums;

/**
 * MenuItem enum
 *
 * The enum is to keep menu items
 *
 * @category ENUM
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
enum MenuItem: string
{
    case CREATE_FLASHCARD = 'Create Flashcard';
    case LIST_FLASHCARD = 'List Flashcards';
    case PRACTICE = 'Practice';
    case STATS = 'Stats';
    case RESET = 'Reset';
    case EXIT = 'Exit';
}
