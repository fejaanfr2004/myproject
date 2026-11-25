
@extends('layouts.admin')

@section('title', 'Create User | Luxury Fashion Hub')

@push('styles')
<style>
.user-form-container {
    max-width: 700px;
    margin: 10px auto;
    padding: 30px;
    background: #171717;
    border: 1px solid #302b23;
    border-radius: 12px;
}

.user-form-container h1 {
    margin-bottom: 8px;
    color: #f0d39e;
    font-size: 25px;
}

.form-subtitle {
    color: #999;
    margin-bottom: 25px;
    font-size: 13px;
}

.form-group {
    margin-bottom: 19px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #ddd;
    font-size: 13px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 13px;
    border: 1px solid #40382b;
    border-radius: 6px;
    background: #101010;
    color: white;
}

.form-group input:focus,
.form-group select:focus {
    outline: 1px solid #d4af72;
}

.form-error {
    color: #ffaaaa;
    font-size: 13px;
    margin-top: 5px;
}

.form-buttons {
    display: flex;
    gap: 10px;
    margin-top: 25px;
    flex-wrap: wrap;
}

.btn-gold {
    background: #c9a24a;
    color: #111;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

.btn-dark {
    background: #292929;
    color: white;
    padding: 12px 20px;
    border-radius: 5px;
    text-decoration: none;
}
</style>
@endpush

@section('admin-content')

<div class="user-form-container">

    <h1>CREATE NEW USER</h1>

    <p class="form-subtitle">
        Add a new user to Luxury Fashion Hub.
    </p>

    @if($errors->any())
        <div style="background:#481d1d;color:#ffaaaa;padding:12px;margin-bottom:20px;border-radius:6px;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name"
                   value="{{ old('name') }}" required>
            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email"
                   value="{{ old('email') }}" required>
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password"
                   name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label>Assign Role</label>

            <select name="role" required>
                <option value="">Select Role</option>

                @foreach($roles as $role)
                    @if(auth()->user()->hasRole('Super Admin') || $role->name !== 'Super Admin')
                        <option value="{{ $role->name }}"
                            {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endif
                @endforeach
            </select>

            @error('role')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn-gold">
                Create User
            </button>

            <a href="{{ route('admin.users.index') }}"
               class="btn-dark">
                Cancel
            </a>
        </div>
    </form>

</div>

@endsection
