@extends('layout.main')

@section('content')

<style>
    .container-custom {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-title {
        color: maroon !important;
        font-size: 35px;
        font-weight: 700;
        margin-bottom: 30px;
    }

    .settings-container {
        width: 100%;
        max-width: 100%;
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .info-title {
        color: var(--pup-maroon);
        font-size: 22px;
        font-weight: 700;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }

    .input-group-text {
        background: white;
        border: 2px solid #e0e0e0;
        color: #666;
        transition: all 0.3s ease;
    }

    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control {
        border-color: var(--pup-maroon);
        outline: none;
    }

    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--pup-maroon);
        box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1);
        outline: none;
    }

    .form-control:disabled {
        background-color: #f8f9fa;
        color: #999;
    }

    .password-strength {
        margin-top: 10px;
    }

    .strength-bar {
        height: 6px;
        background: #eee;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 5px;
    }

    .strength-fill {
        height: 100%;
        width: 0;
        transition: all 0.3s ease;
    }

    .strength-weak {
        background: #ff4d4d;
        width: 33%;
    }

    .strength-medium {
        background: #ffa500;
        width: 66%;
    }

    .strength-strong {
        background: #2ecc71;
        width: 100%;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
        color: white;
    }

    .btn-outline-custom {
        background: white;
        color: var(--pup-maroon);
        border: 2px solid var(--pup-maroon);
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
        color: white;
    }

    .btn-edit i {
        font-size: 15px;
    }

    .modal-content {
        border-radius: 20px;
        border: none;
        padding: 15px;
    }
</style>

<div class="container-custom">
    <h1 class="page-title">Settings</h1>

    <div class="settings-container">
        <div class="alert alert-success d-none mb-4" id="successMessage"
            style="border-radius: 30px; font-weight: 600; font-size: 14px;">
            <i class="bi bi-check-circle-fill me-2"></i> Successfully saved!
        </div>

        <div class="d-flex justify-content-between align-items-center info-title">
            <h2 class="m-0" style="font-size: 22px; font-weight: 700;">Personal Information</h2>
            <button type="button" class="btn-edit" id="editProfileBtn">
                <i class="bi bi-pencil-square me-2"></i>Edit Profile
            </button>
        </div>

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
                    <input type="text" class="form-control editable-field" id="firstName" value="FN" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control editable-field" id="middleName" value="MN" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control editable-field" id="lastName" value="LN" readonly>
                </div>

                <div class="col-12">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white" style="border-radius: 12px 0 0 12px; border-right: none; color: #666;">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <input type="email" class="form-control editable-field" id="email" value="FNLN@gmail.com" readonly
                            style="border-left: none; border-radius: 0 12px 12px 0;">
                    </div>
                </div>

                <div id="securitySection" class="d-none">
                    <div class="col-12 mt-4">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control editable-field" id="password" placeholder="Create a strong password" oninput="checkStrength(this.value)">
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

                    <div class="col-12 mt-3">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control editable-field" id="confirmPassword" placeholder="Re-enter your password">
                            <span class="input-group-text password-toggle" onclick="togglePassword('confirmPassword', 'toggleIcon2')">
                                <i class="bi bi-eye" id="toggleIcon2"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-5 d-none" id="editActionButtons">
                <button type="button" class="btn-primary-custom" id="updateBtn">Update Information</button>
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
        } else {
            editProfileBtn.classList.remove('d-none');
            editActionButtons.classList.add('d-none');
            securitySection.classList.add('d-none');
            editableFields.forEach(field => field.setAttribute('readonly', true));
        }
    }

    editProfileBtn.addEventListener('click', () => toggleEditMode(true));

    cancelBtn.addEventListener('click', function() {
        document.getElementById('firstName').value = originalValues.firstName;
        document.getElementById('middleName').value = originalValues.middleName;
        document.getElementById('lastName').value = originalValues.lastName;
        document.getElementById('email').value = originalValues.email;
        document.getElementById('password').value = "";
        document.getElementById('confirmPassword').value = "";
        toggleEditMode(false);
    });

    updateBtn.addEventListener('click', () => confirmModal.show());

    confirmYes.addEventListener('click', function() {
        confirmModal.hide();
        successMessage.classList.remove('d-none');
        toggleEditMode(false);

        setTimeout(() => {
            successMessage.classList.add('d-none');
        }, 3000);
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