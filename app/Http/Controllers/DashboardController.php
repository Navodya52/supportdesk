<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the role-specific dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isAgent()) {
            return $this->agentDashboard($user);
        }

        return $this->employeeDashboard($user);
    }

    /**
     * Build view data for the administrator dashboard.
     */
    protected function adminDashboard(): View
    {
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', Ticket::STATUS_OPEN)->count();
        $inProgressTickets = Ticket::where('status', Ticket::STATUS_IN_PROGRESS)->count();
        $pendingTickets = Ticket::where('status', Ticket::STATUS_PENDING)->count();
        $resolvedTickets = Ticket::where('status', Ticket::STATUS_RESOLVED)->count();
        $closedTickets = Ticket::where('status', Ticket::STATUS_CLOSED)->count();

        $totalEmployees = User::where('role', User::ROLE_EMPLOYEE)->count();
        $totalAgents = User::where('role', User::ROLE_AGENT)->count();
        $totalCategories = Category::count();

        $priorityBreakdown = [
            'critical' => Ticket::where('priority', Ticket::PRIORITY_CRITICAL)->count(),
            'high' => Ticket::where('priority', Ticket::PRIORITY_HIGH)->count(),
            'medium' => Ticket::where('priority', Ticket::PRIORITY_MEDIUM)->count(),
            'low' => Ticket::where('priority', Ticket::PRIORITY_LOW)->count(),
        ];

        $recentTickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.admin', compact(
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
            'recentTickets'
        ));
    }

    /**
     * Build view data for the support agent dashboard.
     */
    protected function agentDashboard(User $agent): View
    {
        $assignedTickets = Ticket::where('assigned_to', $agent->id);

        $totalAssigned = (clone $assignedTickets)->count();
        $openAssigned = (clone $assignedTickets)->where('status', Ticket::STATUS_OPEN)->count();
        $inProgressAssigned = (clone $assignedTickets)->where('status', Ticket::STATUS_IN_PROGRESS)->count();
        $pendingAssigned = (clone $assignedTickets)->where('status', Ticket::STATUS_PENDING)->count();
        $resolvedAssigned = (clone $assignedTickets)->where('status', Ticket::STATUS_RESOLVED)->count();

        $unassignedOpen = Ticket::whereNull('assigned_to')
            ->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_PENDING])
            ->count();

        $recentAssignedTickets = Ticket::with(['user', 'category'])
            ->where('assigned_to', $agent->id)
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.agent', compact(
            'totalAssigned',
            'openAssigned',
            'inProgressAssigned',
            'pendingAssigned',
            'resolvedAssigned',
            'unassignedOpen',
            'recentAssignedTickets'
        ));
    }

    /**
     * Build view data for the employee dashboard.
     */
    protected function employeeDashboard(User $employee): View
    {
        $employeeTickets = Ticket::where('user_id', $employee->id);

        $myTotalTickets = (clone $employeeTickets)->count();
        $myOpenTickets = (clone $employeeTickets)->where('status', Ticket::STATUS_OPEN)->count();
        $myInProgressTickets = (clone $employeeTickets)->where('status', Ticket::STATUS_IN_PROGRESS)->count();
        $myPendingTickets = (clone $employeeTickets)->where('status', Ticket::STATUS_PENDING)->count();
        $myResolvedTickets = (clone $employeeTickets)->where('status', Ticket::STATUS_RESOLVED)->count();
        $myClosedTickets = (clone $employeeTickets)->where('status', Ticket::STATUS_CLOSED)->count();

        $myRecentTickets = Ticket::with(['category', 'assignedAgent'])
            ->where('user_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.employee', compact(
            'myTotalTickets',
            'myOpenTickets',
            'myInProgressTickets',
            'myPendingTickets',
            'myResolvedTickets',
            'myClosedTickets',
            'myRecentTickets'
        ));
    }
}
