
@extends('layouts.admin')

@section('title', 'User Management | Luxury Fashion Hub')

@push('styles')
<style>
.admin-users {
    max-width: 1200px;
    margin: 10px auto;
    padding: 10px;
    color: #eee;
}

.admin-users h1 {
    font-size: 27px;
    margin-bottom: 8px;
    color: #f0d39e;
}

.subtitle {
    color: #999;
    margin-bottom: 25px;
    font-size: 13px;
}

.user-toolbar {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.search-form {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.search-form input {
    padding: 12px;
    border: 1px solid #40382b;
    border-radius: 6px;
    min-width: 230px;
    background: #171717;
    color: white;
}

.search-form input:focus {
    outline: 1px solid #d4af72;
}

.btn {
    padding: 11px 17px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    font-weight: bold;
    display: inline-block;
    font-size: 12px;
}

.btn-gold {
    background: #c9a24a;
    color: #111;
}

.btn-dark {
    background: #292929;
    color: white;
}

.btn-red {
    background: #b83232;
    color: white;
}

.table-wrapper {
    overflow-x: auto;
    background: #171717;
    border: 1px solid #302b23;
    border-radius: 10px;
}

.admin-users table {
    width: 100%;
    border-collapse: collapse;
    min-width: 700px;
}

.admin-users th,
.admin-users td {
    padding: 16px;
    text-align: left;
    border-bottom: 1px solid #302b23;
    font-size: 12px;
}

.admin-users th {
    background: #222;
    color: #d6b45b;
}

.admin-users td {
    color: #ddd;
}

.role-badge {
    background: rgba(212,175,114,.15);
    color: #f0d39e;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
    margin: 2px;
}

.actions {
    display: flex;
    gap: 7px;
    align-items: center;
}

.actions form {
    margin: 0;
}

.alert {
    padding: 13px;
    margin-bottom: 15px;
    border-radius: 6px;
    font-size: 13px;
}

.success {
    background: #173b24;
    color: #9be2ad;
}

.error {
    background: #481d1d;
    color: #ffaaaa;
}

.pagination {
    margin-top: 20px;
}

.pagination nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.pagination a,
.pagination span {
    color: #d4af72;
    text-decoration: none;
}

.pagination p {
    color: #999;
    font-size: 12px;
}

@media(max-width: 600px) {
    .user-toolbar,
    .search-form {
        width: 100%;
    }

    .search-form input {
        width: 100%;
    }
}
</style>
@endpush

@section('admin-content')

<div class="admin-users">

    <h1>USER MANAGEMENT</h1>

    <p class="subtitle">
        Manage registered customers, administrators and user roles.
    </p>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="user-toolbar">

        <form action="{{ route('admin.users.index') }}"
              method="GET"
              class="search-form">

            <input
                type="text"
                name="search"
                placeholder="Search name or email..."
                value="{{ request('search') }}"
            >

            <button type="submit" class="btn btn-dark">
                Search
            </button>

            <a href="{{ route('admin.users.index') }}"
               class="btn btn-dark">
                Reset
            </a>

        </form>

        <a href="{{ route('admin.users.create') }}"
           class="btn btn-gold">
            + Add New User
        </a>

    </div>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)

                    <tr>
                        <td>{{ $user->id }}</td>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>

                        <td>
                            @forelse($user->roles as $role)
                                <span class="role-badge">
                                    {{ $role->name }}
                                </span>
                            @empty
                                No role
                            @endforelse
                        </td>

                        <td>
                            {{ $user->created_at?->format('d M Y') }}
                        </td>

                        <td>

                            <div class="actions">

                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="btn btn-gold">
                                    Edit
                                </a>

                                @if($user->id !== auth()->id() && !$user->hasRole('Super Admin'))

                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                          method="POST"
                                          data-confirm="Are you sure you want to delete this user?">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-red">
                                            Delete
                                        </button>

                                    </form>

                                @endif

                            </div>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" style="text-align:center;padding:30px;">
                            No users found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="pagination">
        {{ $users->links() }}
    </div>

    <div style="margin-top:25px;">
        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-dark">
            ← Back to Dashboard
        </a>
    </div>

</div>

@endsection
