<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable(); 
            $table->timestamps();
        });

     
        DB::table('roles')->insert([
            ['name' => 'student', 'description' => 'Studenti var mācīties un risināt uzdevumus', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin', 'description' => 'Administrators var pārvaldīt sistēmu', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
