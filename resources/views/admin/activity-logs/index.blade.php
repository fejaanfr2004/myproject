
@extends('layouts.admin')

@section('title', 'Activity Logs | Luxury Fashion Hub')

@push('styles')
<style>
.activity-container {
    padding: 25px;
    color: #f5f5f5;
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
}

.activity-header h1 {
    color: #d4af37;
    font-size: 27px;
    margin: 0;
}

.activity-header p {
    color: #aaa;
    margin: 6px 0 0;
}

.activity-count {
    color: #d4af37;
    font-size: 14px;
}

.activity-filter {
    background: #171717;
    border: 1px solid #302b23;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
}

.activity-filter form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    align-items: end;
}

.activity-filter label {
    display: block;
    color: #d4af37;
    font-size: 13px;
    margin-bottom: 7px;
}

.activity-filter input,
.activity-filter select {
    width: 100%;
    padding: 11px;
    background: #242424;
    color: #fff;
    border: 1px solid #40392d;
    border-radius: 7px;
    outline: none;
}

.activity-filter input:focus,
.activity-filter select:focus {
    border-color: #d4af37;
}

.filter-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-gold,
.btn-reset {
    display: inline-block;
    padding: 11px 18px;
    border-radius: 7px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-weight: 600;
    text-align: center;
}

.btn-gold {
    background: #d4af37;
    color: #111;
}

.btn-gold:hover {
    background: #f0cb52;
    color: #111;
}

.btn-reset {
    background: #333;
    color: #fff;
}

.btn-reset:hover {
    background: #444;
    color: #fff;
}

.activity-table-wrapper {
    background: #171717;
    border: 1px solid #302b23;
    border-radius: 12px;
    overflow-x: auto;
}

.activity-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 950px;
}

.activity-table th {
    background: #242424;
    color: #d4af37;
    padding: 16px 14px;
    text-align: left;
    font-size: 13px;
    white-space: nowrap;
}

.activity-table td {
    padding: 15px 14px;
    border-top: 1px solid #302b23;
    color: #ddd;
    font-size: 13px;
    vertical-align: top;
}

.activity-table tbody tr:hover {
    background: #202020;
}

.activity-user {
    color: #fff;
    font-weight: 600;
}

.activity-email {
    color: #999;
    font-size: 11px;
    margin-top: 4px;
}

.action-badge {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    background: #332c18;
    color: #e5c45b;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.activity-description {
    max-width: 350px;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.activity-ip {
    color: #aaa;
    white-space: nowrap;
}

.activity-empty {
    padding: 45px 20px !important;
    text-align: center;
    color: #aaa !important;
}

.pagination-wrapper {
    padding: 20px;
    display: flex;
    justify-content: center;
}

.pagination-wrapper nav {
    display: flex;
    justify-content: center;
}

.pagination-wrapper nav p {
    color: #aaa;
    margin-bottom: 12px;
}

.pagination-wrapper nav a,
.pagination-wrapper nav span {
    display: inline-block;
    padding: 8px 12px;
    margin: 2px;
    background: #242424;
    color: #d4af37;
    border: 1px solid #40392d;
    border-radius: 5px;
    text-decoration: none;
}

.pagination-wrapper nav span[aria-current="page"] {
    background: #d4af37;
    color: #111;
}

@media (max-width: 600px) {
    .activity-container {
        padding: 12px;
    }

    .activity-header h1 {
        font-size: 22px;
    }
}
</style>
@endpush

@section('admin-content')

<div class="activity-container">

    {{-- HEADER --}}

    <div class="activity-header">

        <div>
            <h1>Activity Logs</h1>
            <p>Monitor administrative activities across your website.</p>
        </div>

        <div style="display:flex; align-items:center; gap:15px; flex-wrap:wrap;">

            <div class="activity-count">
                Total Records: {{ $activities->total() }}
            </div>

            <a href="{{ route('admin.activity-logs.export', request()->query()) }}"
               class="btn-gold">
                ↓ Export CSV
            </a>

        </div>

    </div>

    {{-- SEARCH AND FILTERS --}}

    <div class="activity-filter">

        <form method="GET" action="{{ route('admin.activity-logs.index') }}">

            <div>
                <label for="search">Search Activities</label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Name, email, action, IP..."
                >
            </div>

            <div>
                <label for="action">Filter by Action</label>

                <select name="action" id="action">

                    <option value="">All Activities</option>

                    @foreach($actions as $action)
                        <option
                            value="{{ $action }}"
                            {{ request('action') == $action ? 'selected' : '' }}
                        >
                            {{ $action }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label for="date_from">From Date</label>

                <input
                    type="date"
                    id="date_from"
                    name="date_from"
                    value="{{ request('date_from') }}"
                >
            </div>

            <div>
                <label for="date_to">To Date</label>

                <input
                    type="date"
                    id="date_to"
                    name="date_to"
                    value="{{ request('date_to') }}"
                >
            </div>

            <div class="filter-actions">

                <button type="submit" class="btn-gold">
                    Apply Filters
                </button>

                <a href="{{ route('admin.activity-logs.index') }}" class="btn-reset">
                    Reset
                </a>

            </div>

        </form>

    </div>

    {{-- ACTIVITY TABLE --}}

    <div class="activity-table-wrapper">

        <table class="activity-table">

            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Admin / User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Details</th>
                </tr>
            </thead>

            <tbody>

                @forelse($activities as $activity)

                    <tr>

                        <td>
                            {{ $activity->created_at->format('d M Y') }}

                            <br>

                            <span style="color:#999;">
                                {{ $activity->created_at->format('h:i A') }}
                            </span>
                        </td>

                        <td>
                            <div class="activity-user">
                                {{ $activity->user_name ?? 'System' }}
                            </div>

                            <div class="activity-email">
                                {{ $activity->user_email ?? 'N/A' }}
                            </div>
                        </td>

                        <td>
                            <span class="action-badge">
                                {{ $activity->action }}
                            </span>
                        </td>

                        <td>
                            <div class="activity-description">
                                {{ $activity->description }}
                            </div>
                        </td>

                        <td>
                            <span class="activity-ip">
                                {{ $activity->ip_address ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            <a
                                href="{{ route('admin.activity-logs.show', $activity->id) }}"
                                class="btn-gold"
                                style="padding:7px 12px; font-size:12px; white-space:nowrap;"
                                data-admin-link
                            >
                                View Details
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="activity-empty">
                            No activity records found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <div class="pagination-wrapper">
            {{ $activities->links() }}
        </div>

    </div>

</div>

@endsection
