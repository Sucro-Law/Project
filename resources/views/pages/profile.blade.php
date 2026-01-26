@extends('layout.main')

@push('styles')
@vite(['resources/css/profile.css'])
@endpush

@section('content')

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

            <!-- Organizations Joined/Advised -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-building"></i>
                    {{ $user->account_type === 'Faculty' ? 'Organizations Advised' : 'Organizations Joined' }}
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
                    <p>{{ $user->account_type === 'Faculty' ? "You're not advising any organizations yet." : "You haven't joined any organizations yet." }}</p>
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