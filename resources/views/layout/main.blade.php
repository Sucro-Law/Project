<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KadaOrg - Student Organization Management')</title>

    <!-- Bootstrap & Icons (load first so custom CSS can override) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- bundles of CSS and JavaScript files -->
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/orgdesc.css', 'resources/js/app.js'])

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
//
   <!-- Toggles the 'show' class on the sidebar element. 
        Essential for responsive mobile navigation to slide the menu in/out.-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        
         // Toggles the 'show' class on the sidebar element.
         // Typically used on mobile devices to slide the menu in and out 
         // when the hamburger icon is clicked.
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        //Toggles the 'show' class on the notification dropdown container.
        //Triggered when the user clicks the bell icon in the header.
        function toggleNotifications() {
            document.getElementById('notificationDropdown').classList.toggle('show');
        }

        // Event Listener for closing the notification dropdown automatically.
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notificationDropdown');
            const btn = document.querySelector('.notification-btn');
            if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>

<!-- page specific scripts -->

    @stack('scripts')

</body>

</html>