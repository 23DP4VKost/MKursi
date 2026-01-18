<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('math_topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('class_level'); // 11 или 12 класс
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('math_topics');
    }
};
