<?php

namespace Tests\Feature;

use App\Events\UserRegistered;
use App\Listeners\CreateActivityLog;
use App\Listeners\NotifyAdminChannel;
use App\Listeners\SendWelcomeEmail;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationFlowTest extends TestCase
{
    use RefreshDatabase; // This trait will refresh the database for each test, ensuring a clean state.

    /**
     * Test that registering a user dispatches the UserRegistered event
     */
    public function test_registration_dispatches_event(): void
    {
        Event::fake([UserRegistered::class]);

        $this->postJson('/api/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertCreated();

        Event::assertDispatched(UserRegistered::class);
    }

    /**
     * Test that the UserRegistered event has the correct listeners attached
     */
    public function test_event_has_correct_listeners(): void
    {
        Event::fake([UserRegistered::class]);

        Event::assertListening(
            UserRegistered::class,
            SendWelcomeEmail::class
        );

        Event::assertListening(
            UserRegistered::class,
            CreateActivityLog::class
        );

        Event::assertListening(
            UserRegistered::class,
            NotifyAdminChannel::class
        );
    }

    /**
     * Test that activity log is created when a user registers
     */
    public function test_activity_log_created_on_registration(): void
    {
        $user = User::factory()->create();
        $event = new UserRegistered($user);

        $listener = new CreateActivityLog();
        $listener->handle($event);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $event->user->id,
            'action'  => 'user_registration',
            'metadata' => json_encode([
                'email' => 'User registered with email: ' . $event->user->email,
            ]) 
        ]); 
    }

    /**
     * Test that a welcome email is sent when a user registers
     */
    public function test_welcome_email_sent_on_registration(): void
    {
        Notification::fake(WelcomeNotification::class);

        $user = User::factory()->create();
        $event = new UserRegistered($user);

        $listener = new SendWelcomeEmail();
        $listener->handle($event);

        Notification::assertSentTo($user, WelcomeNotification::class);
    }

    /**
     * Test that user registration returns the correct response structure
     */
    public function test_registration_response_structure(): void
    {
        Event::fake([UserRegistered::class]);

         $this->postJson('/api/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertCreated()
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);
    }

    /**
     * Test that registration validates input and does not dispatch event on validation failure
     */
    public function test_registration_validates_input(): void
    {
        Event::fake();

        $this->postJson('/api/register', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(
                ['name', 'email', 'password']
            );

        Event::assertNotDispatched(UserRegistered::class);
    }

}
