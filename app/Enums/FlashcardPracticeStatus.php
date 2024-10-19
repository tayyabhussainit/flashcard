<?php

namespace App\Enums;

enum FlashcardPracticeStatus: string
{

    case CORRECT = 'correct';
    case INCORRECT = 'incorrect';
    case NOT_ANSWERED = 'not answered';

}