
@extends('layouts.admin')

@section('title', 'Edit Role')

@push('styles')
<style>
    .role-form-page { color: #eee; max-width: 850px; }
    .role-form-page h2 { color: #d4af37; margin-bottom: 20px; }
    .role-form-card {
        background: #171717;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 25px;
    }
    .role-form-page label { display: block; margin-bottom: 8px; }
    .role-form-page input[type="text"] {
        width: 100%;
        padding: 12px;
        background: #252525;
        color: white;
        border: 1px solid #555;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .permission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
        margin: 15px 0 25px;
    }
    .permission-item {
        background: #252525;
        border: 1px solid #444;
        border-radius: 6px;
        padding: 12px;
        display: flex !important;
        align-items: center;
        gap: 10px;
        margin: 0 !important;
    }
    .permission-item input { accent-color: #d4af37; }
    .role-submit {
        background: #d4af37;
        color: #111;
        border: none;
        border-radius: 5px;
        padding: 11px 20px;
        cursor: pointer;
        font-weight: bold;
    }
    .role-back {
        color: #d4af37;
        text-decoration: none;
        margin-left: 12px;
    }
    .form-error {
        color: #ff8b8b;
        margin: -12px 0 15px;
        font-size: 13px;
    }
</style>
@endpush

@section('admin-content')
<div class="role-form-page">

    <h2>Edit Role: {{ $role->name }}</h2>

    <div class="role-form-card">

        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="name">Role Name</label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $role->name) }}"
                required
            >

            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror

            <h3 style="color:#d4af37;">Manage Permissions</h3>

            <div class="permission-grid">
                @foreach($permissions as $permission)
                    <label class="permission-item">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->id }}"
                            {{ in_array(
                                $permission->id,
                                old('permissions', $role->permissions->pluck('id')->toArray())
                            ) ? 'checked' : '' }}
                        >
                        {{ $permission->name }}
                    </label>
                @endforeach
            </div>

            @error('permissions')
                <div class="form-error">{{ $message }}</div>
            @enderror

            <button type="submit" class="role-submit">
                Update Role
            </button>

            <a href="{{ route('admin.roles.index') }}"
               class="role-back"
               data-admin-link>
                Cancel
            </a>

        </form>

    </div>
</div>
@endsection
