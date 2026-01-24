@extends('layout.main')

@section('content')

<style>
    .container-custom {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-title {
        color: maroon !important;
        font-size: 35px;
        font-weight: 700;
        margin-bottom: 30px;
    }

    .event-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: none;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .event-banner {
        background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
        padding: 35px 25px;
        color: white;
        position: relative;
        min-height: 230px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    .event-date-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: white;
        color: var(--pup-maroon);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .event-icon {
        font-size: 40px;
        margin-bottom: 12px;
    }

    .event-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #ffffff !important;
        margin-bottom: 12px;
        line-height: 1.3;
        letter-spacing: 0.5px;
    }

    .event-org {
        font-size: 12px;
        opacity: 0.85;
        font-weight: 500;
    }

    .badge.status-upcoming {
        background: #fff3cd;
        color: #856404;
    }

    .badge.status-pending {
        background: #cfe2ff;
        color: #084298;
    }

    .badge.status-done {
        background: #d1e7dd;
        color: #0f5132;
    }

    .badge.status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge[class*="status-"] {
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 10px;
        font-weight: 700;
    }

    .detail-item i {
        color: var(--pup-maroon);
        width: 20px;
        font-size: 16px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
        color: white;
    }

    .btn-secondary {
        background: #f3f4f6;
        border: none;
        color: #6b7280;
        padding: 12px;
        border-radius: 25px;
        font-weight: 600;
    }

    .btn-success {
        background: #d1e7dd;
        color: #0f5132;
        border: none;
        padding: 12px;
        border-radius: 25px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
        font-size: 0.9rem;
    }

    .form-control,
    .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--pup-maroon);
        box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1);
    }

    .certification-text {
        font-size: 0.85rem;
        color: #666;
        line-height: 1.6;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .section-divider {
        margin: 50px 0 30px;
        border-top: 2px solid #f0f0f0;
        padding-top: 30px;
    }

    .section-subtitle {
        color: #666;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
</style>

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
                        <span class="small text-muted">
                            <i class="bi bi-people me-1"></i> {{ $event->rsvp_count }} RSVP'd
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

                            @if($event->event_duration)
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-clock"></i>
                                <span class="small text-secondary">{{ $event->event_duration }} hours</span>
                            </div>
                            @endif
                        </div>

                        @auth
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
</script>

@endsection