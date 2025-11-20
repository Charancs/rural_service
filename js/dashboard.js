// Dashboard Logic
document.addEventListener('DOMContentLoaded', function() {
    // Logout functionality
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
    
    // Mobile menu toggle
    setupMobileMenu();
});

// Navigate to service page
function navigateToService(service) {
    switch(service) {
        case 'pan-apply':
            window.location.href = 'pan-apply.php';
            break;
        case 'pan-verify':
            window.location.href = 'pan-verify.php';
            break;
        case 'recharge':
            window.location.href = 'recharge.php';
            break;
    }
}

// Logout function
function logout() {
    sessionStorage.clear();
    localStorage.clear();
    window.location.href = 'php/logout.php';
}

// Mobile menu toggle
function setupMobileMenu() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.nav-container')) {
                navMenu.classList.remove('active');
            }
        });
    }
}
