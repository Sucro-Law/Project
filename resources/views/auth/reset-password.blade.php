<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Student Organization Management</title>
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

        .reset-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: var(--shadow-lg);
            animation: fadeInRight 0.8s ease-out;
        }

        .reset-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .reset-header .icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .reset-header .icon-wrapper i {
            font-size: 2.5rem;
            color: white;
        }

        .reset-header h2 {
            color: var(--pup-maroon);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .reset-header p {
            color: #666;
            font-size: 0.95rem;
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

        .btn-primary-custom:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .back-to-login {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .back-to-login a {
            color: var(--pup-maroon);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }

        .input-group-text {
            background: white;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 10px 0 0 10px;
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
            border-left: none !important;
            border-radius: 0 10px 10px 0 !important;
        }

        .input-group .password-toggle {
            border-left: none;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            margin-right: 8px;
        }

        .password-strength {
            margin-top: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            display: none;
        }

        .password-strength.weak {
            background: #ffebee;
            color: #c62828;
            display: block;
        }

        .password-strength.medium {
            background: #fff3e0;
            color: #ef6c00;
            display: block;
        }

        .password-strength.strong {
            background: #e8f5e9;
            color: #2e7d32;
            display: block;
        }

        .password-requirements {
            margin-top: 10px;
            font-size: 0.85rem;
            color: #666;
        }

        .password-requirements ul {
            margin: 5px 0 0 20px;
            padding: 0;
        }

        .password-requirements li {
            margin-bottom: 3px;
        }

        .password-requirements li.valid {
            color: #2e7d32;
        }

        .password-requirements li.invalid {
            color: #c62828;
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
            .reset-card {
                padding: 30px 25px;
            }

            .brand-title {
                font-size: 1.8rem;
            }
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
            <h1 class="brand-title">Reset Password</h1>
            <p class="brand-subtitle">Create a new secure password for your account.</p>
            <p class="brand-tagline">Polytechnic University of the Philippines</p>
        </div>

        <!-- Reset Password Card -->
        <div class="reset-card">
            <div class="reset-header">
                <div class="icon-wrapper">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h2>Create New Password</h2>
                <p>Your new password must be different from previously used passwords.</p>
            </div>

            <!-- Alert Messages -->
            <div id="successAlert" class="alert alert-success" style="display: none;"></div>
            <div id="errorAlert" class="alert alert-danger" style="display: none;"></div>

            <!-- Reset Password Form -->
            <form id="resetPasswordForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required minlength="8">
                        <span class="input-group-text password-toggle" onclick="togglePassword('password', 'passwordToggleIcon')">
                            <i class="bi bi-eye" id="passwordToggleIcon"></i>
                        </span>
                    </div>
                    <div id="passwordStrength" class="password-strength"></div>
                    <div class="password-requirements">
                        <ul>
                            <li id="req-length" class="invalid">At least 8 characters</li>
                            <li id="req-upper" class="invalid">One uppercase letter</li>
                            <li id="req-lower" class="invalid">One lowercase letter</li>
                            <li id="req-number" class="invalid">One number</li>
                        </ul>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required minlength="8">
                        <span class="input-group-text password-toggle" onclick="togglePassword('password_confirmation', 'confirmToggleIcon')">
                            <i class="bi bi-eye" id="confirmToggleIcon"></i>
                        </span>
                    </div>
                    <div id="passwordMatch" class="password-strength" style="display: none;"></div>
                </div>

                <button type="submit" class="btn-primary-custom" id="submitBtn">
                    <span id="btnText">Reset Password</span>
                    <span id="btnSpinner" style="display: none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Resetting...
                    </span>
                </button>
            </form>

            <div class="back-to-login">
                <a href="{{ route('login') }}">
                    <i class="bi bi-arrow-left"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

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

        function showSuccess(message) {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            errorAlert.style.display = 'none';
            successAlert.textContent = message;
            successAlert.style.display = 'block';
        }

        function showError(message) {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            successAlert.style.display = 'none';
            errorAlert.textContent = message;
            errorAlert.style.display = 'block';
            setTimeout(() => {
                errorAlert.style.display = 'none';
            }, 5000);
        }

        function setLoading(loading) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            submitBtn.disabled = loading;
            btnText.style.display = loading ? 'none' : 'inline';
            btnSpinner.style.display = loading ? 'inline' : 'none';
        }

        function checkPasswordStrength(password) {
            let strength = 0;
            const requirements = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password)
            };

            // Update requirement indicators
            document.getElementById('req-length').className = requirements.length ? 'valid' : 'invalid';
            document.getElementById('req-upper').className = requirements.upper ? 'valid' : 'invalid';
            document.getElementById('req-lower').className = requirements.lower ? 'valid' : 'invalid';
            document.getElementById('req-number').className = requirements.number ? 'valid' : 'invalid';

            // Calculate strength
            if (requirements.length) strength++;
            if (requirements.upper) strength++;
            if (requirements.lower) strength++;
            if (requirements.number) strength++;

            const strengthEl = document.getElementById('passwordStrength');

            if (password.length === 0) {
                strengthEl.style.display = 'none';
                return;
            }

            strengthEl.style.display = 'block';
            strengthEl.className = 'password-strength';

            if (strength <= 2) {
                strengthEl.classList.add('weak');
                strengthEl.textContent = 'Weak password';
            } else if (strength === 3) {
                strengthEl.classList.add('medium');
                strengthEl.textContent = 'Medium password';
            } else {
                strengthEl.classList.add('strong');
                strengthEl.textContent = 'Strong password';
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const matchEl = document.getElementById('passwordMatch');

            if (confirm.length === 0) {
                matchEl.style.display = 'none';
                return;
            }

            matchEl.style.display = 'block';
            matchEl.className = 'password-strength';

            if (password === confirm) {
                matchEl.classList.add('strong');
                matchEl.textContent = 'Passwords match';
            } else {
                matchEl.classList.add('weak');
                matchEl.textContent = 'Passwords do not match';
            }
        }

        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });

        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

        document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;

            if (password !== confirm) {
                showError('Passwords do not match.');
                return;
            }

            if (password.length < 8) {
                showError('Password must be at least 8 characters long.');
                return;
            }

            setLoading(true);
            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route("password.update") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showSuccess(data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("login") }}';
                    }, 2000);
                } else {
                    showError(data.message || 'Something went wrong. Please try again.');
                }
            } catch (error) {
                showError('An error occurred. Please try again.');
                console.error('Error:', error);
            } finally {
                setLoading(false);
            }
        });
    </script>
</body>

</html>
