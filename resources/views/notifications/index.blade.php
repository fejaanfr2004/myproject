
@extends('layouts.app')

@section('title', 'My Notifications | Luxury Fashion Hub')

@push('styles')
<style>
    .notifications-container {
        max-width: 1000px;
        margin: 45px auto;
        padding: 25px;
    }

    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .notifications-header h1 {
        color: #171717;
        font-size: 28px;
        margin: 0;
    }

    .notifications-header p {
        color: #777;
        margin-top: 8px;
    }

    .mark-all-btn {
        background: #111;
        color: #c9a96e;
        border: 1px solid #c9a96e;
        padding: 11px 18px;
        cursor: pointer;
        font-weight: bold;
    }

    .mark-all-btn:hover {
        background: #c9a96e;
        color: #111;
    }

    .notification-card {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-left: 4px solid #c9a96e;
        padding: 20px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        border-radius: 5px;
    }

    .notification-card.unread {
        background: #fffaf0;
        border-left-color: #111;
    }

    .notification-info h3 {
        margin: 0 0 8px;
        font-size: 17px;
        color: #171717;
    }

    .notification-info p {
        margin: 0 0 10px;
        color: #666;
        line-height: 1.5;
    }

    .notification-date {
        font-size: 12px;
        color: #999;
    }

    .notification-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-width: 120px;
    }

    .read-btn {
        background: #111;
        color: #c9a96e;
        border: none;
        padding: 9px 12px;
        cursor: pointer;
        font-weight: bold;
    }

    .read-btn:hover {
        background: #c9a96e;
        color: #111;
    }

    .read-status {
        color: #777;
        font-size: 12px;
        text-align: center;
    }

    .empty-notifications {
        background: white;
        padding: 55px 20px;
        text-align: center;
        border: 1px solid #eee;
    }

    .empty-notifications h2 {
        color: #171717;
    }

    .empty-notifications p {
        color: #777;
    }

    .pagination-wrapper {
        margin-top: 25px;
    }

    @media(max-width: 600px) {
        .notifications-container {
            padding: 15px;
        }

        .notification-card {
            align-items: flex-start;
            flex-direction: column;
        }

        .notification-actions {
            flex-direction: row;
        }
    }
</style>
@endpush

@section('content')

<div class="notifications-container">

    <div class="notifications-header">
        <div>
            <h1>My Notifications</h1>
            <p>Stay updated about your orders and account.</p>
        </div>

        @if(auth()->user()->unreadNotifications()->exists())
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="mark-all-btn">
                    MARK ALL AS READ
                </button>
            </form>
        @endif
    </div>

    @forelse($notifications as $notification)

        <div class="notification-card {{ $notification->read_at ? '' : 'unread' }}">

            <div class="notification-info">

                <h3>
                    {{ $notification->data['title'] ?? 'Notification' }}
                </h3>

                <p>
                    {{ $notification->data['message'] ?? '' }}
                </p>

                <span class="notification-date">
                    {{ $notification->created_at->format('d M Y, h:i A') }}
                </span>

            </div>

            <div class="notification-actions">

                @if(!$notification->read_at)

                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="read-btn">
                            MARK AS READ
                        </button>
                    </form>

                @else

                    <span class="read-status">READ</span>

                @endif

            </div>

        </div>

    @empty

        <div class="empty-notifications">
            <h2>No Notifications Yet</h2>
            <p>When there are updates about your orders, they will appear here.</p>
        </div>

    @endforelse

    <div class="pagination-wrapper">
        {{ $notifications->links() }}
    </div>

</div>

@endsection
