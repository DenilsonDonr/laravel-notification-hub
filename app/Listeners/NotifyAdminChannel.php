<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

#[Queue('admin')]
class NotifyAdminChannel implements ShouldQueue
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
        // Simulate sending a notification to the admin channel
        Log::channel('admin')->info(
            'New user registered: ', [
                'user_id' => $event->user->id,
                'name' => $event->user->name,
                'email' => $event->user->email,
            ]
        );
    }
}
