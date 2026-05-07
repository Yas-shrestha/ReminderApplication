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

    // ✅ INDEX
    public function test_user_can_get_their_reminders(): void
    {
        Reminder::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/reminders', $this->authHeader());

        $response->assertStatus(200)->assertJsonCount(3);
    }

    // ✅ STORE
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

    // ✅ STORE VALIDATION
    public function test_reminder_requires_title_and_remind_at(): void
    {
        $response = $this->postJson('/api/reminders', [], $this->authHeader());

        $response->assertStatus(422)->assertJsonValidationErrors(['title', 'remind_at']);
    }

    // ✅ SHOW
    public function test_user_can_view_their_reminder(): void
    {
        $reminder = Reminder::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/reminders/{$reminder->id}", $this->authHeader());

        $response->assertStatus(200)->assertJsonFragment(['title' => $reminder->title]);
    }

    // ✅ SHOW FORBIDDEN
    public function test_user_cannot_view_other_users_reminder(): void
    {
        $other = User::factory()->create();
        $reminder = Reminder::factory()->create(['user_id' => $other->id]);

        $response = $this->getJson("/api/reminders/{$reminder->id}", $this->authHeader());

        $response->assertStatus(403);
    }

    // ✅ UPDATE
    public function test_user_can_update_their_reminder(): void
    {
        $reminder = Reminder::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson("/api/reminders/{$reminder->id}", [
            'title' => 'Updated Title',
        ], $this->authHeader());

        $response->assertStatus(200)->assertJsonFragment(['title' => 'Updated Title']);
    }

    // ✅ UPDATE FORBIDDEN
    public function test_user_cannot_update_other_users_reminder(): void
    {
        $other = User::factory()->create();
        $reminder = Reminder::factory()->create(['user_id' => $other->id]);

        $response = $this->putJson("/api/reminders/{$reminder->id}", [
            'title' => 'Hacked',
        ], $this->authHeader());

        $response->assertStatus(403);
    }

    // ✅ DELETE
    public function test_user_can_delete_their_reminder(): void
    {
        $reminder = Reminder::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/reminders/{$reminder->id}", [], $this->authHeader());

        $response->assertStatus(200);
        $this->assertDatabaseMissing('reminders', ['id' => $reminder->id]);
    }

    // ✅ DELETE FORBIDDEN
    public function test_user_cannot_delete_other_users_reminder(): void
    {
        $other = User::factory()->create();
        $reminder = Reminder::factory()->create(['user_id' => $other->id]);

        $response = $this->deleteJson("/api/reminders/{$reminder->id}", [], $this->authHeader());

        $response->assertStatus(403);
    }

    // ✅ UNAUTHENTICATED
    public function test_unauthenticated_user_cannot_access_reminders(): void
    {
        $response = $this->getJson('/api/reminders');

        $response->assertStatus(401);
    }
}
