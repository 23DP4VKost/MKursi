<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nickname', 'email', 'password', 'role_id'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function results() {
        return $this->hasMany(Result::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }
}
