<?php

namespace Tests\Feature;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReminderTest extends TestCase
{
    use RefreshDatabase;


    private User $user;
    private string $token;

    // setup a user and token for authenticated requests this will run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test')->plainTextToken;
    }

    // helper to get auth header
    private function authHeader(): array
    {
        return ['Authorization' => 'Bearer ' . $this->token];
    }

    // For  INDEX testing, we create 3 reminders for the user and check if they are returned
    public function test_user_can_get_their_reminders(): void
    {
        Reminder::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/reminders', $this->authHeader());

        $response->assertStatus(200)->assertJsonCount(3);
    }

    // For store testing, we send a POST request with valid data and check if the reminder is created in the database
    public function test_user_can_create_a_reminder(): void
    {
        $response = $this->postJson('/api/reminders', [
            'title'     => 'Test Reminder',
            'note'      => 'Some note',
            'remind_at' => now()->addHour()->toDateTimeString(),
        ], $this->authHeader());

        $response->assertStatus(201)->assertJsonFragment(['title' => 'Test Reminder']);
        $this->assertDatabaseHas('reminders', ['title' => 'Test Reminder']);
    }

    // For store validation testing, we send a POST request with invalid data and check if the appropriate validation errors are returned
    public function test_reminder_requires_title_and_remind_at(): void
    {
        $response = $this->postJson('/api/reminders', [], $this->authHeader());

        $response->assertStatus(422)->assertJsonValidationErrors(['title', 'remind_at']);
    }

    // For show testing, we create a reminder for the user and check if it can be viewed
    public function test_user_can_view_their_reminder(): void
    {
        $reminder = Reminder::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/reminders/{$reminder->id}", $this->authHeader());

        $response->assertStatus(200)->assertJsonFragment(['title' => $reminder->title]);
    }

    // For show forbidden testing, we create a reminder for another user and check if it cannot be viewed
    public function test_user_cannot_view_other_users_reminder(): void
    {
        $other = User::factory()->create();
        $reminder = Reminder::factory()->create(['user_id' => $other->id]);

        $response = $this->getJson("/api/reminders/{$reminder->id}", $this->authHeader());

        $response->assertStatus(403);
    }

    // For update testing, we create a reminder for the user and check if it can be updated
    public function test_user_can_update_their_reminder(): void
    {
        $reminder = Reminder::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson("/api/reminders/{$reminder->id}", [
            'title' => 'Updated Title',
        ], $this->authHeader());

        $response->assertStatus(200)->assertJsonFragment(['title' => 'Updated Title']);
    }

    // For update forbidden testing, we create a reminder for another user and check if it cannot be updated
    public function test_user_cannot_update_other_users_reminder(): void
    {
        $other = User::factory()->create();
        $reminder = Reminder::factory()->create(['user_id' => $other->id]);

        $response = $this->putJson("/api/reminders/{$reminder->id}", [
            'title' => 'Hacked',
        ], $this->authHeader());

        $response->assertStatus(403);
    }

    // For delete testing, we create a reminder for the user and check if it can be deleted
    public function test_user_can_delete_their_reminder(): void
    {
        $reminder = Reminder::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/reminders/{$reminder->id}", [], $this->authHeader());

        $response->assertStatus(200);
        $this->assertDatabaseMissing('reminders', ['id' => $reminder->id]);
    }

    // For delete forbidden testing, we create a reminder for another user and check if it cannot be deleted
    public function test_user_cannot_delete_other_users_reminder(): void
    {
        $other = User::factory()->create();
        $reminder = Reminder::factory()->create(['user_id' => $other->id]);

        $response = $this->deleteJson("/api/reminders/{$reminder->id}", [], $this->authHeader());

        $response->assertStatus(403);
    }

    // For unauthenticated testing, we check if an unauthenticated user can access the reminders endpoint
    public function test_unauthenticated_user_cannot_access_reminders(): void
    {
        $response = $this->getJson('/api/reminders');

        $response->assertStatus(401);
    }
}
