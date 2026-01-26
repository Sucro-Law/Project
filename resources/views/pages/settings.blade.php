@extends('layout.main')

@push('styles')
@vite(['resources/css/settings.css'])
@endpush

@section('content')

<div class="container-custom">
    <h1 class="page-title">Settings</h1>

    <div class="settings-container">
        <div class="alert alert-success d-none mb-4" id="successMessage"
            style="border-radius: 30px; font-weight: 600; font-size: 14px;">
            <i class="bi bi-check-circle-fill me-2"></i> <span id="successText">Successfully saved!</span>
        </div>

        <div class="alert alert-danger d-none mb-4" id="errorMessage"
            style="border-radius: 30px; font-weight: 600; font-size: 14px;">
            <i class="bi bi-exclamation-circle-fill me-2"></i> <span id="errorText"></span>
        </div>

        <div class="d-flex justify-content-between align-items-center info-title">
            <h2 class="m-0" style="font-size: 22px; font-weight: 700;">Personal Information</h2>
            <button type="button" class="btn-edit" id="editProfileBtn">
                <i class="bi bi-pencil-square me-2"></i>Edit Profile
            </button>
        </div>

        <form id="settingsForm">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">School Number</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->school_id ?? 'N/A' }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Account Type</label>
                    <input type="text" class="form-control" value="{{ ucfirst(Auth::user()->account_type ?? 'Student') }}" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control editable-field" id="firstName" name="first_name"
                        value="{{ $firstName }}" readonly>
                    <div class="invalid-feedback" id="firstNameError"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control editable-field" id="middleName" name="middle_name"
                        value="{{ $middleName }}" readonly>
                    <div class="invalid-feedback" id="middleNameError"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control editable-field" id="lastName" name="last_name"
                        value="{{ $lastName }}" readonly>
                    <div class="invalid-feedback" id="lastNameError"></div>
                </div>

                <div class="col-12">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white" style="border-radius: 12px 0 0 12px; border-right: none; color: #666;">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <input type="email" class="form-control editable-field" id="email" name="email"
                            value="{{ Auth::user()->email ?? '' }}" readonly
                            style="border-left: none; border-radius: 0 12px 12px 0;">
                    </div>
                    <div class="invalid-feedback" id="emailError"></div>
                </div>

                <div id="securitySection" class="d-none">
                    <div class="col-12 mt-4">
                        <label class="form-label">New Password (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control editable-field" id="password" name="password"
                                placeholder="Leave blank to keep current password" oninput="checkStrength(this.value)">
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
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control editable-field" id="confirmPassword"
                                name="password_confirmation" placeholder="Re-enter your password">
                            <span class="input-group-text password-toggle" onclick="togglePassword('confirmPassword', 'toggleIcon2')">
                                <i class="bi bi-eye" id="toggleIcon2"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback" id="confirmPasswordError"></div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-5 d-none" id="editActionButtons">
                <button type="button" class="btn-primary-custom" id="updateBtn">
                    <span class="btn-text">Update Information</span>
                    <span class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                </button>
                <button type="button" class="btn-outline-custom" id="cancelBtn">Cancel</button>
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
                    <button type="button" class="btn-outline-custom px-4" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const editProfileBtn = document.getElementById('editProfileBtn');
    const editActionButtons = document.getElementById('editActionButtons');
    const securitySection = document.getElementById('securitySection');
    const editableFields = document.querySelectorAll('.editable-field');

    const updateBtn = document.getElementById('updateBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmYes = document.getElementById('confirmYes');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

    let originalValues = {};

    function saveOriginalValues() {
        originalValues = {
            firstName: document.getElementById('firstName').value,
            middleName: document.getElementById('middleName').value,
            lastName: document.getElementById('lastName').value,
            email: document.getElementById('email').value,
            password: ""
        };
    }

    function toggleEditMode(isEditing) {
        if (isEditing) {
            saveOriginalValues();
            editProfileBtn.classList.add('d-none');
            editActionButtons.classList.remove('d-none');
            securitySection.classList.remove('d-none');
            editableFields.forEach(field => field.removeAttribute('readonly'));
            clearErrors();
        } else {
            editProfileBtn.classList.remove('d-none');
            editActionButtons.classList.add('d-none');
            securitySection.classList.add('d-none');
            editableFields.forEach(field => field.setAttribute('readonly', true));
            clearErrors();
        }
    }

    function clearErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }

    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.getElementById(fieldId + 'Error');
        field.classList.add('is-invalid');
        errorDiv.textContent = message;
    }

    editProfileBtn.addEventListener('click', () => toggleEditMode(true));

    cancelBtn.addEventListener('click', function() {
        document.getElementById('firstName').value = originalValues.firstName;
        document.getElementById('middleName').value = originalValues.middleName;
        document.getElementById('lastName').value = originalValues.lastName;
        document.getElementById('email').value = originalValues.email;
        document.getElementById('password').value = "";
        document.getElementById('confirmPassword').value = "";
        document.getElementById('strengthFill').className = 'strength-fill';
        document.getElementById('strengthFill').style.width = '0%';
        document.getElementById('strengthText').innerText = '-';
        toggleEditMode(false);
    });

    updateBtn.addEventListener('click', () => {
        clearErrors();

        // Basic validation
        let hasError = false;

        if (!document.getElementById('firstName').value.trim()) {
            showError('firstName', 'First name is required');
            hasError = true;
        }

        if (!document.getElementById('lastName').value.trim()) {
            showError('lastName', 'Last name is required');
            hasError = true;
        }

        if (!document.getElementById('email').value.trim()) {
            showError('email', 'Email is required');
            hasError = true;
        }

        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (password && password.length < 6) {
            showError('password', 'Password must be at least 6 characters');
            hasError = true;
        }

        if (password !== confirmPassword) {
            showError('confirmPassword', 'Passwords do not match');
            hasError = true;
        }

        if (!hasError) {
            confirmModal.show();
        }
    });

    confirmYes.addEventListener('click', function() {
        confirmModal.hide();

        // Show loading spinner
        const btnText = updateBtn.querySelector('.btn-text');
        const spinner = updateBtn.querySelector('.spinner-border');
        btnText.textContent = 'Updating...';
        spinner.classList.remove('d-none');
        updateBtn.disabled = true;

        // Prepare form data
        const formData = new FormData(document.getElementById('settingsForm'));

        // Send AJAX request
        fetch('{{ route("settings.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Hide loading spinner
                btnText.textContent = 'Update Information';
                spinner.classList.add('d-none');
                updateBtn.disabled = false;

                if (data.success) {
                    successMessage.querySelector('#successText').textContent = data.message || 'Successfully updated!';
                    successMessage.classList.remove('d-none');
                    errorMessage.classList.add('d-none');
                    toggleEditMode(false);

                    // Keep the values as they were entered
                    saveOriginalValues();

                    setTimeout(() => {
                        successMessage.classList.add('d-none');
                    }, 3000);
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            })
            .catch(error => {
                // Hide loading spinner
                btnText.textContent = 'Update Information';
                spinner.classList.add('d-none');
                updateBtn.disabled = false;

                // Show error message
                errorMessage.querySelector('#errorText').textContent = error.message || 'An error occurred. Please try again.';
                errorMessage.classList.remove('d-none');
                successMessage.classList.add('d-none');

                setTimeout(() => {
                    errorMessage.classList.add('d-none');
                }, 5000);
            });
    });

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        input.type = input.type === "password" ? "text" : "password";
        icon.classList.toggle("bi-eye");
        icon.classList.toggle("bi-eye-slash");
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