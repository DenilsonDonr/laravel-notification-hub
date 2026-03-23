<?php

namespace Tests\Feature;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegisteredEventTest extends TestCase
{
    use RefreshDatabase; // This trait will refresh the database for each test, ensuring a clean state.

    /**
     * Test that the UserRegistered event correctly contains the user information.
     */
    public function test_event_contains_user()
    {
        $user = User::factory()->create(); // Create a user using the factory.
        $event = new UserRegistered($user); // Create an instance of the UserRegistered event with the user.
    
        $this->assertSame($user->id, $event->user->id);
        $this->assertInstanceOf(User::class, $event->user);
    }

    /**
     * Test that the user property of the UserRegistered event is readonly, ensuring immutability.
     */
    public function test_user_property_is_readonly()
    {
        $user = User::factory()->create();
        $event = new UserRegistered($user);

        $reflection = new \ReflectionClass($event); // Use reflection to inspect the event class.
        $this->assertTrue($reflection->getProperty('user')->isReadOnly());
    }
}
