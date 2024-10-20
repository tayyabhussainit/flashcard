<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\FlashcardPracticeStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flashcard_practices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flashcard_id');
            $table->unsignedBigInteger('user_id');
            $table->string('answer');
            $table->enum('status', [FlashcardPracticeStatus::CORRECT->value, FlashcardPracticeStatus::INCORRECT->value]);

            $table->foreign('flashcard_id')->references('id')->on('flashcards');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcard_practices');
    }
};
