<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_categories_list(): void
    {
        $admin = User::factory()->admin()->create();
        Category::factory()->create(['name' => 'Hardware Issues']);

        $response = $this->actingAs($admin)->get(route('categories.index'));

        $response->assertOk();
        $response->assertSee('Hardware Issues');
    }

    public function test_employee_cannot_access_categories_management(): void
    {
        $employee = User::factory()->employee()->create();

        $response = $this->actingAs($employee)->get(route('categories.index'));

        $response->assertForbidden();
    }

    public function test_agent_cannot_access_categories_management(): void
    {
        $agent = User::factory()->agent()->create();

        $response = $this->actingAs($agent)->get(route('categories.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Network & VPN',
            'description' => 'Issues related to network and VPN connectivity',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Network & VPN',
        ]);
    }

    public function test_category_creation_requires_unique_name(): void
    {
        $admin = User::factory()->admin()->create();
        Category::factory()->create(['name' => 'Email']);

        $response = $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Email',
            'description' => 'Duplicate category name',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['name' => 'Old Category']);

        $response = $this->actingAs($admin)->put(route('categories.update', $category), [
            'name' => 'Updated Category',
            'description' => 'Updated description',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
        ]);
    }

    public function test_admin_can_delete_unused_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_admin_cannot_delete_category_with_tickets(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        Ticket::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($admin)->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }
}
