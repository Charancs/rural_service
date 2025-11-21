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
    
    // Setup sidebar toggle
    setupSidebar();
});

// Navigate to service page
function navigateToService(service) {
    switch(service) {
        case 'pan-apply':
            window.location.href = 'pan-apply.php';
            break;
        case 'pan-verify':
            window.location.href = 'pan-verification.php';
            break;
        case 'pan-360':
            window.location.href = 'pan-360.php';
            break;
        case 'pan-creation':
            window.location.href = 'pan-creation.php';
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

// Setup sidebar functionality
function setupSidebar() {
    const navToggle = document.getElementById('navToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (navToggle && sidebar) {
        // Open sidebar
        navToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        // Close sidebar
        if (sidebarClose) {
            sidebarClose.addEventListener('click', function() {
                closeSidebar();
            });
        }
        
        // Close sidebar when clicking overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                closeSidebar();
            });
        }
        
        // Close sidebar when clicking a link
        const sidebarLinks = sidebar.querySelectorAll('a:not(.menu-section-title)');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Don't close immediately to allow navigation
                setTimeout(closeSidebar, 100);
            });
        });
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (sidebar) sidebar.classList.remove('active');
    if (sidebarOverlay) sidebarOverlay.classList.remove('active');
    document.body.style.overflow = '';
}

// Toggle submenu dropdown
function toggleSubmenu(element) {
    const submenu = element.nextElementSibling;
    const arrow = element.querySelector('.submenu-arrow');
    
    if (submenu && submenu.classList.contains('submenu')) {
        if (submenu.style.display === 'none' || submenu.style.display === '') {
            submenu.style.display = 'block';
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        } else {
            submenu.style.display = 'none';
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        }
    }
}
