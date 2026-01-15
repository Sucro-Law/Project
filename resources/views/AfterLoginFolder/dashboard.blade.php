@extends('layout.main')


@section('content')

<div class="content-area">
    <!-- Events Section -->
    <div class="section-title">Events</div>
    <div class="events-grid">
        <div class="event-card">
            <div class="event-image">
                <span class="event-date-badge">01/18/26 | PUP South</span>
                💻
            </div>
            <div class="event-content">
                <div class="event-title">2026: Web Development Workshop</div>
                <div class="event-description">
                    This is how you do it! Building the web of tomorrow—one line of code at a time. 💻
                </div>
                <div class="event-meta">
                    <div class="event-author">– GDG</div>
                    <div class="event-likes">
                        <i class="bi bi-heart-fill"></i>
                        19
                    </div>
                </div>
            </div>
        </div>

        <div class="event-card">
            <div class="event-image">
                <span class="event-date-badge">01/20/26 | Main Campus</span>
                🚀
            </div>
            <div class="event-content">
                <div class="event-title">This is how you do it! - IBITS</div>
                <div class="event-description">
                    Are you ready to step out of your comfort zone?
                </div>
                <div class="event-meta">
                    <div class="event-author">– Mr. President</div>
                    <div class="event-likes">
                        <i class="bi bi-heart-fill"></i>
                        24
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="view-more-btn">
        <a href="#" class="btn-view-more">View more...</a>
    </div>

    <!-- Organizations Section -->
    <div class="section-title" style="margin-top: 50px;">Organizations</div>
    <div class="org-cards">
        <div class="org-card">
            <div class="org-header">
                <div class="org-logo">GDG</div>
                <div class="org-info-header">
                    <h3>Google Developer Groups on Campus – PUP</h3>
                    <span class="org-status">ACTIVE • 2018</span>
                </div>
            </div>
            <div class="org-description">
                An organization is a group of people who work together, like a neighborhood association, a charity, a union, or a corporation.
            </div>
            <div class="org-footer">
                <div class="org-members">
                    <i class="bi bi-people-fill"></i> 23 Members
                </div>
                <a href="{{ route('orgDetail') }}" class="btn-view-org">View Organization</a>
            </div>
        </div>

        <div class="org-card">
            <div class="org-header">
                <div class="org-logo">AWS</div>
                <div class="org-info-header">
                    <h3>Amazon Web Services – PUP</h3>
                    <span class="org-status">ACTIVE • 2020</span>
                </div>
            </div>
            <div class="org-description">
                Learn cloud computing and modern infrastructure with AWS technologies and tools.
            </div>
            <div class="org-footer">
                <div class="org-members">
                    <i class="bi bi-people-fill"></i> 18 Members
                </div>
                <a href="#" class="btn-view-org">View Organization</a>
            </div>
        </div>
    </div>
</div>


@endsection