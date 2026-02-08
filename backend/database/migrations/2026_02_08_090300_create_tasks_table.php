<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->text('question');
            $table->string('correct_answer', 10)->nullable();
            $table->enum('difficulty_level', ['viegls', 'videjs', 'sarezgit'])->nullable();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
