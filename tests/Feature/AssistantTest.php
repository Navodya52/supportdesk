<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssistantTest extends TestCase
{
    use RefreshDatabase;

    // -----------------------------------------------------------------------
    // Authentication boundary
    // -----------------------------------------------------------------------

    public function test_guest_cannot_access_assistant_chat(): void
    {
        $response = $this->postJson(route('assistant.chat'), ['message' => 'VPN not working']);

        $response->assertStatus(401);
    }

    // -----------------------------------------------------------------------
    // Authenticated access
    // -----------------------------------------------------------------------

    public function test_authenticated_employee_can_use_assistant(): void
    {
        $employee = User::factory()->employee()->create();

        $response = $this->actingAs($employee)->postJson(route('assistant.chat'), [
            'message' => 'VPN not working',
        ]);

        $response->assertOk()->assertJsonStructure(['matched', 'topic', 'message']);
    }

    public function test_authenticated_agent_can_use_assistant(): void
    {
        $agent = User::factory()->agent()->create();

        $response = $this->actingAs($agent)->postJson(route('assistant.chat'), [
            'message' => 'network issue',
        ]);

        $response->assertOk()->assertJsonStructure(['matched', 'topic', 'message']);
    }

    public function test_authenticated_admin_can_use_assistant(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->postJson(route('assistant.chat'), [
            'message' => 'password reset',
        ]);

        $response->assertOk()->assertJsonStructure(['matched', 'topic', 'message']);
    }

    // -----------------------------------------------------------------------
    // Knowledge-base matching
    // -----------------------------------------------------------------------

    public function test_vpn_keyword_returns_matched_response(): void
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), [
            'message' => 'my vpn is not connecting',
        ]);

        $response->assertOk()
            ->assertJson(['matched' => true])
            ->assertJsonPath('topic', 'VPN Issues');
    }

    public function test_password_keyword_returns_matched_response(): void
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), [
            'message' => 'I forgot my password',
        ]);

        $response->assertOk()
            ->assertJson(['matched' => true])
            ->assertJsonPath('topic', 'Password Reset');
    }

    public function test_network_keyword_returns_matched_response(): void
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), [
            'message' => 'the network is down and I have no internet',
        ]);

        $response->assertOk()
            ->assertJson(['matched' => true])
            ->assertJsonPath('topic', 'Network / Internet');
    }

    public function test_email_keyword_returns_matched_response(): void
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), [
            'message' => 'I cannot send email from outlook',
        ]);

        $response->assertOk()
            ->assertJson(['matched' => true])
            ->assertJsonPath('topic', 'Email Issues');
    }

    public function test_slow_computer_keyword_returns_matched_response(): void
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), [
            'message' => 'my laptop is very slow and keeps freezing',
        ]);

        $response->assertOk()
            ->assertJson(['matched' => true])
            ->assertJsonPath('topic', 'Slow Computer');
    }

    public function test_software_keyword_returns_matched_response(): void
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), [
            'message' => 'I need help with software installation',
        ]);

        $response->assertOk()
            ->assertJson(['matched' => true])
            ->assertJsonPath('topic', 'Software Installation');
    }

    // -----------------------------------------------------------------------
    // Fallback
    // -----------------------------------------------------------------------

    public function test_unknown_query_returns_fallback_response(): void
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), [
            'message' => 'banana hammock oscillation frequency',
        ]);

        $response->assertOk()
            ->assertJson(['matched' => false])
            ->assertJsonPath('topic', null);

        $this->assertStringContainsString('support ticket', strtolower($response->json('message')));
    }

    // -----------------------------------------------------------------------
    // Ticket escalation link
    // -----------------------------------------------------------------------

    public function test_ticket_route_is_accessible_to_authenticated_user(): void
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)->get(route('tickets.create'));

        $response->assertOk();
    }

    // -----------------------------------------------------------------------
    // Validation
    // -----------------------------------------------------------------------

    public function test_message_field_is_required(): void
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), []);

        $response->assertStatus(422)->assertJsonValidationErrors(['message']);
    }

    public function test_message_cannot_exceed_500_characters(): void
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), [
            'message' => str_repeat('a', 501),
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['message']);
    }
}
