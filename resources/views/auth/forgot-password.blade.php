<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Student Organization Management</title>
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

        .forgot-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: var(--shadow-lg);
            animation: fadeInRight 0.8s ease-out;
        }

        .forgot-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .forgot-header .icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .forgot-header .icon-wrapper i {
            font-size: 2.5rem;
            color: white;
        }

        .forgot-header h2 {
            color: var(--pup-maroon);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .forgot-header p {
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

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            margin-right: 8px;
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
            .forgot-card {
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
            <h1 class="brand-title">Forgot Password?</h1>
            <p class="brand-subtitle">No worries, we'll send you reset instructions.</p>
            <p class="brand-tagline">Polytechnic University of the Philippines</p>
        </div>

        <!-- Forgot Password Card -->
        <div class="forgot-card">
            <div class="forgot-header">
                <div class="icon-wrapper">
                    <i class="bi bi-key-fill"></i>
                </div>
                <h2>Reset Password</h2>
                <p>Enter your email address and we'll send you a link to reset your password.</p>
            </div>

            <!-- Alert Messages -->
            <div id="successAlert" class="alert alert-success" style="display: none;"></div>
            <div id="errorAlert" class="alert alert-danger" style="display: none;"></div>

            <!-- Forgot Password Form -->
            <form id="forgotPasswordForm">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary-custom" id="submitBtn">
                    <span id="btnText">Send Reset Link</span>
                    <span id="btnSpinner" style="display: none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Sending...
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

        document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            setLoading(true);

            const email = document.getElementById('email').value;
            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route("password.email") }}', {
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
                    document.getElementById('email').value = '';
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
