<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_list_with_search(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = User::factory()->employee()->create(['name' => 'John Developer']);

        $response = $this->actingAs($admin)->get(route('users.index', ['search' => 'John']));

        $response->assertOk();
        $response->assertSee('John Developer');
    }

    public function test_employee_cannot_access_user_management(): void
    {
        $employee = User::factory()->employee()->create();

        $response = $this->actingAs($employee)->get(route('users.index'));

        $response->assertForbidden();
    }

    public function test_agent_cannot_access_user_management(): void
    {
        $agent = User::factory()->agent()->create();

        $response = $this->actingAs($agent)->get(route('users.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_update_user_role_and_status(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = User::factory()->employee()->create(['role' => User::ROLE_EMPLOYEE, 'is_active' => true]);

        $response = $this->actingAs($admin)->put(route('users.update', $employee), [
            'role' => User::ROLE_AGENT,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'role' => User::ROLE_AGENT,
            'is_active' => true,
        ]);
    }

    public function test_admin_cannot_demote_or_modify_own_role(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->put(route('users.update', $admin), [
            'role' => User::ROLE_EMPLOYEE,
            'is_active' => '1',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_admin_can_delete_user_without_tickets(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $response->assertForbidden();
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_admin_cannot_delete_user_with_assigned_or_created_tickets(): void
    {
        $admin = User::factory()->admin()->create();
        $agent = User::factory()->agent()->create();
        Ticket::factory()->assigned($agent)->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $agent));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $agent->id,
        ]);
    }
}
