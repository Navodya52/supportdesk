<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of users with search and role filter.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $query = User::withCount(['tickets', 'assignedTickets', 'comments']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Show the user details and edit role/status form.
     */
    public function edit(User $user): View
    {
        Gate::authorize('view', $user);

        $user->loadCount(['tickets', 'assignedTickets', 'comments']);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the user role and active status.
     */
    public function update(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('updateRole', $user);

        $user->update($request->validated());

        return redirect()->route('users.index')
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Delete the specified user if safe.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        if ($user->tickets()->exists() || $user->assignedTickets()->exists()) {
            return redirect()->route('users.index')
                ->with('error', "Cannot delete user '{$user->name}' because they have associated tickets. You can deactivate their account instead.");
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User '{$name}' deleted successfully.");
    }
}
