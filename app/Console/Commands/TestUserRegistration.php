<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:test-user-registration')]
#[Description('Command description')]
class TestUserRegistration extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // TODO: Implement the logic to test user registration
        $this->info('Testing user registration...');
        
        // Simulate user registration logic here
        $user = User::factory()->create();
        $this->info('User registered successfully: ' . $user->email);

        // send a welcome email
        event(new \App\Events\UserRegistered($user));
        $this->info('Welcome email sent to: ' . $user->email);
    }
}
