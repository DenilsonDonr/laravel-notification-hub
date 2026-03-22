<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Notifications\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Attributes\Backoff;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Queue\InteractsWithQueue;

#[Queue('notifications')] // This listener will be placed on the 'notifications' queue.
#[Tries(3)] // This listener will be attempted a maximum of 3 times before it fails permanently.
#[Backoff(10)] // If the listener fails, it will wait 10 seconds before trying again.
class SendWelcomeEmail
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
        $event->user->notify(new WelcomeNotification());
    }

    /**
     * Handle the failure of the event.
     */
    public function failed(UserRegistered $event, \Throwable $exception): void
    {
        // Log the failure for monitoring
        logger()->error('Welcome email failed to send for user ID: ' . $event->user->id, [
            'exception' => $exception->getMessage(),
        ]);
    }
}
