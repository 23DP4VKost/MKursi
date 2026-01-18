<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MathTopic extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'class_level'];

    public function theories() {
        return $this->hasMany(Theory::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function users() {
        return $this->belongsToMany(User::class)->withPivot('progress')->withTimestamps();
    }
}
