<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Queue\InteractsWithQueue;

#[Queue('logs')]
class CreateActivityLog implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        ActivityLog::create([
            'user_id' => $event->user->id,
            'action'  => 'user_registration',
            'metadata' => [
                'email' => 'User registered with email: ' . $event->user->email,
            ],
        ]);
    }
}
