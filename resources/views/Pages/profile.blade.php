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

    /* Section Cards */
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

    /* Info Grid */
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

    /* Organization Cards */
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

    /* Event Cards */
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

    .status-attended {
        background: #d4edda;
        color: #155724;
    }

    .status-upcoming {
        background: #fff3cd;
        color: #856404;
    }

    /* Empty State */
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
</style>

<div class="container-custom">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar-large">FL</div>
            <div class="profile-info">
                <h1 class="profile-name">FN MI. LN</h1>
                <p class="profile-id">Student ID: SN-XXXXXXXXXX</p>
                <div class="profile-stats">
                    <div class="stat-box">
                        <span class="stat-number">2</span>
                        <span class="stat-label">Organizations</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">5</span>
                        <span class="stat-label">Events Attended</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">3</span>
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
                        <span class="info-label">Student Number</span>
                        <span class="info-value">SN-XXXXXXXXXX</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Type</span>
                        <span class="info-value">Student</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email Address</span>
                        <span class="info-value">FNLN@gmail.com</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Academic Year</span>
                        <span class="info-value">2026-2027</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Campus</span>
                        <span class="info-value">PUP - Manila</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Member Since</span>
                        <span class="info-value">December 2, 2025</span>
                    </div>
                </div>
            </div>

            <!-- Organizations Joined -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-building"></i>
                    Organizations Joined
                </h2>
                <div class="org-list">
                    <div class="org-item">
                        <div class="org-item-logo">GDG</div>
                        <div class="org-item-info">
                            <div class="org-item-name">Google Developer Groups on Campus – PUP</div>
                            <div class="org-item-position">Position: Member</div>
                            <div class="org-item-meta">
                                <span><i class="bi bi-calendar-check"></i> Joined: Jan 7, 2026</span>
                                <span><i class="bi bi-shield-check"></i> Active</span>
                            </div>
                        </div>
                    </div>
                    <div class="org-item">
                        <div class="org-item-logo">AWS</div>
                        <div class="org-item-info">
                            <div class="org-item-name">Amazon Web Services – PUP</div>
                            <div class="org-item-position">Position: Member</div>
                            <div class="org-item-meta">
                                <span><i class="bi bi-calendar-check"></i> Joined: Jan 7, 2026</span>
                                <span><i class="bi bi-shield-check"></i> Active</span>
                            </div>
                        </div>
                    </div>
                </div>
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
                <div class="event-list">
                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="event-day">18</span>
                            <span class="event-month">JAN</span>
                        </div>
                        <div class="event-item-info">
                            <div class="event-item-title">2026: Web Development Workshop</div>
                            <div class="event-item-org">Google Developer Groups on Campus – PUP</div>
                            <div class="event-item-meta">
                                <span><i class="bi bi-geo-alt"></i> PUP South Campus</span>
                                <span class="event-status status-attended mt-3">Attended</span>
                            </div>
                        </div>
                    </div>
                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="event-day">15</span>
                            <span class="event-month">JAN</span>
                        </div>
                        <div class="event-item-info">
                            <div class="event-item-title">AWS Cloud Computing Basics</div>
                            <div class="event-item-org">Amazon Web Services – PUP</div>
                            <div class="event-item-meta">
                                <span><i class="bi bi-geo-alt"></i> PUP Main Campus</span>
                                <span class="event-status status-attended mt-3">Attended</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-calendar-event"></i>
                    Upcoming Events (RSVP'd)
                </h2>
                <div class="event-list">
                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="event-day">25</span>
                            <span class="event-month">JAN</span>
                        </div>
                        <div class="event-item-info">
                            <div class="event-item-title">Tech Summit 2026</div>
                            <div class="event-item-org">Google Developer Groups on Campus – PUP</div>
                            <div class="event-item-meta">
                                <span><i class="bi bi-geo-alt"></i> PUP Main Campus</span>
                                <span class="event-status status-upcoming mt-3">Upcoming</span>
                            </div>
                        </div>
                    </div>
                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="event-day">30</span>
                            <span class="event-month">JAN</span>
                        </div>
                        <div class="event-item-info">
                            <div class="event-item-title">Serverless Architecture Workshop</div>
                            <div class="event-item-org">Amazon Web Services – PUP</div>
                            <div class="event-item-meta">
                                <span><i class="bi bi-geo-alt"></i> PUP Main Campus</span>
                                <span class="event-status status-upcoming mt-3">Upcoming</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection