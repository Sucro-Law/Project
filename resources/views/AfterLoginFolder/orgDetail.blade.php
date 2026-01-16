@extends('layout.main')

@section('title', 'Organization Details')

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
                        <i class="bi bi-circle-fill" style="font-size: 0.6rem;"></i>
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
                <button class="btn-primary-custom" onclick="openModal('membershipModal')">
                    MEMBERSHIP FORM
                </button>
                <a href="#" class="btn-secondary-custom">
                    <i class="bi bi-share"></i> Share
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="custom-tabs">
        <button class="tab-btn active" onclick="showTab('about')">About us</button>
        <button class="tab-btn" onclick="showTab('officers')">Officers</button>
        <button class="tab-btn" onclick="showTab('members')">Members</button>
        <button class="tab-btn" onclick="showTab('events')">Events</button>
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

    <!-- Events Tab -->
    <div id="events" class="tab-content">
        <div class="about-section">
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
                    <div style="margin-top: 15px;">
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
                    <input type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" required>
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

            <button type="submit" class="btn-primary-custom" style="width: 100%;">
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
                    <input type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" required>
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