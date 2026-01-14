<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Organization Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --pup-maroon: #800000;
            --pup-gold: #FFD700;
            --pup-dark: #4a0000;
            --sidebar-width: 280px;
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

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            color: white;
            padding: 20px;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
        }

        .pup-logo {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .pup-logo span {
            font-size: 1.5rem;
            color: var(--pup-maroon);
            font-weight: bold;
        }

        .sidebar-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .user-profile {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 1.5rem;
            color: var(--pup-maroon);
            font-weight: bold;
        }

        .user-name {
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 5px;
        }

        .user-org-list {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-org-title {
            font-size: 0.75rem;
            opacity: 0.8;
            margin-bottom: 8px;
        }

        .org-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-block;
            margin: 3px;
        }

        .org-info {
            font-size: 0.75rem;
            opacity: 0.8;
            margin-top: 8px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
        }

        .nav-link i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Top Bar */
        .top-bar {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text h2 {
            color: var(--pup-maroon);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .welcome-text p {
            color: #666;
            font-size: 0.9rem;
        }

        .top-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            padding: 10px 20px 10px 45px;
            width: 300px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--pup-maroon);
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .notification-btn {
            position: relative;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            border-color: var(--pup-maroon);
            background: #f8f8f8;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        .section-title {
            color: var(--pup-maroon);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        /* Event Cards */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .event-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .event-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            position: relative;
        }

        .event-date-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: white;
            color: var(--pup-maroon);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .event-content {
            padding: 20px;
        }

        .event-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .event-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .event-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .event-author {
            font-size: 0.85rem;
            color: #666;
        }

        .event-likes {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--pup-maroon);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .view-more-btn {
            text-align: center;
            margin-top: 20px;
        }

        .btn-view-more {
            background: white;
            color: var(--pup-maroon);
            border: 2px solid var(--pup-maroon);
            padding: 12px 40px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-view-more:hover {
            background: var(--pup-maroon);
            color: white;
        }

        /* Organizations Section */
        .org-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .org-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .org-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .org-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .org-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .org-info-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 3px;
        }

        .org-status {
            font-size: 0.75rem;
            color: #28a745;
            font-weight: 600;
        }

        .org-description {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .org-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .org-members {
            font-size: 0.85rem;
            color: #666;
        }

        .btn-view-org {
            background: var(--pup-maroon);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-view-org:hover {
            background: var(--pup-dark);
            color: white;
        }

        /* Notification Dropdown */
        .notification-dropdown {
            position: absolute;
            top: 60px;
            right: 100px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-header {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            color: var(--pup-maroon);
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.3s ease;
        }

        .notification-item:hover {
            background: #f8f8f8;
        }

        .notification-item.unread {
            background: #fff3cd;
        }

        .notification-text {
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 5px;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #999;
        }

        @media (max-width: 1200px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .search-box input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                gap: 15px;
            }

            .top-actions {
                width: 100%;
                justify-content: space-between;
            }

            .search-box input {
                width: 100%;
            }

            .events-grid,
            .org-cards {
                grid-template-columns: 1fr;
            }
        }

        .mobile-menu-btn {
            display: none;
            background: var(--pup-maroon);
            color: white;
            border: none;
            border-radius: 8px;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 15px;
        }

        @media (max-width: 1200px) {
            .mobile-menu-btn {
                display: flex;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="pup-logo">
                <span>PUP</span>
            </div>
            <div class="sidebar-title">SOM System</div>
        </div>

        <div class="user-profile">
            <div class="user-avatar">FL</div>
            <div class="user-name">FN MN. LN</div>

            <div class="user-org-list">
                <div class="user-org-title">Organization/s Joined:</div>
                <div>
                    <span class="org-badge">Google Developer Groups on Campus – PUP</span>
                    <span class="org-badge">Amazon Web Services – PUP</span>
                </div>
                <div class="org-info">
                    <div>Position: Member</div>
                    <div>Academic Year: 2026-2027</div>
                    <div>Date Joined: January 7, 2026</div>
                </div>
            </div>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="bi bi-house-door-fill"></i>
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-building"></i>
                    Organization
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-calendar-event"></i>
                    Events
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-gear"></i>
                    Settings
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-box-arrow-right"></i>
                    Log out
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <button class="mobile-menu-btn" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="welcome-text">
                <h2>Welcome, FN!</h2>
                <p>Orgy – Novaliches</p>
            </div>
            <div class="top-actions">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search">
                </div>
                <div class="notification-btn" onclick="toggleNotifications()">
                    <i class="bi bi-bell-fill"></i>
                    <span class="notification-badge">2</span>
                </div>
                <div class="user-menu">
                    <span>FN MN. LN</span>
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>
        </div>

        <!-- Notification Dropdown -->
        <div class="notification-dropdown" id="notificationDropdown">
            <div class="notification-header">
                Notifications
            </div>
            <div class="notification-item unread">
                <div class="notification-text">Your application has been submitted. Please wait for verification. View your application form here</div>
                <div class="notification-time">1hr</div>
            </div>
            <div class="notification-item unread">
                <div class="notification-text">Welcome to Google Developers Group on Campus PUP!</div>
                <div class="notification-time">7d</div>
            </div>
        </div>

        <!-- Content Area -->
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
                        <a href="#" class="btn-view-org">View Organization</a>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        function toggleNotifications() {
            document.getElementById('notificationDropdown').classList.toggle('show');
        }

        // Close notification dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const notificationBtn = document.querySelector('.notification-btn');

            if (!dropdown.contains(event.target) && !notificationBtn.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');

            if (window.innerWidth <= 1200) {
                if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
</body>

</html>