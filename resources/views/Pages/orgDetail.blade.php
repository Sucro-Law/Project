@extends('layout.main')

@push('styles')
@vite(['resources/css/orgdesc.css'])
@endpush

@section('content')

<div class="container-custom">
    <!-- Organization Header -->
    <div class="org-header-card">
        <div class="org-header-content">
            <div class="org-logo-large">GDG</div>

            <div class="org-main-info">
                <h1>Google Developer Groups on Campus – PUP</h1>
                <div>
                    <span class="org-status-badge">
                        <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i>
                        ACTIVE
                    </span>
                </div>
                <div class="org-meta-info">
                    <div class="meta-item">
                        <i class="bi bi-calendar3"></i>
                        Established: 2018
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-people-fill"></i>
                        23 Members
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-person-badge"></i>
                        8 Officers
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-building"></i>
                        Institute of Bachelors in Information Technology Studies
                    </div>
                </div>
            </div>

            <div class="org-actions">
                @if($role === 'officer' || $role === 'adviser')
                <button class="btn-add-member" onclick="openModal('memberAdmissionModal')">
                    <i class="bi bi-person-plus"></i> ADD MEMBER
                </button>
                @else
                <button class="btn-primary-custom" onclick="openModal('membershipModal')">
                    MEMBERSHIP FORM
                </button>
                @endif
                <a href="#" class="btn-secondary-custom">
                    <i class="bi bi-share me-1"></i> Share
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="custom-tabs">
        <div class="tabs-left">
            <button class="tab-btn active" onclick="showTab('about')">About us</button>
            <button class="tab-btn" onclick="showTab('officers')">Officers</button>
            <button class="tab-btn" onclick="showTab('members')">Members</button>
            <button class="tab-btn" onclick="showTab('alumni')">Alumni</button>
            <button class="tab-btn" onclick="showTab('events')">Events</button>
        </div>
        <div class="tabs-right">
            @if($role === 'officer' || $role === 'adviser')
            <button class="tab-btn" onclick="openModal('pendingMembers')">
                <span class="pending-badge" title="Pending member requests">
                    <i class="bi bi-person-plus-fill"></i>
                    <span class="badge-count">3</span>
            </button>
            <button class="tab-btn" onclick="openModal('pendingEvents')">
                <span class="pending-badge" title="Pending event approvals">
                    <i class="bi bi-clock-history"></i>
                    <span class="badge-count">2</span>
            </button>
            @endif
        </div>

    </div>

    <!-- About Tab -->
    <div id="about" class="tab-content active">
        <div class="about-section">
            <div class="section-title">About us!</div>

            <div class="adviser-info">
                <div class="adviser-avatar">JV</div>
                <div class="adviser-details">
                    <h4>Josef Karol A. Velayo</h4>
                    <p>Organization Adviser</p>
                </div>
            </div>

            <p class="description-text">
                An organization is a group of people who work together, like a neighborhood
                association, a charity, a union, or a corporation. You can use the word organization
                to refer to group or business, or to the act of forming or establishing something.
            </p>
        </div>
    </div>

    <!-- Officers Tab -->
    <div id="officers" class="tab-content">
        <div class="about-section">
            <div class="section-title">Officers (8)</div>
            <div class="officers-grid">
                <div class="officer-item">
                    <div class="officer-role">President</div>
                    <div class="officer-name">John Evans Guttierez</div>
                </div>
                <div class="officer-item">
                    <div class="officer-role">Vice President</div>
                    <div class="officer-name">Francheska Ramirez</div>
                </div>
                <div class="officer-item">
                    <div class="officer-role">Secretary</div>
                    <div class="officer-name">Franzel Cayona</div>
                </div>
                <div class="officer-item">
                    <div class="officer-role">Treasurer</div>
                    <div class="officer-name">Ken John Vianney Mondragon</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Members Tab -->
    <div id="members" class="tab-content">
        <div class="about-section">
            <div class="section-title">Members (23)</div>
            <div class="members-list">
                <div class="member-item">1. Cayona, Chrisel Franzel</div>
                <div class="member-item">2. Mondragon, Ken John Vianney</div>
                <div class="member-item">3. Member Name</div>
                <div class="member-item">4. Member Name</div>
                <div class="member-item">5. Member Name</div>
                <div class="member-item">6. Member Name</div>
            </div>
        </div>
    </div>

    <div id="alumni" class="tab-content">
        <div class="about-section">
            <div class="section-title">Alumni (50)</div>
            <div class="members-list">
                <div class="member-item">1. Cayona, Chrisel Franzel</div>
                <div class="member-item">2. Mondragon, Ken John Vianney</div>
                <div class="member-item">3. Member Name</div>
                <div class="member-item">4. Member Name</div>
                <div class="member-item">5. Member Name</div>
                <div class="member-item">6. Member Name</div>
            </div>
        </div>
    </div>


    <!-- Events Tab -->
    <div id="events" class="tab-content">
        @if($role === 'officer' || $role === 'adviser')
        <div class="create-event-trigger" onclick="openModal('eventPostingModal')">
            <div class="trigger-avatar">
                <div class="org-logo-small me-2">GDG</div>
            </div>
            <div class="trigger-input">Create Event</div>
        </div>
        @endif

        <div class="events-list">
            <div class="section-title">Events</div>
            <div class="events-list">
                <div class="event-item">
                    <span class="event-status status-upcoming">UPCOMING</span>
                    <h3>2026: Web Development Workshop</h3>
                    <p class="event-description">
                        Building the web of tomorrow—one line of code at a time. 💻<br>
                        From fundamentals to modern tools, this workshop empowers aspiring
                        developers to design, build, and deploy impactful web applications. 🚀
                    </p>
                    <div class="event-details">
                        <div class="event-detail-item">
                            <i class="bi bi-calendar-check"></i>
                            Date: 01/18/26
                        </div>
                        <div class="event-detail-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            Event's Place: PUP South
                        </div>
                        <div class="event-detail-item">
                            <i class="bi bi-person-circle"></i>
                            Author: Mr. Adviser
                        </div>
                        <div class="event-detail-item">
                            <i class="bi bi-heart-fill"></i>
                            19 Likes
                        </div>
                    </div>
                    <div class="event-action-group">
                        @if($role === 'officer' || $role === 'adviser')
                        <button class="btn-view-attendees" title="See Attendees" onclick="openModal('attendeesModal')">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        @endif
                        <button class="btn-rsvp" onclick="openModal('rsvpModal')">RSVP</button>
                    </div>
                </div>

                <div class="event-item">
                    <span class="event-status status-ended">ENDED</span>
                    <h3>2025: Web Development Workshop</h3>
                    <p class="event-description">
                        Building the web of tomorrow—one line of code at a time. 💻
                    </p>
                    <div class="event-details">
                        <div class="event-detail-item">
                            <i class="bi bi-calendar-check"></i>
                            Date: 12/15/25
                        </div>
                        <div class="event-detail-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            Event's Place: PUP Main
                        </div>
                        <div class="event-detail-item">
                            <i class="bi bi-person-circle"></i>
                            Author: Mr. Adviser
                        </div>
                        <div class="event-detail-item">
                            <i class="bi bi-heart-fill"></i>
                            24 Likes
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Attendees Modal -->
    <div class="modal-overlay" id="attendeesModal">
        <div class="modal-content" style="padding: 0; width: 400px; border: 3px solid #500000; background: white; border-radius: 8px;">
            <div style="background: #500000; color: white; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-weight: bold;">ATTENDEES</h3>
                <button class="modal-close" style="color: white; position: static;" onclick="closeModal('attendeesModal')">X</button>
            </div>
            <div class="attendees-list-container">
                @for ($i = 1; $i <= 10; $i++)
                    <div class="attendee-row">{{ $i }}. Member {{ $i }}</div>
            @endfor
        </div>
    </div>
