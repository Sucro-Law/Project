<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Details - SOMSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --pup-maroon: #800000;
            --pup-gold: #FFD700;
            --pup-dark: #4a0000;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        /* Top Navigation */
        .top-nav {
            background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 0;
            border-bottom: 2px solid transparent;
        }

        .nav-links a:hover,
        .nav-links a.active {
            border-bottom-color: var(--pup-gold);
        }

        .search-bar {
            position: relative;
            width: 300px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 40px 10px 15px;
            border: none;
            border-radius: 25px;
            background: rgba(255,255,255,0.2);
            color: white;
            transition: all 0.3s ease;
        }

        .search-bar input::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .search-bar input:focus {
            outline: none;
            background: rgba(255,255,255,0.3);
        }

        .search-bar i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
        }

        /* Container */
        .container-custom {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        /* Organization Header */
        .org-header-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .org-header-content {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 30px;
            align-items: start;
        }

        .org-logo-large {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: bold;
        }

        .org-main-info h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pup-maroon);
            margin-bottom: 10px;
        }

        .org-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #d4edda;
            color: #155724;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .org-meta-info {
            display: flex;
            gap: 30px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 0.95rem;
        }

        .meta-item i {
            color: var(--pup-maroon);
        }

        .org-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn-primary-custom {
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

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
            color: white;
        }

        .btn-secondary-custom {
            background: white;
            color: var(--pup-maroon);
            border: 2px solid var(--pup-maroon);
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .btn-secondary-custom:hover {
            background: var(--pup-maroon);
            color: white;
        }

        /* Tabs */
        .custom-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 15px 25px;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }

        .tab-btn.active {
            color: var(--pup-maroon);
            border-bottom-color: var(--pup-maroon);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* About Section */
        .about-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--pup-maroon);
            margin-bottom: 20px;
        }

        .adviser-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .adviser-avatar {
            width: 60px;
            height: 60px;
            background: var(--pup-maroon);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .adviser-details h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .adviser-details p {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }

        .description-text {
            color: #555;
            line-height: 1.8;
            font-size: 1rem;
        }

        /* Officers & Members */
        .officers-grid,
        .members-list {
            display: grid;
            gap: 15px;
        }

        .officer-item {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 15px;
            padding: 15px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .officer-role {
            font-weight: 600;
            color: var(--pup-maroon);
        }

        .officer-name {
            color: #333;
        }

        .members-list {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }

        .member-item {
            padding: 12px 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            color: #333;
        }

        /* Events Section */
        .events-list {
            display: grid;
            gap: 20px;
        }

        .event-item {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .event-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }

        .event-status {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .status-upcoming {
            background: #fff3cd;
            color: #856404;
        }

        .status-ended {
            background: #d1ecf1;
            color: #0c5460;
        }

        .event-item h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 12px;
        }

        .event-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .event-details {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .event-detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 0.9rem;
        }

        .event-detail-item i {
            color: var(--pup-maroon);
        }

        .btn-rsvp {
            background: var(--pup-maroon);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-rsvp:hover {
            background: var(--pup-dark);
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
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

        @media (max-width: 992px) {
            .org-header-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .org-logo-large {
                margin: 0 auto;
            }

            .org-actions {
                flex-direction: row;
            }

            .search-bar {
                width: 100%;
            }

            .officer-item {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .top-nav {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                width: 100%;
                justify-content: space-around;
            }

            .custom-tabs {
                overflow-x: auto;
            }

            .org-meta-info {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <div class="top-nav">
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#" class="active">Organization</a>
            <a href="#">Events</a>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Search">
            <i class="bi bi-search"></i>
        </div>
    </div>

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

        // Form submissions
        document.getElementById('membershipForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Application submitted successfully!');

            closeModal('membershipModal');
        });     

        </script>
</body>
</html>