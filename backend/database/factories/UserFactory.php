<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $email = fake()->unique()->safeEmail();
        return [
            'email' => $email,
            'username' => Str::before($email, '@'),
            'password' => Hash::make('password'),
            'role' => 'user',
        ];
    }
}