</div>

<!-- Member Admission Modal -->
<div class="modal-overlay" id="memberAdmissionModal">
    <div class="modal-content" style="padding: 0; max-width: 700px; border-radius: 8px;">
        <div class="admission-header">
            <h4 style="margin: 0;">Google Developer Groups on Campus – PUP</h4>
            <button class="modal-close" style="color: white; top: 10px;" onclick="closeModal('memberAdmissionModal')">X</button>
        </div>
        <div class="admission-body text-center">
            <h2 class="admission-title">MEMBER ADMISSION</h2>

            <form>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="School Number">
                    </div>
                    <div class="col-md-6">
                        <select class="form-select">
                            <option>MEMBER/OFFICER</option>
                            <option>Member</option>
                            <option>Officer</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><input type="text" class="form-control" placeholder="First Name"></div>
                    <div class="col-md-4"><input type="text" class="form-control" placeholder="Middle Name"></div>
                    <div class="col-md-4"><input type="text" class="form-control" placeholder="Last Name"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6"><input type="email" class="form-control" placeholder="Email@gmail.com"></div>
                    <div class="col-md-6"><input type="text" class="form-control" placeholder="Role (IF OFFICER)"></div>
                </div>
                <button type="button" class="btn-primary-custom" style="width: 100%; background: #800000; height: 50px;">ADD MEMBER</button>
            </form>
        </div>
    </div>
</div>



