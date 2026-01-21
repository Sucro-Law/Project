@extends('layout.main')

@push('styles')
@vite(['resources/css/pages.css'])
@endpush

@section('content')

<div class="container-custom">
    <h1 class="page-title">Events</h1>

    <div class="row g-4">
        <div class="col-lg-4 col-md-6">
            <div class="event-card">
                <div class="event-banner">
                    <div class="event-date-badge">01/20/26 | Main Campus</div>
                    <div class="event-icon">🚀</div>
                    <h3 class="event-title mb-2">2026: Web Development Workshop</h3>
                    <div class="event-org">Google Developer Groups on Campus – PUP</div>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <div>
                        <span class="badge status-upcoming mb-3">UPCOMING</span>
                        <p class="text-muted small mb-3">
                            Building the web of tomorrow—one line of code at a time. From fundamentals to modern tools, this workshop empowers developers.
                        </p>
                    </div>

                    <div class="mt-auto">
                        <div class="d-flex flex-column gap-2 mb-3 pt-3 border-top">
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-calendar3"></i>
                                <span class="small text-secondary">January 18, 2026</span>
                            </div>
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span class="small text-secondary">PUP South</span>
                            </div>
                        </div>
                        <div style="margin-top: 15px;">
                            <button class="btn-primary w-100" onclick="openModal('rsvpModal')">RSVP</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="event-card">
                <div class="event-banner">
                    <div class="event-date-badge">01/18/26 | PUP South</div>
                    <div class="event-icon">💡</div>
                    <h3 class="event-title mb-2">This is how you do it!</h3>
                    <div class="event-org">Institute of Bachelors in IT Studies</div>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <div>
                        <span class="badge status-ended mb-3">ENDED</span>
                        <p class="text-muted small mb-3">
                            Are you ready to step out of your comfort zone? Join us for an exciting session where we explore new possibilities.
                        </p>
                    </div>

                    <div class="mt-auto">
                        <div class="d-flex flex-column gap-2 mb-3 pt-3 border-top">
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-calendar3"></i>
                                <span class="small text-secondary">January 18, 2026</span>
                            </div>
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span class="small text-secondary">PUP South Campus</span>
                            </div>
                        </div>
                        <button class="btn-secondary w-100" disabled>Event Ended</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="event-card">
                <div class="event-banner">
                    <div class="event-date-badge">01/25/26 | Main Campus</div>
                    <div class="event-icon">☁️</div>
                    <h3 class="event-title mb-2">AWS Cloud Fundamentals</h3>
                    <div class="event-org">Amazon Web Services – PUP</div>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <div>
                        <span class="badge status-upcoming mb-3">UPCOMING</span>
                        <p class="text-muted small mb-3">
                            Dive into the world of cloud computing with AWS. Learn the fundamentals of cloud infrastructure and scalable services.
                        </p>
                    </div>

                    <div class="mt-auto">
                        <div class="d-flex flex-column gap-2 mb-3 pt-3 border-top">
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-calendar3"></i>
                                <span class="small text-secondary">January 25, 2026</span>
                            </div>
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span class="small text-secondary">PUP Main Campus</span>
                            </div>
                        </div>
                        <div style="margin-top: 15px;">
                            <button class="btn-primary w-100" onclick="openModal('rsvpModal')">RSVP</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RSVP Modal -->
<div class="modal-overlay" id="rsvpModal">
    <div class="org-modal-content">
        <button class="modal-close" onclick="closeModal('rsvpModal')">
            <i class="bi bi-x-lg"></i>
        </button>
        <h2 class="modal-title">EVENT RSVP FORM</h2>
        <p style="color: #666; margin-bottom: 25px;">Google Developer Groups on Campus – PUP</p>

        <form id="rsvpForm">
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h4 style="color: var(--pup-maroon); margin-bottom: 10px;" id="modalEventTitle">Event: 2026: Web Development Workshop</h4>
                <p style="margin: 0; color: #666;"><strong>Details:</strong></p>
                <p style="margin: 5px 0; color: #666;" id="modalEventDate">Date: 01/18/26</p>
                <p style="margin: 5px 0; color: #666;" id="modalEventPlace">Event's Place: PUP South</p>
            </div>

            <div class="form-group">
                <label class="form-label">School Number</label>
                <input type="text" class="form-control" placeholder="SN-XXXXXXXX" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" placeholder="Middle Name">
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" placeholder="Last Name" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" placeholder="Email@gmail.com" required>
            </div>

            <div class="certification-text">
                I acknowledge that this RSVP is a confirmation of my attendance.
            </div>

            <button type="submit" class="btn-primary w-100">
                SUBMIT RSVP
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });

        // Remove active from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Show selected tab
        document.getElementById(tabName).classList.add('active');

        // Add active to clicked button
        event.target.classList.add('active');
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
</script>

@endsection