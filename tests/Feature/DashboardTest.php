<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_accesses_admin_dashboard_with_stats(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        Ticket::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewIs('dashboard.admin');
        $response->assertViewHasAll([
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'pendingTickets',
            'resolvedTickets',
            'closedTickets',
            'totalEmployees',
            'totalAgents',
            'totalCategories',
            'priorityBreakdown',
            'recentTickets',
        ]);
        $response->assertSee('Admin Dashboard');
    }

    public function test_agent_accesses_agent_dashboard(): void
    {
        $agent = User::factory()->agent()->create();
        $category = Category::factory()->create();
        Ticket::factory()->assigned($agent)->create(['category_id' => $category->id]);

        $response = $this->actingAs($agent)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewIs('dashboard.agent');
        $response->assertViewHasAll([
            'totalAssigned',
            'openAssigned',
            'inProgressAssigned',
            'pendingAssigned',
            'resolvedAssigned',
            'unassignedOpen',
            'recentAssignedTickets',
        ]);
        $response->assertSee('Agent Dashboard');
    }

    public function test_employee_accesses_employee_dashboard(): void
    {
        $employee = User::factory()->employee()->create();
        $category = Category::factory()->create();
        Ticket::factory()->create([
            'user_id' => $employee->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($employee)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewIs('dashboard.employee');
        $response->assertViewHasAll([
            'myTotalTickets',
            'myOpenTickets',
            'myInProgressTickets',
            'myPendingTickets',
            'myResolvedTickets',
            'myClosedTickets',
            'myRecentTickets',
        ]);
        $response->assertSee('My Dashboard');
    }
}
