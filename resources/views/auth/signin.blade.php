<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Organization Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/signin.css', 'resources/js/app.js'])
    
</head>

<body>
    <div class="landing-container">
        <!-- Brand Section -->
        <div class="brand-section">
            <div class="brand-logo">
                <span style="font-size: 3rem; color: var(--pup-maroon); font-weight: bold;">PUP</span>
            </div>
            <h1 class="brand-title">Hi, PUPians!</h1>
            <p class="brand-subtitle">Please click or tap your destination</p>
            <p class="brand-tagline">Polytechnic University of the Philippines</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <div class="login-header">
                <h2>Student Organization<br>Management</h2>
                <p>Sign in to start your session</p>
            </div>

            <!-- Error Display -->
            <div id="errorAlert" class="alert alert-danger" style="display: none;"></div>

            <!-- Role Selector -->
            <div class="role-selector">
                <div class="role-btn active" data-role="student" onclick="selectRole('student')">
                    <i class="bi bi-person-fill role-icon"></i>
                    <span class="role-label">Student</span>
                </div>
                <div class="role-btn" data-role="faculty" onclick="selectRole('faculty')">
                    <i class="bi bi-briefcase-fill role-icon"></i>
                    <span class="role-label">Faculty</span>
                </div>
                <div class="role-btn" data-role="admin" onclick="selectRole('admin')">
                    <i class="bi bi-shield-fill-check role-icon"></i>
                    <span class="role-label">Admin</span>
                </div>
            </div>

            <!-- Login Form -->
            <form id="loginForm">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="student">

                <div class="form-group">
                    <label for="username" class="form-label" id="usernameLabel">Student Number</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="SN-XXXXXXXX" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        <span class="input-group-text password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="passwordToggleIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-primary-custom">
                    Sign in
                </button>

                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">I forgot my password</a>
                </div>
            </form>

            <div class="signup-link">
                <p>Don't have an account?</p>
                <a href="{{ route('signup') }}">Sign Up Here!</a>
            </div>

            <div class="terms-notice">
                By using this service, you understood and agree to the<br>
                <a href="#">PUP Online Services Terms of Use</a> and <a href="#">Privacy Statement</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentRole = 'student';

        function selectRole(role) {
            currentRole = role;
            document.getElementById('roleInput').value = role;

            // Update active state
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-role="${role}"]`).classList.add('active');

            // Update form labels and placeholders
            const usernameLabel = document.getElementById('usernameLabel');
            const usernameInput = document.getElementById('username');

            switch (role) {
                case 'student':
                    usernameLabel.textContent = 'Student Number';
                    usernameInput.placeholder = 'SN-XXXXXXXX';
                    break;
                case 'faculty':
                    usernameLabel.textContent = 'Faculty Number';
                    usernameInput.placeholder = 'FN-XXXXXXXX';
                    break;
                case 'admin':
                    usernameLabel.textContent = 'Admin Username';
                    usernameInput.placeholder = 'Enter admin username';
                    break;
            }

            // Add ripple effect
            const activeBtn = document.querySelector(`[data-role="${role}"]`);
            activeBtn.style.animation = 'none';
            setTimeout(() => {
                activeBtn.style.animation = '';
            }, 10);
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        function showError(message) {
            const errorAlert = document.getElementById('errorAlert');
            errorAlert.textContent = message;
            errorAlert.style.display = 'block';
            setTimeout(() => {
                errorAlert.style.display = 'none';
            }, 5000);
        }

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            // Basic validation for school ID format
            if (currentRole === 'student' && !username.match(/^SN-\d{8}$/)) {
                showError('Invalid Student Number format. Please use SN-XXXXXXXX');
                return;
            }

            if (currentRole === 'faculty' && !username.match(/^FN-\d{8}$/)) {
                showError('Invalid Faculty Number format. Please use FN-XXXXXXXX');
                return;
            }

            // Prepare form data
            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route("login.submit") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    window.location.href = data.redirect;
                } else {
                    showError(data.message || 'Login failed. Please check your credentials.');
                }
            } catch (error) {
                showError('An error occurred. Please try again.');
                console.error('Login error:', error);
            }
        });

        // Add input mask for student/faculty numbers
        document.getElementById('username').addEventListener('input', function(e) {
            if (currentRole !== 'admin') {
                let value = e.target.value.toUpperCase();

                // Remove any non-digit characters except the prefix
                if (currentRole === 'student') {
                    if (!value.startsWith('SN-')) {
                        value = 'SN-' + value.replace(/[^0-9]/g, '');
                    }
                } else if (currentRole === 'faculty') {
                    if (!value.startsWith('FN-')) {
                        value = 'FN-' + value.replace(/[^0-9]/g, '');
                    }
                }

                // Limit to 11 characters (XX-XXXXXXXX)
                e.target.value = value.substring(0, 11);
            }
        });
    </script>
</body>

</html>