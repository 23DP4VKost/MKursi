<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['math_topic_id', 'title', 'question', 'answer'];

    public function topic() {
        return $this->belongsTo(MathTopic::class, 'math_topic_id');
    }

    public function results() {
        return $this->hasMany(Result::class);
    }
}
