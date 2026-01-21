@extends('layout.main')

@push('styles')
@vite(['resources/css/pages.css'])
@endpush

@section('content')

<div class="container-custom">
    <h1 class="page-title">Settings</h1>

    <div class="settings-container">
        <div class="alert alert-success d-none mb-4" id="successMessage"
            style="border-radius: 30px; font-weight: 600; font-size: 14px;">
            <i class="bi bi-check-circle-fill me-2"></i> Personal Information successfully saved!
        </div>

        <h2 class="info-title">Personal Information</h2>

        <form id="settingsForm">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">School Number</label>
                    <input type="text" class="form-control" value="SN-XXXXXXXXXX" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Account Type</label>
                    <input type="text" class="form-control" value="Student" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" value="FN" placeholder="First Name">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middleName" value="MN" placeholder="Middle Name">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" value="LN" placeholder="Last Name">
                </div>

                <div class="col-12">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <input type="email" class="form-control" id="email" value="FNLN@gmail.com" placeholder="Email@gmail.com">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="Create a strong password" oninput="checkStrength(this.value)">
                        <span class="input-group-text password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                            <i class="bi bi-eye" id="toggleIcon1"></i>
                        </span>
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <small class="text-muted">Strength: <span id="strengthText">-</span></small>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Re-enter your password">
                        <span class="input-group-text password-toggle" onclick="togglePassword('confirmPassword', 'toggleIcon2')">
                            <i class="bi bi-eye" id="toggleIcon2"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-5">
                <button type="button" class="btn-primary-custom" id="updateBtn">Update Information</button>
                <button type="button" class="btn-outline" id="cancelBtn">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="mb-3">
                    <i class="bi bi-question-circle" style="font-size: 3rem; color: var(--pup-maroon);"></i>
                </div>
                <h3 class="mb-3" style="font-weight: 700; color: #333;">Save Changes?</h3>
                <p class="text-muted mb-4">Are you sure you want to update your personal information?</p>
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn-primary-custom px-5" id="confirmYes">Save</button>
                    <button type="button" class="btn-outline px-4" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const updateBtn = document.getElementById('updateBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmYes = document.getElementById('confirmYes');
    const successMessage = document.getElementById('successMessage');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

    let originalValues = {
        firstName: document.getElementById('firstName').value,
        middleName: document.getElementById('middleName').value,
        lastName: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value
    };

    updateBtn.addEventListener('click', () => confirmModal.show());

    cancelBtn.addEventListener('click', function() {
        document.getElementById('firstName').value = originalValues.firstName;
        document.getElementById('middleName').value = originalValues.middleName;
        document.getElementById('lastName').value = originalValues.lastName;
        document.getElementById('email').value = originalValues.email;
        document.getElementById('password').value = originalValues.password;
    });

    confirmYes.addEventListener('click', function() {
        originalValues = {
            firstName: document.getElementById('firstName').value,
            middleName: document.getElementById('middleName').value,
            lastName: document.getElementById('lastName').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        confirmModal.hide();
        successMessage.classList.remove('d-none');

        setTimeout(() => {
            successMessage.classList.add('d-none');
        }, 3000);
    });

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        }
    }

    function checkStrength(password) {
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');

        fill.className = 'strength-fill';

        if (password.length === 0) {
            text.innerText = '-';
            fill.style.width = '0%';
        } else if (password.length < 6) {
            text.innerText = 'Weak';
            fill.classList.add('strength-weak');
        } else if (password.length < 10) {
            text.innerText = 'Medium';
            fill.classList.add('strength-medium');
        } else {
            text.innerText = 'Strong';
            fill.classList.add('strength-strong');
        }
    }
</script>

@endsection