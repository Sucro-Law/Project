<div class="top-bar">
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
    <div class="welcome-text pt-3">
        @if(Auth::check())
        <h2>Welcome, {{ Auth::user()->school_id ?? 'SN ' . Auth::user()->id }}!</h2>
        <p>Polytechnic University of the Philippines</p>
        @else
        <h2>Welcome, Guest!</h2>
        <p>Polytechnic University of the Philippines</p>
        @endif
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
        @if(Auth::check())
        <div class="user-menu" id="userMenuTrigger">
            @php
            // Get individual name parts
            $fullNameParts = explode(' ', Auth::user()->full_name ?? '');

            if (count($fullNameParts) >= 3) {
            // Assume format: FirstName MiddleName LastName or FirstName SecondName MiddleName LastName
            $lastName = array_pop($fullNameParts); // Get last part as last name
            $middleName = array_pop($fullNameParts); // Get second to last as middle name
            $firstName = implode(' ', $fullNameParts); // Everything else is first name

            $middleInitial = strtoupper(substr($middleName, 0, 1)) . '.';
            $displayName = trim($firstName . ' ' . $middleInitial . ' ' . $lastName);
            } else {
            // Fallback to full name if format is unexpected
            $displayName = Auth::user()->full_name ?? 'User';
            }
            @endphp
            <span>{{ $displayName }}</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        @endif
    </div>
</div>


<div class="user-dropdown" id="userDropdown">
    <a href="{{ route('profile') }}" class="dropdown-item">Profile</a>
    <a href="{{ route('settings') }}" class="dropdown-item">Settings</a>
    <div class="dropdown-divider"></div>
    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
        @csrf
        <button type="submit" class="dropdown-item logout" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
            Logout
        </button>
    </form>
</div>

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




<script>
    document.addEventListener('DOMContentLoaded', () => {
        const trigger = document.getElementById('userMenuTrigger');
        const dropdown = document.getElementById('userDropdown');
        const notifBtn = document.querySelector('.notification-btn');
        const notifDropdown = document.getElementById('notificationDropdown');

        if (trigger && dropdown) {
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('show');
                if (notifDropdown) {
                    notifDropdown.classList.remove('show');
                }
            });
        }

        document.addEventListener('click', (e) => {
            if (dropdown && !trigger.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
            if (notifDropdown && !notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.classList.remove('show');
            }
        });
    });

    function toggleNotifications() {
        const notifDropdown = document.getElementById('notificationDropdown');
        const userDropdown = document.getElementById('userDropdown');

        if (notifDropdown) {
            notifDropdown.classList.toggle('show');
            if (userDropdown) {
                userDropdown.classList.remove('show');
            }
        }
    }

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.toggle('active');
        }
    }
</script>