<!-- Event Posting Modal -->
<div class="modal-overlay" id="eventPostingModal">
    <div class="modal-content-event">
        <button class="modal-close" onclick="closeModal('eventPostingModal')">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="event-posting-title">EVENT POSTING</h2>

        <form id="eventPostingForm">
            <div class="posting-grid">
                <div class="full-width">
                    <input type="text" class="posting-input" placeholder="Title" required>
                </div>

                <div class="posting-left">
                    <div class="image-upload-box">
                        <label for="eventImage" class="upload-label">
                            <i class="bi bi-plus-lg"></i>
                            <span>Insert Image</span>
                        </label>
                        <input type="file" id="eventImage" hidden>
                    </div>

                    <div class="details-section">
                        <label class="mb-1">Details:</label>
                        <input type="date" class="posting-input-small mb-2" placeholder="Date">
                        <input type="text" class="posting-input-small" placeholder="Event's Place">
                    </div>
                </div>

                <div class="posting-right">
                    <textarea class="posting-textarea" placeholder="Description"></textarea>
                </div>
            </div>

            <div class="posting-footer">
                <button type="submit" class="btn-submit-event">SUBMIT</button>
            </div>
        </form>
    </div>
</div>

<!-- Pending Members Modal -->
<div class="modal-overlay" id="pendingMembers">
    <div class="modal-content-pending">
        <button class="modal-close" onclick="closeModal('pendingMembers')">
            <i class="bi bi-x-lg"></i>
        </button>
        <h2 class="modal-title-pending">Pending Members</h2>

        <div class="accordion" id="pendingMembersAccordion">
            <!-- Member 1 -->
            <div class="accordion-item member-accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#member1">
                        <span class="member-header-info">
                            <strong>Name:</strong> Juan Dela Cruz
                            <span class="divider">|</span>
                            <strong>School ID:</strong> SN-12456784
                        </span>
                    </button>
                </h2>
                <div id="member1" class="accordion-collapse collapse"
                    data-bs-parent="#pendingMembersAccordion">
                    <div class="accordion-body">
                        <div class="member-details-container">
                            <div class="member-info-box">
                                <div class="info-row">
                                    <strong>Full Name:</strong>
                                    <span class="text-maroon">Juan Dela Cruz</span>
                                </div>
                                <div class="info-row">
                                    <strong>School ID:</strong>
                                    <span class="text-maroon">SN-12456784</span>
                                </div>
                                <div class="info-row">
                                    <strong>Role:</strong> Member
                                </div>
                                <div class="info-row">
                                    <strong>Position:</strong> Member
                                </div>
                            </div>
                            <div class="member-action-buttons">
                                <button class="btn-accept">ACCEPT</button>
                                <button class="btn-decline">DECLINE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member 2 -->
            <div class="accordion-item member-accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#member2">
                        <span class="member-header-info">
                            <strong>Name:</strong> Maria Santos
                            <span class="divider">|</span>
                            <strong>School ID:</strong> SN-12456785
                        </span>
                    </button>
                </h2>
                <div id="member2" class="accordion-collapse collapse"
                    data-bs-parent="#pendingMembersAccordion">
                    <div class="accordion-body">
                        <div class="member-details-container">
                            <div class="member-info-box">
                                <div class="info-row">
                                    <strong>Full Name:</strong>
                                    <span class="text-maroon">Maria Santos</span>
                                </div>
                                <div class="info-row">
                                    <strong>School ID:</strong>
                                    <span class="text-maroon">SN-12456785</span>
                                </div>
                                <div class="info-row">
                                    <strong>Role:</strong> Officer
                                </div>
                                <div class="info-row">
                                    <strong>Position:</strong> Secretary
                                </div>
                            </div>
                            <div class="member-action-buttons">
                                <button class="btn-accept">ACCEPT</button>
                                <button class="btn-decline">DECLINE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member 3 -->
            <div class="accordion-item member-accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#member3">
                        <span class="member-header-info">
                            <strong>Name:</strong> Pedro Reyes
                            <span class="divider">|</span>
                            <strong>School ID:</strong> SN-12456786
                        </span>
                    </button>
                </h2>
                <div id="member3" class="accordion-collapse collapse"
                    data-bs-parent="#pendingMembersAccordion">
                    <div class="accordion-body">
                        <div class="member-details-container">
                            <div class="member-info-box">
                                <div class="info-row">
                                    <strong>Full Name:</strong>
                                    <span class="text-maroon">Pedro Reyes</span>
                                </div>
                                <div class="info-row">
                                    <strong>School ID:</strong>
                                    <span class="text-maroon">SN-12456786</span>
                                </div>
                                <div class="info-row">
                                    <strong>Role:</strong> Member
                                </div>
                                <div class="info-row">
                                    <strong>Position:</strong> Member
                                </div>
                            </div>
                            <div class="member-action-buttons">
                                <button class="btn-accept">ACCEPT</button>
                                <button class="btn-decline">DECLINE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Events Modal -->
