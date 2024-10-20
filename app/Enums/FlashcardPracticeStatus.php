<?php

/**
 * FlashcardPracticeStatus enum file
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
 * FlashcardPracticeStatus enum
 *
 * The enum is to keep status for flashcard practice
 *
 * @category ENUM
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
enum FlashcardPracticeStatus: string
{

    case CORRECT = 'correct';
    case INCORRECT = 'incorrect';
    case NOT_ANSWERED = 'not answered';

}
