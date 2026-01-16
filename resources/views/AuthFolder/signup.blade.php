<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Student Organization Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --pup-maroon: #800000;
            --pup-gold: #FFD700;
            --pup-dark: #4a0000;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.15);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.2);
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
            padding: 40px 20px;
        }

        .signup-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: var(--shadow-lg);
            animation: fadeInUp 0.6s ease-out;
        }

        .signup-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .pup-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: float 3s ease-in-out infinite;
        }

        .pup-logo span {
            font-size: 2rem;
            color: white;
            font-weight: bold;
        }

        .signup-header h2 {
            color: var(--pup-maroon);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .signup-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }

        .role-btn {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .role-btn:hover {
            transform: translateY(-2px);
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
            font-size: 1.8rem;
            margin-bottom: 8px;
            color: var(--pup-maroon);
            transition: all 0.3s ease;
        }

        .role-label {
            font-size: 0.9rem;
            font-weight: 600;
            display: block;
        }

        .form-section {
            margin-bottom: 25px;
        }

        .section-title {
            color: var(--pup-maroon);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-row.single {
            grid-template-columns: 1fr;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-size: 0.85rem;
        }

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--pup-maroon);
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1);
            outline: none;
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
        }

        .certification-box {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }

        .certification-text {
            font-size: 0.85rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .custom-checkbox {
            display: flex;
            align-items: flex-start;
            cursor: pointer;
        }

        .custom-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: var(--pup-maroon);
        }

        .custom-checkbox label {
            font-size: 0.85rem;
            color: #555;
            cursor: pointer;
            line-height: 1.5;
        }

        .custom-checkbox label a {
            color: var(--pup-maroon);
            text-decoration: none;
            font-weight: 600;
        }

        .custom-checkbox label a:hover {
            text-decoration: underline;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
            border: none;
            border-radius: 10px;
            padding: 14px 30px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary-custom:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary-custom:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .signin-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .signin-link p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .signin-link a {
            color: var(--pup-maroon);
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
        }

        .signin-link a:hover {
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

        .password-strength {
            margin-top: 8px;
            font-size: 0.8rem;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e0e0e0;
            margin-top: 5px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            width: 0%;
        }

        .strength-weak .strength-fill {
            width: 33%;
            background: #dc3545;
        }

        .strength-medium .strength-fill {
            width: 66%;
            background: #ffc107;
        }

        .strength-strong .strength-fill {
            width: 100%;
            background: #28a745;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        @media (max-width: 768px) {
            .signup-container {
                padding: 40px 25px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .role-selector {
                grid-template-columns: 1fr;
            }
        }

        .form-text {
            font-size: 0.75rem;
            color: #666;
            margin-top: 5px;
        }

        .invalid-feedback {
            display: none;
            font-size: 0.8rem;
            color: #dc3545;
            margin-top: 5px;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-invalid ~ .invalid-feedback {
            display: block;
        }

        .form-control.is-valid {
            border-color: #28a745;
        }
    </style>
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
            <div class="form-section">
                <div class="section-title">Account Information</div>
                
                <div class="form-group">
                    <label for="schoolId" class="form-label" id="schoolIdLabel">School Number</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-card-text"></i>
                        </span>
                        <input type="text" class="form-control" id="schoolId" placeholder="SN-XXXXXXXX" required>
                    </div>
                    <div class="invalid-feedback">Please enter a valid school ID format</div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">Personal Information</div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="First Name" required>
                        <div class="invalid-feedback">First name is required</div>
                    </div>
                    <div class="form-group">
                        <label for="middleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middleName" placeholder="Middle Name">
                    </div>
                    <div class="form-group">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Last Name" required>
                        <div class="invalid-feedback">Last name is required</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <input type="email" class="form-control" id="email" placeholder="Email@gmail.com" required>
                    </div>
                    <div class="invalid-feedback">Please enter a valid email address</div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control" id="password" placeholder="Create a strong password" required>
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
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Re-enter your password" required>
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
            <a href="index.html">Sign In Here!</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentRole = 'student';

        function selectRole(role) {
            currentRole = role;
            
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
        document.getElementById('signupForm').addEventListener('submit', function(e) {
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
                // Collect form data
                const formData = {
                    role: currentRole,
                    schoolId: schoolId.value,
                    firstName: firstName.value,
                    middleName: document.getElementById('middleName').value,
                    lastName: lastName.value,
                    email: email.value,
                    password: password.value
                };

                console.log('Registration data:', formData);
                
                // Simulate successful registration
                alert('Registration successful! Please check your email for verification.');
                // window.location.href = 'index.html';
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