<div class="modal-overlay" id="pendingEvents">
    <div class="modal-content-pending">
        <button class="modal-close" onclick="closeModal('pendingEvents')">
            <i class="bi bi-x-lg"></i>
        </button>
        <h2 class="modal-title-pending">Pending Events</h2>

        <div class="accordion" id="pendingEventsAccordion">

            <!-- Event 1 -->
            <div class="accordion-item event-accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#event1">
                        <div class="event-header-info">
                            <strong>Title:</strong> <span class="fw-normal">2026: AHHH EHHHH</span><br>
                            <small class="text-muted">Submitted by: Mommy Oni</small>
                        </div>
                    </button>
                </h2>
                <div id="event1" class="accordion-collapse collapse"
                    data-bs-parent="#pendingEventsAccordion">
                    <div class="accordion-body">
                        <div class="event-details-container">
                            <input type="text" class="event-title-input"
                                value="2026: AHHH EHHHH" readonly>

                            <div class="event-content-row">
                                <div class="event-visual-column">
                                    <div class="event-image-preview">
                                        <img src="{{ asset('image/computer.jpg') }}" alt="Event Image">
                                    </div>
                                    <div class="event-details-inputs">
                                        <label class="input-label">Details:</label>
                                        <input type="text" class="detail-input" value="02/15/26" readonly>
                                        <input type="text" class="detail-input" value="PUP Main" readonly>
                                    </div>
                                </div>

                                <div class="event-description-column">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                </div>
                            </div>

                            <p class="waiting-verification">-- Waiting for Verification --</p>

                            @if($role === 'adviser')
                            <div class="event-action-buttons">
                                <button class="btn-approve">APPROVE</button>
                                <button class="btn-reject">REJECT</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event 2 -->
            <div class="accordion-item event-accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button"
                        data-bs-toggle="collapse" data-bs-target="#event2">
                        <div class="event-header-info">
                            <strong>Title:</strong> <span class="fw-normal">2026: Web Development Workshop</span><br>
                            <small class="text-muted">Submitted by: Mr. Treasurer</small>
                        </div>
                    </button>
                </h2>
                <div id="event2" class="accordion-collapse collapse show"
                    data-bs-parent="#pendingEventsAccordion">
                    <div class="accordion-body">
                        <div class="event-details-container">
                            <input type="text" class="event-title-input"
                                value="2026: Web Development Workshop" readonly>

                            <div class="event-content-row">
                                <div class="event-visual-column">
                                    <div class="event-image-preview">
                                        <img src="{{ asset('image/computer.jpg') }}" alt="Workshop Image">
                                    </div>
                                    <div class="event-details-inputs">
                                        <label class="input-label">Details:</label>
                                        <input type="text" class="detail-input" value="01/18/26" readonly>
                                        <input type="text" class="detail-input" value="PUP South" readonly>
                                    </div>
                                </div>

                                <div class="event-description-column">
                                    <p>Building the web of tomorrow—one line of code at a time. 💻</p>
                                    <p>From fundamentals to modern tools, this workshop empowers aspiring
                                        developers to design, build, and deploy impactful web applications. 🚀</p>
                                </div>
                            </div>

                            <p class="waiting-verification">-- Waiting for Verification --</p>

                            @if($role === 'adviser')
                            <div class="event-action-buttons">
                                <button class="btn-approve">APPROVE</button>
                                <button class="btn-reject">REJECT</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Membership Modal -->
<div class="modal-overlay" id="membershipModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('membershipModal')">
            <i class="bi bi-x-lg"></i>
        </button>
        <h2 class="modal-title">MEMBERSHIP FORM</h2>
        <p style="color: #666; margin-bottom: 25px;">Google Developer Groups on Campus – PUP</p>

        <form id="membershipForm">
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

            <div class="form-group">
                <label class="form-label">Position</label>
                <select class="form-select" required>
                    <option value="">MEMBER/OFFICER</option>
                    <option value="member">Member</option>
                    <option value="officer">Officer</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Role (IF OFFICER)</label>
                <input type="text" class="form-control" placeholder="e.g., Secretary, Treasurer">
            </div>

            <div class="certification-text">
                I hereby certify that the information provided in this form is true, complete,
                and accurate to the best of my knowledge. I understand that any misrepresentation
                or material omission made on this form may result in the rejection of my application.
            </div>

            <button type="submit" class="btn-primary-custom">
                SUBMIT APPLICATION
            </button>
        </form>
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
                <h4 style="color: var(--pup-maroon); margin-bottom: 10px;">Event: 2026: Web Development Workshop</h4>
                <p style="margin: 0; color: #666;"><strong>Details:</strong></p>
                <p style="margin: 5px 0; color: #666;">Date: 01/18/26</p>
                <p style="margin: 5px 0; color: #666;">Event's Place: PUP South</p>
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

            <button type="submit" class="btn-primary-custom" style="width: 100%;">
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