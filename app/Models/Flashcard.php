<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FlashcardPractice;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'answer'];

    public function practices()
    {
        return $this->hasMany(FlashcardPractice::class);
    }
}
