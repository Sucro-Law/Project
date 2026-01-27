@extends('layout.main')

@push('styles')
@vite(['resources/css/event.css'])
@endpush

@section('content')

<div class="container-custom">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <h1 class="page-title">Events</h1>

    {{-- Search Bar --}}
    <div class="search-container mb-4">
        <form action="{{ route('events.search') }}" method="GET" class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Search events by title, description, or organization..." value="{{ $query ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary px-4" style="background: var(--pup-maroon); border-color: var(--pup-maroon);">Search</button>
            @if(!empty($query))
            <a href="{{ route('events') }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>

    @if(!empty($query))
    <p class="text-muted mb-3">Search results for: <strong>"{{ $query }}"</strong></p>
    @endif

    {{-- Upcoming Events --}}
    <div class="row g-4">
        @forelse($upcomingEvents as $event)
        <div class="col-lg-4 col-md-6">
            <div class="event-card">

                <div class="event-banner">
                    <div class="event-date-badge">
                        {{ $event->formatted_date }} | {{ $event->venue ?? 'TBA' }}
                    </div>
                    <div class="event-icon">📅</div>
                    <h3 class="event-title">{{ $event->title }}</h3>
                    <div class="event-org">{{ $event->org_name }}</div>
                </div>

                <div class="p-4 d-flex flex-column flex-grow-1">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge status-{{ strtolower($event->status) }}">
                            {{ strtoupper($event->status) }}
                        </span>
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn-like {{ $event->user_liked ? 'liked' : '' }}" onclick="toggleLike('{{ $event->event_id }}', this)" title="Like">
                                <i class="bi {{ $event->user_liked ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                <span class="like-count">{{ $event->likes_count ?? 0 }}</span>
                            </button>
                            <span class="small text-muted">
                                <i class="bi bi-people me-1"></i> {{ $event->rsvp_count }} RSVP'd
                            </span>
                        </div>
                    </div>

                    @if($event->description)
                    <p class="text-muted small mb-3">
                        {{ Str::limit($event->description, 120) }}
                    </p>
                    @endif

                    <div class="mt-auto">

                        <div class="d-flex flex-column gap-2 mb-3 pt-3 border-top">
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-calendar3"></i>
                                <span class="small text-secondary">{{ $event->formatted_full_date }}</span>
                            </div>

                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span class="small text-secondary">{{ $event->venue ?? 'Venue TBA' }}</span>
                            </div>

                            @if($event->event_duration)
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-clock"></i>
                                <span class="small text-secondary">{{ $event->event_duration }} hours</span>
                            </div>
                            @endif
                        </div>

                        @auth
                        @if(!$event->is_officer_of_org && !$event->is_adviser_of_org && Auth::user()->account_type !== 'Faculty')
                            @if($event->user_rsvp_status === 'RSVP')
                            <button class="btn btn-success w-100" disabled>
                                <i class="bi bi-check-circle me-2"></i> Already RSVP'd
                            </button>
                            @else
                            <button class="btn btn-primary w-100"
                                data-id="{{ $event->event_id }}"
                                data-title="{{ $event->title }}"
                                data-date="{{ $event->formatted_date }}"
                                data-venue="{{ $event->venue ?? 'TBA' }}"
                                data-org="{{ $event->org_name }}"
                                onclick="openRsvpModal(this)">
                                RSVP
                            </button>
                            @endif
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">Login to RSVP</a>
                        @endauth

                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                <p>No upcoming events at the moment.</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Past Events --}}
    @if(count($pastEvents) > 0)
    <div class="section-divider">
        <h2 class="section-subtitle">Past Events</h2>
    </div>

    <div class="row g-4">
        @foreach($pastEvents as $event)
        <div class="col-lg-4 col-md-6">
            <div class="event-card">
                <div class="event-banner">
                    <div class="event-date-badge">
                        {{ $event->formatted_date }} | {{ $event->venue ?? 'TBA' }}
                    </div>
                    <div class="event-icon">✅</div>
                    <h3 class="event-title">{{ $event->title }}</h3>
                    <div class="event-org">{{ $event->org_name }}</div>
                </div>

                <div class="p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge status-{{ strtolower($event->status) }}">
                            {{ strtoupper($event->status) }}
                        </span>
                        <span class="small text-muted">
                            <i class="bi bi-people me-1"></i> {{ $event->rsvp_count }} attended
                        </span>
                    </div>

                    @if($event->description)
                    <p class="text-muted small mb-3">
                        {{ Str::limit($event->description, 120) }}
                    </p>
                    @endif

                    <div class="mt-auto">
                        <div class="d-flex flex-column gap-2 mb-3 pt-3 border-top">
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-calendar3"></i>
                                <span class="small text-secondary">{{ $event->formatted_full_date }}</span>
                            </div>
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span class="small text-secondary">{{ $event->venue ?? 'Venue TBA' }}</span>
                            </div>
                        </div>

                        <button class="btn btn-secondary w-100" disabled>Event Ended</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- RSVP Modal --}}
