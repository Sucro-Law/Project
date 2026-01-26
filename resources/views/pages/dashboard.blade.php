@extends('layout.main')

@push('styles')
@vite(['resources/css/dashboard.css'])
@endpush

@section('content')

<div class="container-custom">
    <div class="content-area">
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill"></i>
            {{ session('info') }}
        </div>
        @endif

        <!-- Organizations Section -->
        <div class="section-header">
            <a href="{{ route('organization') }}" class="section-title">
                Organizations <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="org-cards">
            @forelse($organizations as $org)
            <div class="org-card">
                <div class="org-header">
                    <div class="org-logo">{{ $org->short_name }}</div>
                    <div class="org-info-header">
                        <h3>{{ $org->org_name }}</h3>
                        <span class="org-status">{{ strtoupper($org->status) }} • {{ $org->year }}</span>
                    </div>
                </div>
                <div class="org-description">
                    {{ $org->description ?? 'No description available' }}
                </div>
                <div class="org-footer">
                    <div class="org-members">
                        <i class="bi bi-people-fill"></i> {{ $org->member_count ?? 0 }} Members
                    </div>
                    <a href="{{ route('orgDetail', ['id' => $org->org_id]) }}" class="btn-view-org">View Organization</a>
                </div>
            </div>
            @empty
            <div class="alert alert-info" style="grid-column: 1/-1;">
                <i class="bi bi-info-circle-fill"></i>
                No organizations available at the moment.
            </div>
            @endforelse
        </div>
        @if(count($organizations) > 0)
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('organization') }}" class="btn-view-more">View more...</a>
        </div>
        @endif

        <!-- Events Section -->
        <div class="section-header" style="margin-top: 50px;">
            <a href="{{ route('events') }}" class="section-title">
                Events <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="events-grid">
            @forelse($events as $event)
            <div class="event-card">
                <div class="event-image">
                    <span class="event-date-badge">{{ $event->formatted_date }} | {{ $event->venue ?? 'TBA' }}</span>
                    📅
                </div>
                <div class="event-content">
                    <div class="event-title">{{ $event->title }}</div>
                    <div class="event-description">
                        {{ Str::limit($event->description ?? 'No description available', 100) }}
                    </div>
                    <div class="event-meta">
                        <div class="event-author">– {{ $event->org_name }}</div>
                        <div class="event-likes">
                            <i class="bi bi-people-fill"></i>
                            {{ $event->rsvp_count }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="event-card">
                <div class="event-content" style="text-align: center; padding: 40px;">
                    <i class="bi bi-calendar-x" style="font-size: 48px; color: #ccc; display: block; margin-bottom: 15px;"></i>
                    <p style="color: #999; margin: 0;">No upcoming events at the moment</p>
                </div>
            </div>
            @endforelse
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('events') }}" class="btn-view-more">View more...</a>
        </div>

        @endsection