<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // DISPLAY ALL USERS
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // SHOW CREATE FORM
    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    // STORE NEW USER
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        // Only Super Admin can assign the Super Admin role.
        if (
            $validated['role'] === 'Super Admin' &&
            !auth()->user()->hasRole('Super Admin')
        ) {
            return back()
                ->withErrors(['role' => 'Only a Super Admin can assign the Super Admin role.'])
                ->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        ActivityLogger::log(
            'User Created',
            "Created user: {$user->name} ({$user->email}) with role: {$validated['role']}",
            $user
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    // SHOW EDIT FORM
    public function edit(User $user)
    {
        // Non-Super Admin users cannot edit Super Admin accounts.
        if (
            $user->hasRole('Super Admin') &&
            !auth()->user()->hasRole('Super Admin')
        ) {
            abort(403, 'You cannot edit a Super Admin account.');
        }

        $roles = Role::orderBy('name')->get();

        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    // UPDATE USER
    public function update(Request $request, User $user)
    {
        // Protect Super Admin accounts from non-Super Admin users.
        if (
            $user->hasRole('Super Admin') &&
            !auth()->user()->hasRole('Super Admin')
        ) {
            abort(403, 'You cannot modify a Super Admin account.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        // Only Super Admin can assign the Super Admin role.
        if (
            $validated['role'] === 'Super Admin' &&
            !auth()->user()->hasRole('Super Admin')
        ) {
            return back()
                ->withErrors(['role' => 'Only a Super Admin can assign the Super Admin role.'])
                ->withInput();
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->syncRoles([$validated['role']]);

        ActivityLogger::log(
            'User Updated',
            "Updated user: {$user->name} ({$user->email}); assigned role: {$validated['role']}",
            $user
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    // DELETE USER
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->hasRole('Super Admin')) {
            return back()->with('error', 'Super Admin accounts cannot be deleted here.');
        }

        ActivityLogger::log(
            'User Deleted',
            "Deleted user: {$user->name} ({$user->email})",
            $user
        );

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
