/**
 * SOMSystem - Reusable JavaScript Components
 */

// ============================================
// SIDEBAR FUNCTIONS
// ============================================
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const arrow = document.getElementById('toggleArrow');
    const mainContent = document.querySelector('.main-content');
    
    if (!sidebar) return;
    
    // Desktop/Tablet: Collapse sidebar
    if (window.innerWidth > 1200) {
        sidebar.classList.toggle('collapsed');
        mainContent?.classList.toggle('sidebar-collapsed');
        
        if (arrow) {
            arrow.classList.toggle('bi-chevron-left');
            arrow.classList.toggle('bi-chevron-right');
        }
    } 
    // Mobile: Slide sidebar
    else {
        sidebar.classList.toggle('show');
    }
}

// Close sidebar when clicking outside (mobile)
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.querySelector('.mobile-menu-btn');
    
    if (window.innerWidth <= 1200 && sidebar && menuBtn) {
        if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    }
});

// ============================================
// NOTIFICATION FUNCTIONS
// ============================================
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

// Close notification dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notificationDropdown');
    const notificationBtn = document.querySelector('.notification-btn');
    
    if (dropdown && notificationBtn) {
        if (!dropdown.contains(event.target) && !notificationBtn.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    }
});

// ============================================
// MODAL FUNCTIONS
// ============================================
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
});

// ============================================
// TAB FUNCTIONS
// ============================================
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    const selectedTab = document.getElementById(tabName);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
    
    // Add active to clicked button
    event.target?.classList.add('active');
}

// ============================================
// FORM VALIDATION
// ============================================
function validateSchoolId(input, role = 'student') {
    const prefix = role === 'student' ? 'SN-' : 'FN-';
    const regex = new RegExp(`^${prefix}\\d{8}$`);
    
    if (!regex.test(input.value)) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        return false;
    } else {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        return true;
    }
}

function validateEmail(input) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!regex.test(input.value)) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        return false;
    } else {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        return true;
    }
}

function validatePassword(input, minLength = 8) {
    if (input.value.length < minLength) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        return false;
    } else {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        return true;
    }
}

// ============================================
// PASSWORD TOGGLE
// ============================================
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (!input || !icon) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// ============================================
// PASSWORD STRENGTH CHECKER
// ============================================
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    return {
        score: strength,
        level: strength <= 1 ? 'weak' : strength <= 2 ? 'medium' : 'strong'
    };
}

// ============================================
// AUTO-FORMAT SCHOOL ID
// ============================================
function formatSchoolId(input, role = 'student') {
    let value = input.value.toUpperCase();
    const prefix = role === 'student' ? 'SN-' : 'FN-';
    
    if (!value.startsWith(prefix)) {
        value = prefix + value.replace(/[^0-9]/g, '');
    }
    
    input.value = value.substring(0, 11);
}

// ============================================
// UTILITY FUNCTIONS
// ============================================
function showAlert(message, type = 'success') {
    // You can use Bootstrap alerts or custom implementation
    alert(message);
}

function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}


