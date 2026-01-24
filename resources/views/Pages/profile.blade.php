@extends('layout.main')

@section('content')

<style>
    .container-custom {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 25px;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .profile-header-content {
        display: flex;
        gap: 30px;
        align-items: center;
    }

    .profile-avatar-large {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: var(--pup-maroon);
        font-size: 48px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .profile-info {
        flex: 1;
    }

    .profile-name {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .profile-id {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 15px;
    }

    .profile-stats {
        display: flex;
        gap: 30px;
        margin-top: 20px;
    }

    .stat-box {
        text-align: center;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        display: block;
    }

    .stat-label {
        font-size: 13px;
        opacity: 0.8;
    }

    .section-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--pup-maroon);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        font-size: 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    .org-list {
        display: grid;
        gap: 15px;
    }

    .org-item {
        display: flex;
        gap: 20px;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .org-item:hover {
        transform: translateY(-2px);
        border-color: var(--pup-maroon);
        box-shadow: 0 2px 8px rgba(139, 0, 0, 0.1);
    }

    .org-item-logo {
        width: 60px;
        height: 60px;
        background: var(--pup-maroon);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        font-weight: bold;
        flex-shrink: 0;
    }

    .org-item-info {
        flex: 1;
    }

    .org-item-name {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }

    .org-item-position {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
    }

    .org-item-meta {
        display: flex;
        gap: 15px;
        font-size: 12px;
        color: #999;
    }

    .org-item-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .event-list {
        display: grid;
        gap: 15px;
    }

    .event-item {
        display: flex;
        gap: 20px;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        transition: all 0.2s;
    }

    .event-item:hover {
        border-color: var(--pup-maroon);
        box-shadow: 0 2px 8px rgba(139, 0, 0, 0.1);
    }

    .event-date-box {
        width: 70px;
        height: 70px;
        background: var(--pup-maroon);
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .event-day {
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
    }

    .event-month {
        font-size: 12px;
        text-transform: uppercase;
    }

    .event-item-info {
        flex: 1;
    }

    .event-item-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }

    .event-item-org {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
    }

    .event-item-meta {
        display: flex;
        gap: 15px;
        font-size: 12px;
        color: #999;
        align-items: center;
    }

    .event-item-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .event-status {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-present {
        background: #d4edda;
        color: #155724;
    }

    .status-rsvp {
        background: #fff3cd;
        color: #856404;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
    }
</style>

<div class="container-custom">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar-large">{{ $initials }}</div>
            <div class="profile-info">
                <h1 class="profile-name">{{ $user->full_name }}</h1>
                <p class="profile-id">{{ $user->account_type === 'Faculty' ? 'Faculty' : 'Student' }} ID: {{ $user->school_id }}</p>
                <div class="profile-stats">
                    <div class="stat-box">
                        <span class="stat-number">{{ $stats['organizations_count'] }}</span>
                        <span class="stat-label">Organizations</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">{{ $stats['events_attended_count'] }}</span>
                        <span class="stat-label">Events Attended</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">{{ $stats['upcoming_events_count'] }}</span>
                        <span class="stat-label">Upcoming Events</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-6">
            <!-- Personal Information -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-person-circle"></i>
                    Personal Information
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">{{ $user->account_type === 'Faculty' ? 'Faculty' : 'Student' }} Number</span>
                        <span class="info-value">{{ $user->school_id }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Type</span>
                        <span class="info-value">{{ $user->account_type }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email Address</span>
                        <span class="info-value">{{ $user->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Academic Year</span>
                        <span class="info-value">{{ $currentAcademicYear }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Campus</span>
                        <span class="info-value">{{ $campus }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Member Since</span>
                        <span class="info-value">{{ $memberSince }}</span>
                    </div>
                </div>
            </div>

            <!-- Organizations Joined -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-building"></i>
                    Organizations Joined
                </h2>
                @if(count($organizations) > 0)
                <div class="org-list">
                    @foreach($organizations as $org)
                    <a href="{{ route('orgDetail', $org->org_id) }}" style="text-decoration: none; color: inherit;">
                        <div class="org-item">
                            <div class="org-item-logo">{{ $org->short_name }}</div>
                            <div class="org-item-info">
                                <div class="org-item-name">{{ $org->org_name }}</div>
                                <div class="org-item-position">Position: {{ $org->display_position }}</div>
                                <div class="org-item-meta">
                                    <span><i class="bi bi-calendar-check"></i> Joined: {{ $org->formatted_joined }}</span>
                                    <span><i class="bi bi-shield-check"></i> {{ $org->status }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="bi bi-building"></i>
                    <p>You haven't joined any organizations yet.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6">
            <!-- Events Attended -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-calendar-check"></i>
                    Events Attended
                </h2>
                @if(count($attendedEvents) > 0)
                <div class="event-list">
                    @foreach($attendedEvents as $event)
                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="event-day">{{ $event->formatted_date }}</span>
                            <span class="event-month">{{ $event->formatted_month }}</span>
                        </div>
                        <div class="event-item-info">
                            <div class="event-item-title">{{ $event->title }}</div>
                            <div class="event-item-org">{{ $event->org_name }}</div>
                            <div class="event-item-meta">
                                <span><i class="bi bi-geo-alt"></i> {{ $event->location }}</span>
                                <span class="event-status status-present">Present</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="bi bi-calendar-check"></i>
                    <p>You haven't attended any events yet.</p>
                </div>
                @endif
            </div>

            <!-- Upcoming Events -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-calendar-event"></i>
                    Upcoming Events (RSVP'd)
                </h2>
                @if(count($upcomingEvents) > 0)
                <div class="event-list">
                    @foreach($upcomingEvents as $event)
                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="event-day">{{ $event->formatted_date }}</span>
                            <span class="event-month">{{ $event->formatted_month }}</span>
                        </div>
                        <div class="event-item-info">
                            <div class="event-item-title">{{ $event->title }}</div>
                            <div class="event-item-org">{{ $event->org_name }}</div>
                            <div class="event-item-meta">
                                <span><i class="bi bi-geo-alt"></i> {{ $event->location }}</span>
                                <span class="event-status status-rsvp">RSVP</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="bi bi-calendar-event"></i>
                    <p>You don't have any upcoming events.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endsection