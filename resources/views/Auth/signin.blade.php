<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Organization Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --pup-maroon: #800000;
            --pup-gold: #FFD700;
            --pup-dark: #4a0000;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #800000 0%, #4a0000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .landing-container {
            max-width: 1200px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .brand-section {
            color: white;
            animation: fadeInLeft 0.8s ease-out;
        }

        .brand-logo {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            box-shadow: var(--shadow-lg);
            animation: float 3s ease-in-out infinite;
        }

        .brand-logo img {
            width: 80%;
            height: 80%;
            object-fit: contain;
        }

        .brand-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            margin-bottom: 30px;
        }

        .brand-tagline {
            font-size: 1rem;
            opacity: 0.8;
            font-style: italic;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: var(--shadow-lg);
            animation: fadeInRight 0.8s ease-out;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h2 {
            color: var(--pup-maroon);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .role-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .role-btn {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 25px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .role-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: var(--pup-maroon);
        }

        .role-btn.active {
            background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            border-color: var(--pup-maroon);
            color: white;
        }

        .role-btn.active .role-icon {
            color: var(--pup-gold);
        }

        .role-icon {
            font-size: 2rem;
            margin-bottom: 8px;
            color: var(--pup-maroon);
            transition: all 0.3s ease;
        }

        .role-label {
            font-size: 0.9rem;
            font-weight: 600;
            display: block;
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

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--pup-maroon);
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password a {
            color: var(--pup-maroon);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .signup-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .signup-link p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .signup-link a {
            color: var(--pup-maroon);
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .terms-notice {
            text-align: center;
            font-size: 0.8rem;
            color: #888;
            margin-top: 20px;
            line-height: 1.5;
        }

        .terms-notice a {
            color: var(--pup-maroon);
            text-decoration: none;
        }

        .terms-notice a:hover {
            text-decoration: underline;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 992px) {
            .landing-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .brand-section {
                text-align: center;
            }

            .brand-logo {
                margin-left: auto;
                margin-right: auto;
            }

            .brand-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 30px 25px;
            }

            .role-selector {
                grid-template-columns: 1fr;
            }

            .brand-title {
                font-size: 1.8rem;
            }
        }

        .input-group-text {
            background: white;
            border: 2px solid #e0e0e0;
            color: #666;
            transition: all 0.3s ease;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--pup-maroon);
        }

        .password-toggle {
            cursor: pointer;
            user-select: none;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="landing-container">
        <!-- Brand Section -->
        <div class="brand-section">
            <div class="brand-logo">
                <span style="font-size: 3rem; color: var(--pup-maroon); font-weight: bold;">PUP</span>
            </div>
            <h1 class="brand-title">Hi, PUPian!</h1>
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