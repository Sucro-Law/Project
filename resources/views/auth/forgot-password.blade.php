<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Student Organization Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/forgotpass.css'])
    
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