<div class="modal fade" id="rsvpModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:none;">

            <div class="modal-header" style="border-bottom:2px solid #f0f0f0;">
                <h5 class="modal-title" style="color:var(--pup-maroon);font-weight:700;">
                    EVENT RSVP FORM
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <p class="text-muted mb-3" id="modalOrgName"></p>

                <div class="bg-light p-3 rounded mb-3">
                    <h6 class="fw-bold text-maroon" id="modalEventTitle"></h6>
                    <p class="mb-1"><strong>Date:</strong> <span id="modalEventDate"></span></p>
                    <p class="mb-0"><strong>Venue:</strong> <span id="modalEventPlace"></span></p>
                </div>

                <form id="rsvpForm">
                    <input type="hidden" id="eventIdInput" name="event_id">

                    <div class="form-group">
                        <label class="form-label">School Number</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->school_id ?? '' }}" readonly>
                    </div>

                    <div class="row g-3">
                        <div class="col">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control"
                                value="{{ $firstName ?? explode(' ', Auth::user()->full_name ?? '')[0] ?? '' }}" readonly>
                        </div>
                        <div class="col">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" value="{{ $middleName ?? '' }}" readonly>
                        </div>
                        <div class="col">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control"
                                value="{{ $lastName ?? explode(' ', Auth::user()->full_name ?? '')[count(explode(' ', Auth::user()->full_name ?? '')) - 1] ?? '' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email ?? '' }}" readonly>
                    </div>

                    <div class="certification-text">
                        I acknowledge that this RSVP is a confirmation of my attendance.
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        SUBMIT RSVP
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let rsvpModal;

    document.addEventListener('DOMContentLoaded', () => {
        rsvpModal = new bootstrap.Modal(document.getElementById('rsvpModal'));
    });

    function openRsvpModal(btn) {
        document.getElementById('eventIdInput').value = btn.dataset.id;
        document.getElementById('modalEventTitle').textContent = btn.dataset.title;
        document.getElementById('modalEventDate').textContent = btn.dataset.date;
        document.getElementById('modalEventPlace').textContent = btn.dataset.venue;
        document.getElementById('modalOrgName').textContent = btn.dataset.org;

        rsvpModal.show();
    }

    document.getElementById('rsvpForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const eventId = document.getElementById('eventIdInput').value;
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

        try {
            const response = await fetch(`/events/${eventId}/rsvp`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                rsvpModal.hide();
                location.reload();
            } else if (data.redirect) {
                alert(data.message);
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'Failed to RSVP');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        } catch (err) {
            alert('An error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });

    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            new bootstrap.Alert(alert).close();
        });
    }, 5000);

    async function toggleLike(eventId, btn) {
        @auth
        try {
            const response = await fetch(`/events/${eventId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                const icon = btn.querySelector('i');
                const countSpan = btn.querySelector('.like-count');

                if (data.liked) {
                    btn.classList.add('liked');
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                } else {
                    btn.classList.remove('liked');
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                }
                countSpan.textContent = data.likeCount;
            } else {
                alert(data.message || 'Failed to like event');
            }
        } catch (err) {
            alert('An error occurred. Please try again.');
        }
        @else
        alert('Please login to like events');
        window.location.href = '{{ route("login") }}';
        @endauth
    }
</script>

@endsection