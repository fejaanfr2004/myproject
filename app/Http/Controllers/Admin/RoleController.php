<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    // Display all roles
    public function index()
    {
        $roles = Role::with('permissions')
            ->withCount('users')
            ->orderBy('id')
            ->paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    // Show create role page
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.create', compact('permissions'));
    }

    // Save new role
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name',
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'integer',
                'exists:permissions,id',
            ],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions(
            Permission::whereIn(
                'id',
                $validated['permissions'] ?? []
            )->get()
        );

        $permissionNames = $role->permissions->pluck('name')->implode(', ');

        ActivityLogger::log(
            'Role Created',
            "Created role: {$role->name}. Permissions: " . ($permissionNames ?: 'None'),
            $role
        );

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }

    // Show edit role page
    public function edit(Role $role)
    {
        if ($role->name === 'Super Admin') {
            abort(403, 'Super Admin role cannot be edited here.');
        }

        $permissions = Permission::orderBy('name')->get();

        $role->load('permissions');

        return view(
            'admin.roles.edit',
            compact('role', 'permissions')
        );
    }

    // Update role and permissions
    public function update(Request $request, Role $role)
    {
        if ($role->name === 'Super Admin') {
            abort(403, 'Super Admin role cannot be modified here.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'integer',
                'exists:permissions,id',
            ],
        ]);

        $oldName = $role->name;
        $oldPermissions = $role->permissions->pluck('name')->implode(', ');

        $role->update([
            'name' => $validated['name'],
        ]);

        $role->syncPermissions(
            Permission::whereIn(
                'id',
                $validated['permissions'] ?? []
            )->get()
        );

        $newPermissions = $role->fresh()->permissions->pluck('name')->implode(', ');

        ActivityLogger::log(
            'Role Updated',
            "Updated role from '{$oldName}' to '{$role->name}'. Previous permissions: " .
            ($oldPermissions ?: 'None') . '. New permissions: ' .
            ($newPermissions ?: 'None'),
            $role
        );

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully!');
    }

    // Delete role
    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with(
                'error',
                'Super Admin role cannot be deleted.'
            );
        }

        if ($role->users()->exists()) {
            return back()->with(
                'error',
                'This role is assigned to users and cannot be deleted.'
            );
        }

        ActivityLogger::log(
            'Role Deleted',
            "Deleted role: {$role->name}",
            $role
        );

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}
