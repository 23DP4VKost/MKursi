<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MathPart extends Model
{
    use HasFactory;

    protected $fillable = ['topic_id', 'title', 'content'];

    public function topic() {
        return $this->belongsTo(MathTopic::class, 'topic_id');
    }
}

