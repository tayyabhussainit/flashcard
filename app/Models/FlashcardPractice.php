<?php

/**
 * FlashcardPractice class file
 *
 * PHP Version 8.3
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * FlashcardPractice class 
 *
 * This class is model class for FlashcardPractice entity
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
class FlashcardPractice extends Model
{
    use HasFactory;

    /**
     * Fillable columns
     * @var array
     */
    protected $fillable = ['flashcard_id', 'answer', 'status', 'user_id'];
}
