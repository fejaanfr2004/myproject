
@extends('layouts.admin')

@section('title', 'Notifications | Luxury Fashion Hub')

@push('styles')
<style>
.notification-container {
    padding: 25px;
    color: #f5f5f5;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
}

.notification-header h1 {
    color: #d4af72;
    font-size: 27px;
    margin: 0;
}

.notification-header p {
    color: #999;
    margin-top: 7px;
}

.notification-card {
    background: #171717;
    border: 1px solid #302b23;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.notification-card.unread {
    border-left: 4px solid #d4af72;
    background: #1e1b16;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-content h3 {
    color: #f0d39e;
    font-size: 16px;
    margin-bottom: 8px;
}

.notification-content p {
    color: #ccc;
    font-size: 13px;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.notification-meta {
    color: #888;
    font-size: 11px;
    margin-top: 12px;
}

.notification-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.btn-notification {
    display: inline-block;
    padding: 9px 13px;
    border-radius: 6px;
    background: #d4af72;
    color: #111;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.btn-notification:hover {
    background: #f0d39e;
    color: #111;
}

.btn-secondary {
    background: #333;
    color: #fff;
}

.btn-secondary:hover {
    background: #444;
    color: #fff;
}

.unread-label {
    color: #d4af72;
    font-size: 11px;
    font-weight: 700;
}

.notification-empty {
    background: #171717;
    border: 1px solid #302b23;
    border-radius: 10px;
    padding: 55px 20px;
    text-align: center;
    color: #999;
}

.notification-empty h3 {
    color: #d4af72;
    margin-bottom: 10px;
}

.notification-pagination {
    display: flex;
    justify-content: center;
    padding: 20px;
}

.notification-pagination nav a,
.notification-pagination nav span {
    display: inline-block;
    padding: 8px 12px;
    margin: 2px;
    background: #242424;
    color: #d4af72;
    border: 1px solid #40392d;
    border-radius: 5px;
    text-decoration: none;
}

.notification-pagination nav span[aria-current="page"] {
    background: #d4af72;
    color: #111;
}

.notification-message {
    padding: 12px 15px;
    margin-bottom: 20px;
    background: #19351f;
    border: 1px solid #285c35;
    color: #a8e6b4;
    border-radius: 7px;
}

@media(max-width: 650px) {
    .notification-container {
        padding: 12px;
    }

    .notification-card {
        flex-direction: column;
    }

    .notification-header h1 {
        font-size: 22px;
    }
}
</style>
@endpush

@section('admin-content')

<div class="notification-container">

    <div class="notification-header">
        <div>
            <h1>Admin Notifications</h1>
            <p>Stay updated with your store activities.</p>
        </div>

        <form method="POST"
              action="{{ route('admin.notifications.read-all') }}">
            @csrf

            <button type="submit" class="btn-notification">
                Mark All as Read
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="notification-message">
            {{ session('success') }}
        </div>
    @endif

    @forelse($notifications as $notification)

        <div class="notification-card {{ $notification->read_at ? '' : 'unread' }}">

            <div class="notification-content">

                <h3>
                    {{ $notification->data['title'] ?? 'Notification' }}

                    @if(!$notification->read_at)
                        <span class="unread-label"> • NEW</span>
                    @endif
                </h3>

                <p>
                    {{ $notification->data['message'] ?? 'You have a new notification.' }}
                </p>

                <div class="notification-meta">
                    Type: {{ ucfirst($notification->data['type'] ?? 'general') }}
                    &nbsp; | &nbsp;
                    {{ $notification->created_at->format('d M Y, h:i A') }}
                </div>

            </div>

            <div class="notification-actions">

                @if(!$notification->read_at)
                    <form method="POST"
                          action="{{ route('admin.notifications.read', $notification->id) }}">
                        @csrf

                        <button type="submit" class="btn-notification btn-secondary">
                            Mark as Read
                        </button>
                    </form>
                @endif

                @if(!empty($notification->data['url']))
                    <a href="{{ $notification->data['url'] }}"
                       class="btn-notification"
                       data-admin-link>
                        View
                    </a>
                @endif

            </div>

        </div>

    @empty

        <div class="notification-empty">
            <h3>No Notifications Yet</h3>
            <p>New store notifications will appear here.</p>
        </div>

    @endforelse

    <div class="notification-pagination">
        {{ $notifications->links() }}
    </div>

</div>

@endsection
