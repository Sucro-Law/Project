<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KadaOrg - Student Organization Management')</title>

    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/orgdesc.css', 'resources/js/app.js'])
    @stack('styles')


    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">


    <!-- Page-specific CSS -->
    @stack('styles')


</head>

<body>
    <div class="dashboard">
        @include('layout.header')
        <div class="main-content">
            @yield('content')
            @include('layout.sidebar')
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


        // Close dropdown
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notificationDropdown');
            const btn = document.querySelector('.notification-btn');
            if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>


    @stack('scripts')
</body>

</html>