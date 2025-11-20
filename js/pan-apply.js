// PAN Apply Logic
document.addEventListener('DOMContentLoaded', function() {
    // Setup mobile menu and logout
    setupMobileMenu();
    setupLogout();
    
    // Form submission
    const panApplyForm = document.getElementById('panApplyForm');
    panApplyForm.addEventListener('submit', handlePanApply);
    
    // Auto-fill full name based on first, middle, last name
    setupNameAutoFill();
});

function handlePanApply(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    // Validate form
    if (!validatePanApplyForm(data)) {
        return;
    }
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="loader"></span> Processing...';
    submitBtn.disabled = true;
    
    // Simulate API call
    // TODO: Replace with actual API integration
    setTimeout(() => {
        // Generate reference number
        const refNumber = 'PAN' + Date.now().toString().slice(-8);
        
        // Store application data (in production, this would be sent to server)
        const applicationData = {
            ...data,
            referenceNumber: refNumber,
            status: 'Pending',
            appliedDate: new Date().toISOString(),
            agentId: sessionStorage.getItem('agentId')
        };
        
        // Save to localStorage for demo purposes
        saveApplication(applicationData);
        
        // Show success modal
        showSuccessModal(refNumber);
        
        // Reset form
        e.target.reset();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 2000);
}

function validatePanApplyForm(data) {
    // Validate Aadhaar number
    if (!/^\d{12}$/.test(data.aadhaar)) {
        alert('Please enter a valid 12-digit Aadhaar number');
        return false;
    }
    
    // Validate mobile number
    if (!/^\d{10}$/.test(data.mobile)) {
        alert('Please enter a valid 10-digit mobile number');
        return false;
    }
    
    // Validate pincode
    if (!/^\d{6}$/.test(data.pincode)) {
        alert('Please enter a valid 6-digit pincode');
        return false;
    }
    
    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        alert('Please enter a valid email address');
        return false;
    }
    
    // Validate file uploads
    const photo = document.getElementById('photo').files[0];
    const signature = document.getElementById('signature').files[0];
    const aadhaarDoc = document.getElementById('aadhaarDoc').files[0];
    
    if (!photo || !signature || !aadhaarDoc) {
        alert('Please upload all required documents');
        return false;
    }
    
    // Check file sizes
    if (photo.size > 2 * 1024 * 1024) {
        alert('Photo size should not exceed 2MB');
        return false;
    }
    
    if (signature.size > 1 * 1024 * 1024) {
        alert('Signature size should not exceed 1MB');
        return false;
    }
    
    if (aadhaarDoc.size > 5 * 1024 * 1024) {
        alert('Aadhaar document size should not exceed 5MB');
        return false;
    }
    
    return true;
}

function setupNameAutoFill() {
    const firstName = document.getElementById('firstName');
    const middleName = document.getElementById('middleName');
    const lastName = document.getElementById('lastName');
    const applicantName = document.getElementById('applicantName');
    
    const updateFullName = () => {
        const parts = [
            firstName.value.trim(),
            middleName.value.trim(),
            lastName.value.trim()
        ].filter(part => part !== '');
        
        applicantName.value = parts.join(' ');
    };
    
    firstName.addEventListener('input', updateFullName);
    middleName.addEventListener('input', updateFullName);
    lastName.addEventListener('input', updateFullName);
}

function saveApplication(data) {
    // Get existing applications
    let applications = JSON.parse(localStorage.getItem('panApplications') || '[]');
    applications.push(data);
    localStorage.setItem('panApplications', JSON.stringify(applications));
}

function showSuccessModal(refNumber) {
    const modal = document.getElementById('successModal');
    document.getElementById('refNumber').textContent = refNumber;
    modal.classList.add('show');
}

function closeModal() {
    const modal = document.getElementById('successModal');
    modal.classList.remove('show');
    window.location.href = 'dashboard.html';
}

// Utility functions
function setupLogout() {
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = 'php/logout.php';
        });
    }
}

// Mobile menu toggle
function setupMobileMenu() {
    const navToggle = document.getElementById('navToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (navToggle && sidebar) {
        navToggle.addEventListener('click', function() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
        });
        
        if (sidebarClose) {
            sidebarClose.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        }
    }
}
