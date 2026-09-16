<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_create_ticket_and_ticket_number_is_generated(): void
    {
        $employee = User::factory()->employee()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($employee)->post(route('tickets.store'), [
            'title' => 'Cannot connect to company WiFi',
            'description' => 'The WiFi authentication keeps dropping after a few minutes.',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_HIGH,
        ]);

        $ticket = Ticket::first();
        $this->assertNotNull($ticket);
        $this->assertMatchesRegularExpression('/^SD-\d{6}$/', $ticket->ticket_number);
        $this->assertSame('Cannot connect to company WiFi', $ticket->title);
        $this->assertSame($employee->id, $ticket->user_id);
        $this->assertSame(Ticket::STATUS_OPEN, $ticket->status);

        $response->assertRedirect(route('tickets.show', $ticket));
    }

    public function test_employee_can_view_own_ticket(): void
    {
        $employee = User::factory()->employee()->create();
        $category = Category::factory()->create();
        $ticket = Ticket::factory()->create([
            'user_id' => $employee->id,
            'category_id' => $category->id,
            'title' => 'My Specific Ticket',
        ]);

        $response = $this->actingAs($employee)->get(route('tickets.show', $ticket));

        $response->assertOk();
        $response->assertSee('My Specific Ticket');
    }

    public function test_employee_cannot_view_another_employees_ticket(): void
    {
        $employeeA = User::factory()->employee()->create();
        $employeeB = User::factory()->employee()->create();
        $ticket = Ticket::factory()->create(['user_id' => $employeeA->id]);

        $response = $this->actingAs($employeeB)->get(route('tickets.show', $ticket));

        $response->assertForbidden();
    }

    public function test_admin_can_view_any_ticket(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = User::factory()->employee()->create();
        $ticket = Ticket::factory()->create(['user_id' => $employee->id, 'title' => 'Employee Problem']);

        $response = $this->actingAs($admin)->get(route('tickets.show', $ticket));

        $response->assertOk();
        $response->assertSee('Employee Problem');
    }

    public function test_ticket_list_search_and_filters(): void
    {
        $admin = User::factory()->admin()->create();
        $catHardware = Category::factory()->create(['name' => 'Hardware']);
        $catSoftware = Category::factory()->create(['name' => 'Software']);

        $ticket1 = Ticket::factory()->create([
            'category_id' => $catHardware->id,
            'title' => 'Keyboard broken',
            'status' => Ticket::STATUS_OPEN,
            'priority' => Ticket::PRIORITY_LOW,
        ]);

        $ticket2 = Ticket::factory()->create([
            'category_id' => $catSoftware->id,
            'title' => 'Photoshop license expired',
            'status' => Ticket::STATUS_RESOLVED,
            'priority' => Ticket::PRIORITY_HIGH,
        ]);

        // Search by title
        $response = $this->actingAs($admin)->get(route('tickets.index', ['search' => 'Keyboard']));
        $response->assertOk();
        $response->assertSee('Keyboard broken');
        $response->assertDontSee('Photoshop license expired');

        // Filter by status
        $response = $this->actingAs($admin)->get(route('tickets.index', ['status' => Ticket::STATUS_RESOLVED]));
        $response->assertOk();
        $response->assertSee('Photoshop license expired');
        $response->assertDontSee('Keyboard broken');

        // Filter by category
        $response = $this->actingAs($admin)->get(route('tickets.index', ['category_id' => $catHardware->id]));
        $response->assertOk();
        $response->assertSee('Keyboard broken');
        $response->assertDontSee('Photoshop license expired');
    }

    public function test_admin_can_assign_ticket_to_agent(): void
    {
        $admin = User::factory()->admin()->create();
        $agent = User::factory()->agent()->create();
        $ticket = Ticket::factory()->create(['assigned_to' => null, 'status' => Ticket::STATUS_OPEN]);

        $response = $this->actingAs($admin)->post(route('tickets.assign', $ticket), [
            'assigned_to' => $agent->id,
        ]);

        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'assigned_to' => $agent->id,
            'status' => Ticket::STATUS_IN_PROGRESS,
        ]);
    }

    public function test_employee_cannot_assign_ticket(): void
    {
        $employee = User::factory()->employee()->create();
        $agent = User::factory()->agent()->create();
        $ticket = Ticket::factory()->create(['user_id' => $employee->id]);

        $response = $this->actingAs($employee)->post(route('tickets.assign', $ticket), [
            'assigned_to' => $agent->id,
        ]);

        $response->assertForbidden();
    }

    public function test_agent_can_update_ticket_status_and_resolution(): void
    {
        $agent = User::factory()->agent()->create();
        $category = Category::factory()->create();
        $ticket = Ticket::factory()->assigned($agent)->create([
            'category_id' => $category->id,
            'status' => Ticket::STATUS_IN_PROGRESS,
        ]);

        $response = $this->actingAs($agent)->patch(route('tickets.update', $ticket), [
            'status' => Ticket::STATUS_RESOLVED,
            'priority' => Ticket::PRIORITY_HIGH,
            'category_id' => $category->id,
            'title' => $ticket->title,
            'description' => $ticket->description,
            'resolution' => 'Replaced faulty ethernet cable.',
        ]);

        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => Ticket::STATUS_RESOLVED,
            'resolution' => 'Replaced faulty ethernet cable.',
        ]);
        $this->assertNotNull($ticket->fresh()->resolved_at);
    }

    public function test_employee_can_only_close_or_reopen_own_ticket(): void
    {
        $employee = User::factory()->employee()->create();
        $ticket = Ticket::factory()->create([
            'user_id' => $employee->id,
            'status' => Ticket::STATUS_OPEN,
        ]);

        $response = $this->actingAs($employee)->patch(route('tickets.update', $ticket), [
            'status' => Ticket::STATUS_CLOSED,
        ]);

        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertSame(Ticket::STATUS_CLOSED, $ticket->fresh()->status);
        $this->assertNotNull($ticket->fresh()->closed_at);
    }

    public function test_employee_can_add_comment_to_own_ticket(): void
    {
        $employee = User::factory()->employee()->create();
        $ticket = Ticket::factory()->create(['user_id' => $employee->id]);

        $response = $this->actingAs($employee)->post(route('tickets.comments.store', $ticket), [
            'comment' => 'Here is additional diagnostic info.',
        ]);

        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertDatabaseHas('comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $employee->id,
            'comment' => 'Here is additional diagnostic info.',
        ]);
    }

    public function test_unauthorized_user_cannot_comment_on_ticket(): void
    {
        $employeeA = User::factory()->employee()->create();
        $employeeB = User::factory()->employee()->create();
        $ticket = Ticket::factory()->create(['user_id' => $employeeA->id]);

        $response = $this->actingAs($employeeB)->post(route('tickets.comments.store', $ticket), [
            'comment' => 'Unauthorized comment',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('comments', [
            'comment' => 'Unauthorized comment',
        ]);
    }

    public function test_admin_can_delete_ticket(): void
    {
        $admin = User::factory()->admin()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($admin)->delete(route('tickets.destroy', $ticket));

        $response->assertRedirect(route('tickets.index'));
        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id,
        ]);
    }

    public function test_non_admin_cannot_delete_ticket(): void
    {
        $agent = User::factory()->agent()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($agent)->delete(route('tickets.destroy', $ticket));

        $response->assertForbidden();
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
        ]);
    }
}
