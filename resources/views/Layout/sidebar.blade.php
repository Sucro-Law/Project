<div class="sidebar" id="sidebar">

    <div class="sidebar-header">

        <button class="close-sidebar-btn" onclick="toggleSidebar()">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="pup-logo">
            <span>PUP</span>
        </div>
        <div class="sidebar-title">SOM System</div>
    </div>

    @if(Auth::check() && isset($sidebarData))
    <div class="user-profile">
        <div class="user-avatar">{{ $sidebarData['initials'] }}</div>
        <div class="user-name">{{ $sidebarData['full_name'] }}</div>

        <!-- User Organizations List -->
        <div class="user-org-list">
            <div class="user-org-title">Organization/s Joined:</div>

            @if(count($sidebarData['organizations']) > 0)
            @foreach($sidebarData['organizations'] as $org)
            <div>
                <a href="{{ route('orgDetail', $org['org_id']) }}" style="text-decoration: none;">
                    <span class="org-badge {{ !$loop->first ? 'mt-2' : '' }}">{{ $org['org_name'] }}</span>
                </a>
            </div>
            <div class="org-info">
                <div>Position: {{ $org['display_position'] }}</div>
                <div>Academic Year: {{ $org['academic_year'] }}</div>
                <div>Date Joined: {{ $org['formatted_joined_at'] }}</div>
            </div>
            @endforeach
            @else
            <div class="org-info" style="text-align: center; padding: 10px 0;">
                <div style="color: #999; font-size: 13px;">No organizations joined yet</div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="user-profile">
        <div class="user-avatar">?</div>
        <div class="user-name">Guest User</div>
        <div class="user-org-list">
            <div class="org-info" style="text-align: center; padding: 10px 0;">
                <div style="color: #999; font-size: 13px;">Please login to view organizations</div>
            </div>
        </div>
    </div>
    @endif

    <div class="height">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill"></i>
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('organization') }}" class="nav-link {{ request()->routeIs('organization') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    Organization
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('events') }}" class="nav-link {{ request()->routeIs('events') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i>
                    Events
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('settings') }}" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    Settings
                </a>
            </li>
            @if(Auth::check())
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                        <i class="bi bi-box-arrow-right"></i>
                        Log out
                    </button>
                </form>
            </li>
            @else
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Log in
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>