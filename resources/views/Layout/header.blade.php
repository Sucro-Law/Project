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
            <span class="notification-badge" id="notifBadge" style="display: none;">0</span>
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
        <span>Notifications</span>
        <button onclick="markAllNotificationsRead()" class="mark-all-read-btn">Mark all read</button>
    </div>
    <div id="notificationList">
        <div class="notification-item" style="text-align: center; color: #666;">Loading...</div>
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

        loadNotifications();
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

    async function loadNotifications() {
        try {
            const response = await fetch('/notifications');
            const data = await response.json();

            const badge = document.getElementById('notifBadge');
            if (data.unread_count > 0) {
                badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }

            const list = document.getElementById('notificationList');
            if (data.notifications.length === 0) {
                list.innerHTML = '<div class="notification-item" style="text-align: center; color: #666;">No notifications</div>';
                return;
            }

            list.innerHTML = data.notifications.map(notif => `
                <div class="notification-item ${notif.is_read ? '' : 'unread'}" onclick="handleNotificationClick('${notif.notification_id}', '${notif.link || ''}')">
                    <div class="notification-text">${notif.message || notif.title}</div>
                    <div class="notification-time">${timeAgo(notif.created_at)}</div>
                </div>
            `).join('');
        } catch (err) {
            console.error('Failed to load notifications:', err);
        }
    }

    async function handleNotificationClick(notifId, link) {
        await fetch(`/notifications/${notifId}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        if (link) {
            window.location.href = link;
        } else {
            loadNotifications();
        }
    }

    async function markAllNotificationsRead() {
        await fetch('/notifications/read-all', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        loadNotifications();
    }

    function timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return 'now';
        if (seconds < 3600) return Math.floor(seconds / 60) + 'm';
        if (seconds < 86400) return Math.floor(seconds / 3600) + 'h';
        if (seconds < 604800) return Math.floor(seconds / 86400) + 'd';
        return Math.floor(seconds / 604800) + 'w';
    }
</script>