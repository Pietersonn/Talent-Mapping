<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserController extends Controller
{

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $status = $request->status === 'active';
            $query->where('is_active', $status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->withCount(['testSessions', 'picEvents', 'resendRequests'])
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);

        $statistics = [
            'total_users'    => User::count(),
            'active_users'   => User::where('is_active', true)->count(),
            'admins'         => User::where('role', 'admin')->count(),
            'staff'          => User::where('role', 'staff')->count(),
            'pics'           => User::where('role', 'pic')->count(),
            'regular_users'  => User::where('role', 'user')->count(),
        ];

        return view('admin.users.index', compact('users', 'statistics'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'role'      => ['required', 'in:admin,staff,pic,user'],
            'is_active' => ['boolean'],
        ]);

        // Redundant safety (karena sudah di middleware)
        if ($request->role === 'admin' && Auth::user()->role !== 'admin') {
            return back()->withErrors(['role' => 'Only administrators can create admin users.'])->withInput();
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' created successfully.");
    }

    public function show(User $user)
    {
        $user->load([
            'testSessions' => fn ($q) => $q->latest()->take(5),
            'picEvents'    => fn ($q) => $q->latest()->take(5),
            'resendRequests' => fn ($q) => $q->latest()->take(5),
        ]);

        $userStats = [
            'total_test_sessions' => $user->testSessions()->count(),
            'completed_tests'     => $user->testSessions()->where('is_completed', true)->count(),
            'events_as_pic'       => $user->picEvents()->count(),
            'total_resend_requests' => $user->resendRequests()->count(),
            'last_login'          => $user->last_login_at ?? 'Never',
            'account_age'         => $user->created_at?->diffForHumans() ?? '-',
        ];

        return view('admin.users.show', compact('user', 'userStats'));
    }

    public function edit(User $user)
    {
        // Redundant safety
        if ($user->role === 'admin' && Auth::user()->role !== 'admin' && Auth::id() !== $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot edit administrator accounts.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'  => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role'      => ['required', 'in:admin,staff,pic,user'],
            'is_active' => ['boolean'],
        ]);

        if ($user->role === 'admin' && Auth::user()->role !== 'admin' && Auth::id() !== $user->id) {
            return back()->withErrors(['role' => 'You cannot modify administrator accounts.'])->withInput();
        }

        if ($request->role === 'admin' && Auth::user()->role !== 'admin') {
            return back()->withErrors(['role' => 'Only administrators can assign admin role.'])->withInput();
        }

        if (Auth::id() === $user->id && !$request->boolean('is_active', true)) {
            return back()->withErrors(['is_active' => 'You cannot deactivate your own account.'])->withInput();
        }

        $userData = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.show', $user)
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'admin' && Auth::user()->role !== 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Only administrators can delete admin accounts.');
        }

        $hasTestSessions = $user->testSessions()->exists();
        $hasPicEvents    = $user->picEvents()->exists();

        if ($hasTestSessions || $hasPicEvents) {
            return redirect()->route('admin.users.index')
                ->with('error', "Cannot delete user '{$user->name}' because they have associated test sessions or events. Consider deactivating instead.");
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userName}' deleted successfully.");
    }

    public function toggleStatus(User $user)
    {
        if (Auth::id() === $user->id && $user->is_active) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        if ($user->role === 'admin' && Auth::user()->role !== 'admin' && Auth::id() !== $user->id) {
            return back()->with('error', 'You cannot modify administrator accounts.');
        }

        $newStatus = !$user->is_active;
        $user->update(['is_active' => $newStatus]);

        return back()->with('success', "User '{$user->name}' has been " . ($newStatus ? 'activated' : 'deactivated') . '.');
    }

    public function resetPassword(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Only administrators can reset user passwords.');
        }

        $tempPassword = 'TalentMap' . rand(1000, 9999);

        $user->update([
            'password' => Hash::make($tempPassword),
            'password_changed_at' => null,
        ]);

        return back()
            ->with('success', "Password reset for '{$user->name}'. Temporary password: {$tempPassword}")
            ->with('temp_password', $tempPassword);
    }
}
