
@extends('layouts.admin')

@section('title', 'Role Management')

@push('styles')
<style>
    .roles-page { color: #e5e5e5; }
    .roles-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    .roles-header h2 { color: #d4af37; margin: 0; }
    .role-card {
        background: #171717;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
    }
    .role-card h3 { color: #d4af37; margin: 0 0 12px; }
    .role-meta { color: #aaa; margin-bottom: 12px; }
    .permission-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 15px;
    }
    .permission-tag {
        background: #292929;
        color: #ddd;
        border: 1px solid #444;
        padding: 5px 9px;
        border-radius: 5px;
        font-size: 12px;
    }
    .role-actions { display: flex; gap: 10px; }
    .role-btn {
        display: inline-block;
        padding: 8px 14px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        cursor: pointer;
        font-size: 13px;
    }
    .edit-btn { background: #d4af37; color: #111; }
    .delete-btn { background: #a83232; color: white; }
    .success-message {
        background: #173d28;
        color: #9ee6b5;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
    }
    .error-message {
        background: #491e1e;
        color: #ffb3b3;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
    }
    .pagination { margin-top: 20px; }
</style>
@endpush

@section('admin-content')
<div class="roles-page">

    <div class="roles-header">
        <h2>Roles & Permissions</h2>

        <a href="{{ route('admin.roles.create') }}"
           class="role-btn edit-btn"
           data-admin-link>
            + Create New Role
        </a>
    </div>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif

    @forelse($roles as $role)
        <div class="role-card">

            <h3>{{ $role->name }}</h3>

            <div class="role-meta">
                Assigned users: {{ $role->users_count }}
                &nbsp; | &nbsp;
                Permissions: {{ $role->permissions->count() }}
            </div>

            <div class="permission-list">
                @forelse($role->permissions as $permission)
                    <span class="permission-tag">
                        {{ $permission->name }}
                    </span>
                @empty
                    <span class="permission-tag">No permissions assigned</span>
                @endforelse
            </div>

            <div class="role-actions">

                @if($role->name !== 'Super Admin')
                    <a href="{{ route('admin.roles.edit', $role) }}"
                       class="role-btn edit-btn"
                       data-admin-link>
                        Edit
                    </a>

                    <form action="{{ route('admin.roles.destroy', $role) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this role?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="role-btn delete-btn">
                            Delete
                        </button>
                    </form>
                @else
                    <span class="permission-tag">
                        Protected system role
                    </span>
                @endif

            </div>
        </div>
    @empty
        <p>No roles found.</p>
    @endforelse

    <div class="pagination">
        {{ $roles->links() }}
    </div>

</div>
@endsection
