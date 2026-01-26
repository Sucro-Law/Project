<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign Up - Student Organization Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/signup.css'])

</head>

<body>
    <div class="signup-container">
        <div class="signup-header">
            <div class="pup-logo">
                <span>PUP</span>
            </div>
            <h2>Student Organization<br>Management</h2>
            <p>Sign Up Application</p>
        </div>

        <!-- Error Display -->
        <div id="errorAlert" class="alert alert-danger" style="display: none;"></div>
        <div id="successAlert" class="alert alert-success" style="display: none;"></div>

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
        </div>

        <!-- Sign Up Form -->
        <form id="signupForm">
            @csrf
            <input type="hidden" name="role" id="roleInput" value="student">

            <div class="form-section">
                <div class="section-title">Account Information</div>

                <div class="form-group">
                    <label for="schoolId" class="form-label" id="schoolIdLabel">School Number</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-card-text"></i>
                        </span>
                        <input type="text" class="form-control" id="schoolId" name="school_id" placeholder="SN-XXXXXXXX" required>
                    </div>
                    <div class="invalid-feedback">Please enter a valid school ID format</div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">Personal Information</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" placeholder="First Name" required>
                        <div class="invalid-feedback">First name is required</div>
                    </div>
                    <div class="form-group">
                        <label for="middleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middleName" name="middle_name" placeholder="Middle Name">
                    </div>
                    <div class="form-group">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Last Name" required>
                        <div class="invalid-feedback">Last name is required</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email@gmail.com" required>
                    </div>
                    <div class="invalid-feedback">Please enter a valid email address</div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Create a strong password" required>
                        <span class="input-group-text password-toggle" onclick="togglePassword('password', 'passwordToggleIcon')">
                            <i class="bi bi-eye" id="passwordToggleIcon"></i>
                        </span>
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-bar">
                            <div class="strength-fill"></div>
                        </div>
                        <small class="form-text">Password strength: <span id="strengthText">-</span></small>
                    </div>
                    <div class="invalid-feedback">Password must be at least 8 characters</div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Re-enter your password" required>
                        <span class="input-group-text password-toggle" onclick="togglePassword('confirmPassword', 'confirmPasswordToggleIcon')">
                            <i class="bi bi-eye" id="confirmPasswordToggleIcon"></i>
                        </span>
                    </div>
                    <div class="invalid-feedback">Passwords do not match</div>
                </div>
            </div>

            <!-- Certification Box -->
            <div class="certification-box">
                <p class="certification-text">
                    I hereby certify that the information provided in this form is true, complete,
                    and accurate to the best of my knowledge. I understand that any misrepresentation
                    or material omission made on this form may result in the rejection of my application.
                </p>

                <div class="custom-checkbox">
                    <input type="checkbox" id="agreeTerms" required>
                    <label for="agreeTerms">
                        By using this service, you understood and agree to the
                        <a href="#">PUP Online Services Terms of Use</a> and
                        <a href="#">Privacy Statement</a>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-primary-custom" id="submitBtn" disabled>
                Sign Up
            </button>
        </form>

        <div class="signin-link">
            <p>Already have an account?</p>
            <a href="{{ route('login') }}">Sign In Here!</a>
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

            // Update labels
            const schoolIdLabel = document.getElementById('schoolIdLabel');
            const schoolIdInput = document.getElementById('schoolId');

            if (role === 'student') {
                schoolIdLabel.textContent = 'School Number';
                schoolIdInput.placeholder = 'SN-XXXXXXXX';
            } else {
                schoolIdLabel.textContent = 'Faculty Number';
                schoolIdInput.placeholder = 'FN-XXXXXXXX';
            }
        }

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

        function showError(message) {
            const errorAlert = document.getElementById('errorAlert');
            errorAlert.textContent = message;
            errorAlert.style.display = 'block';
            window.scrollTo(0, 0);
            setTimeout(() => {
                errorAlert.style.display = 'none';
            }, 5000);
        }

        function showSuccess(message) {
            const successAlert = document.getElementById('successAlert');
            successAlert.textContent = message;
            successAlert.style.display = 'block';
            window.scrollTo(0, 0);
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthContainer = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');

            if (password.length === 0) {
                strengthContainer.className = 'password-strength';
                strengthText.textContent = '-';
                return;
            }

            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            strengthContainer.className = 'password-strength';

            if (strength <= 1) {
                strengthContainer.classList.add('strength-weak');
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#dc3545';
            } else if (strength <= 2) {
                strengthContainer.classList.add('strength-medium');
                strengthText.textContent = 'Medium';
                strengthText.style.color = '#ffc107';
            } else {
                strengthContainer.classList.add('strength-strong');
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#28a745';
            }
        });

        // Enable submit button when terms are agreed
        document.getElementById('agreeTerms').addEventListener('change', function(e) {
            document.getElementById('submitBtn').disabled = !e.target.checked;
        });

        // School ID formatting
        document.getElementById('schoolId').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            const prefix = currentRole === 'student' ? 'SN-' : 'FN-';

            if (!value.startsWith(prefix)) {
                value = prefix + value.replace(/[^0-9]/g, '');
            }

            e.target.value = value.substring(0, 11);
        });

        // Form validation
        document.getElementById('signupForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            let isValid = true;

            // School ID validation
            const schoolId = document.getElementById('schoolId');
            const prefix = currentRole === 'student' ? 'SN-' : 'FN-';
            const regex = new RegExp(`^${prefix}\\d{8}$`);

            if (!regex.test(schoolId.value)) {
                schoolId.classList.add('is-invalid');
                isValid = false;
            } else {
                schoolId.classList.remove('is-invalid');
                schoolId.classList.add('is-valid');
            }

            // Name validation
            const firstName = document.getElementById('firstName');
            const lastName = document.getElementById('lastName');

            if (firstName.value.trim().length < 2) {
                firstName.classList.add('is-invalid');
                isValid = false;
            } else {
                firstName.classList.remove('is-invalid');
                firstName.classList.add('is-valid');
            }

            if (lastName.value.trim().length < 2) {
                lastName.classList.add('is-invalid');
                isValid = false;
            } else {
                lastName.classList.remove('is-invalid');
                lastName.classList.add('is-valid');
            }

            // Email validation
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email.value)) {
                email.classList.add('is-invalid');
                isValid = false;
            } else {
                email.classList.remove('is-invalid');
                email.classList.add('is-valid');
            }

            // Password validation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');

            if (password.value.length < 8) {
                password.classList.add('is-invalid');
                isValid = false;
            } else {
                password.classList.remove('is-invalid');
                password.classList.add('is-valid');
            }

            if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('is-invalid');
                isValid = false;
            } else {
                confirmPassword.classList.remove('is-invalid');
                confirmPassword.classList.add('is-valid');
            }

            if (isValid) {
                const formData = new FormData(this);

                try {
                    const response = await fetch('{{ route("signup.submit") }}', {
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
                            window.location.href = data.redirect;
                        }, 1500);
                    } else {
                        if (data.errors) {
                            let errorMessage = 'Please fix the following errors:\n';
                            Object.values(data.errors).forEach(error => {
                                errorMessage += '- ' + error[0] + '\n';
                            });
                            showError(errorMessage);
                        } else {
                            showError(data.message || 'Registration failed. Please try again.');
                        }
                    }
                } catch (error) {
                    showError('An error occurred. Please try again.');
                    console.error('Signup error:', error);
                }
            }
        });

        // Remove validation classes on input
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid', 'is-valid');
            });
        });
    </script>
</body>

</html>