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
        /*display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 3.2em;*/
    }

    .event-org {
        font-size: 12px;
        opacity: 0.85;
        font-weight: 500;
    }

    .badge.status-upcoming {
        background: #fff3cd;
        color: #856404;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 10px;
        font-weight: 700;
    }

    .badge.status-ended {
        background: #fee2e2;
        color: #991b1b;
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
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
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

    .btn-like-event {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-like-event:hover {
        background: white;
        color: #ff4d4d;
        transform: scale(1.1);
    }

    .btn-like-event.active {
        background: white;
        color: #ff4d4d;
    }

    .speaker-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        padding: 12px;
        background: #fcf6f6;
        border-radius: 12px;
        min-height: 65px;
        border: 1px solid #f0f0f0;
        width: 100%;
    }

    .speaker-avatar {
        width: 35px;
        height: 35px;
        background: var(--pup-maroon);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 40px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #999;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--pup-maroon);
        margin-bottom: 25px;
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
        font-size: 0.95rem;
        width: 100%;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
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
</style>

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

                    <button class="btn-like-event">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge status-upcoming">UPCOMING</span>
                        <span class="small text-muted"><i class="bi bi-people me-1"></i> 19 Liked</span>
                    </div>

                    <p class="text-muted small mb-3">
                        Building the web of tomorrow—one line of code at a time. From fundamentals to modern tools, this workshop empowers developers.
                    </p>

                    <div class="speaker-info">
                        <div class="speaker-avatar">JK</div>
                        <div>
                            <div class="small fw-bold" style="color: #333;">Mr. Josef Velayo</div>
                            <div class="text-muted" style="font-size: 10px;">Lead Speaker / GDG Lead</div>
                        </div>
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
                            <button class="btn btn-primary w-100" onclick="openModal('rsvpModal')">RSVP</button>
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

                    <button class="btn-like-event">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge status-ended">ENDED</span>
                        <span class="small text-muted"><i class="bi bi-people me-1"></i> 24 Liked</span>
                    </div>
                    <p class="text-muted small mb-3">
                        Are you ready to step out of your comfort zone? Join us for an exciting session where we explore new possibilities.
                    </p>

                    <div class="speaker-info">
                        <div class="speaker-avatar">KG</div>
                        <div>
                            <div class="small fw-bold" style="color: #333;">Ms. Keliya Gamayo</div>
                            <div class="text-muted" style="font-size: 10px;">Lead Speaker / IBITS Lead</div>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <div class="d-flex flex-column gap-2 mb-3 pt-3 border-top">
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-calendar3"></i>
                                <span class="small text-secondary">December 12, 2025</span>
                            </div>
                            <div class="detail-item d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span class="small text-secondary">PUP South Campus</span>
                            </div>
                        </div>
                        <button class="btn btn-secondary w-100" disabled>Event Ended</button>
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

                    <button class="btn-like-event">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge status-upcoming">UPCOMING</span>
                        <span class="small text-muted"><i class="bi bi-people me-1"></i> 53 Liked</span>
                    </div>

                    <p class="text-muted small mb-3">
                        Dive into the world of cloud computing with AWS. Learn the fundamentals of cloud infrastructure and scalable services.
                    </p>

                    <div class="speaker-info">
                        <div class="speaker-avatar">FC</div>
                        <div>
                            <div class="small fw-bold" style="color: #333;">Ms. Franzel Cayona</div>
                            <div class="text-muted" style="font-size: 10px;">Lead Speaker / AWS Lead</div>
                        </div>
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
                            <button class="btn btn-primary w-100" onclick="openModal('rsvpModal')">RSVP</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RSVP Modal -->
<div class="modal-overlay" id="rsvpModal">
    <div class="modal-content">
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

            <button type="submit" class="btn btn-primary w-100">
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