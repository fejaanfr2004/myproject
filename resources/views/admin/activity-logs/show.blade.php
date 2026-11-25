
@extends('layouts.admin')

@section('title', 'Activity Details | Luxury Fashion Hub')

@push('styles')
<style>
    .activity-details-container {
        padding: 25px;
        color: #f5f5f5;
    }

    .details-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .details-header h1 {
        color: #d4af72;
        font-size: 27px;
        margin: 0 0 8px;
    }

    .details-header p {
        color: #999;
        margin: 0;
    }

    .details-card {
        background: #171717;
        border: 1px solid #302b23;
        border-radius: 12px;
        padding: 25px;
        max-width: 950px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .detail-item {
        border-bottom: 1px solid #302b23;
        padding-bottom: 15px;
        min-width: 0;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-label {
        color: #999;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .detail-value {
        color: #f5f5f5;
        font-size: 15px;
        overflow-wrap: anywhere;
    }

    .action-badge {
        display: inline-block;
        background: rgba(212, 175, 114, .12);
        color: #d4af72;
        border: 1px solid #705a36;
        padding: 5px 12px;
        border-radius: 20px;
    }

    .btn-back {
        display: inline-block;
        background: #d4af72;
        color: #111;
        padding: 10px 17px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-back:hover {
        background: #f0d39e;
        color: #111;
    }

    @media (max-width: 650px) {
        .details-grid {
            grid-template-columns: 1fr;
        }

        .detail-item.full-width {
            grid-column: auto;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="activity-details-container">

    <div class="details-header">
        <div>
            <h1>Activity Details</h1>
            <p>Complete information about this administrative activity.</p>
        </div>

        <a href="{{ route('admin.activity-logs.index') }}" class="btn-back">
            ← Back to Activity Logs
        </a>
    </div>

    <div class="details-card">
        <div class="details-grid">

            <div class="detail-item">
                <div class="detail-label">Activity ID</div>
                <div class="detail-value">#{{ $activity->id }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Date & Time</div>
                <div class="detail-value">
                    {{ $activity->created_at?->format('d M Y, h:i:s A') ?? 'N/A' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">User Name</div>
                <div class="detail-value">{{ $activity->user_name ?? 'System' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">User Email</div>
                <div class="detail-value">{{ $activity->user_email ?? 'N/A' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Action</div>
                <div class="detail-value">
                    <span class="action-badge">{{ $activity->action }}</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">IP Address</div>
                <div class="detail-value">{{ $activity->ip_address ?? 'N/A' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Subject Type</div>
                <div class="detail-value">{{ $activity->subject_type ?? 'N/A' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Subject ID</div>
                <div class="detail-value">{{ $activity->subject_id ?? 'N/A' }}</div>
            </div>

            <div class="detail-item full-width">
                <div class="detail-label">Full Description</div>
                <div class="detail-value">{{ $activity->description }}</div>
            </div>

        </div>
    </div>

</div>
@endsection
