<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theories', function (Blueprint $table) {
            $table->id();
            $table->string('subtopic_name', 40);
            $table->longText('content');
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theories');
    }
};
