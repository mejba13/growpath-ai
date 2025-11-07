<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class TeamController extends Controller
{
    /**
     * Display a listing of team members.
     */
    public function index(Request $request)
    {
        // Check permission
        if (! $request->user()->can('manage-team')) {
            abort(403);
        }

        $query = User::with('roles');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('team.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new team member.
     */
    public function create()
    {
        $this->authorize('manage-team');

        $roles = Role::all();

        return view('team.create', compact('roles'));
    }

    /**
     * Store a newly created team member.
     */
    public function store(Request $request)
    {
        $this->authorize('manage-team');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()
            ->route('team.index')
            ->with('success', 'Team member added successfully.');
    }

    /**
     * Display the specified team member.
     */
    public function show(User $user)
    {
        $this->authorize('manage-team');

        $user->load(['roles', 'prospects', 'clients', 'followUps']);

        // Get activity stats
        $stats = [
            'total_prospects' => $user->prospects()->count(),
            'won_prospects' => $user->prospects()->where('status', 'won')->count(),
            'active_prospects' => $user->prospects()->active()->count(),
            'total_clients' => $user->clients()->count(),
            'total_follow_ups' => $user->followUps()->count(),
            'completed_follow_ups' => $user->followUps()->where('status', 'completed')->count(),
        ];

        return view('team.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified team member.
     */
    public function edit(User $user)
    {
        $this->authorize('manage-team');

        $roles = Role::all();

        return view('team.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified team member.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('manage-team');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update role
        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('team.show', $user)
            ->with('success', 'Team member updated successfully.');
    }

    /**
     * Remove the specified team member.
     */
    public function destroy(User $user)
    {
        $this->authorize('manage-team');

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Reassign prospects, clients, and follow-ups to the current user
        $user->prospects()->update(['user_id' => auth()->id()]);
        $user->clients()->update(['user_id' => auth()->id()]);
        $user->followUps()->update(['user_id' => auth()->id()]);

        $user->delete();

        return redirect()
            ->route('team.index')
            ->with('success', 'Team member removed successfully. Their data has been reassigned to you.');
    }

    /**
     * Update team member's password.
     */
    public function updatePassword(Request $request, User $user)
    {
        $this->authorize('manage-team');

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Approve a pending user.
     */
    public function approve(User $user)
    {
        // Only admins can approve users
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($user->is_approved) {
            return back()->with('error', 'User is already approved.');
        }

        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // TODO: Send approval email notification to the user

        return back()->with('success', "User {$user->name} has been approved successfully.");
    }

    /**
     * Reject and delete a pending user.
     */
    public function reject(User $user)
    {
        // Only admins can reject users
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($user->is_approved) {
            return back()->with('error', 'Cannot reject an approved user. Please deactivate them instead.');
        }

        $userName = $user->name;
        $user->delete();

        // TODO: Send rejection email notification to the user

        return redirect()
            ->route('team.index')
            ->with('success', "Registration request from {$userName} has been rejected.");
    }
}
