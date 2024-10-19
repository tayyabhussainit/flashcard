<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlashcardPractice extends Model
{
    use HasFactory;

    protected $fillable = ['flashcard_id', 'answer', 'status', 'user_id'];
}
