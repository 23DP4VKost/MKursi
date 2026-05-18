<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateApiTokens extends Command
{
    protected $signature = 'app:generate-api-tokens';
    protected $description = 'Generate API tokens for users that dont have one';

    public function handle()
    {
        $usersWithoutTokens = User::whereNull('api_token')->get();
        $count = 0;

        foreach ($usersWithoutTokens as $user) {
            $user->update(['api_token' => Str::random(80)]);
            $count++;
        }

        $this->info("Generated API tokens for {$count} users.");
    }
}
