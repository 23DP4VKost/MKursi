<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theory extends Model
{
    use HasFactory;

    protected $fillable = ['math_topic_id', 'title', 'content'];

    public function topic() {
        return $this->belongsTo(MathTopic::class, 'math_topic_id');
    }
}
