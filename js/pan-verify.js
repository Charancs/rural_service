// PAN Verification Logic
document.addEventListener('DOMContentLoaded', function() {
    // Setup mobile menu and logout
    setupMobileMenu();
    setupLogout();
    
    // Form submission
    const panVerifyForm = document.getElementById('panVerifyForm');
    panVerifyForm.addEventListener('submit', handlePanVerify);
    
    // Auto uppercase PAN number
    const panInput = document.getElementById('panNumber');
    panInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
});

function handlePanVerify(e) {
    e.preventDefault();
    
    // Get form data
    const panNumber = document.getElementById('panNumber').value.toUpperCase();
    const name = document.getElementById('verifyName').value.trim();
    const dob = document.getElementById('verifyDob').value;
    
    // Validate PAN format
    const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
    if (!panRegex.test(panNumber)) {
        alert('Please enter a valid PAN number (Format: ABCDE1234F)');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    const btnText = document.getElementById('verifyBtnText');
    const loader = document.getElementById('verifyLoader');
    
    btnText.style.display = 'none';
    loader.style.display = 'inline-block';
    submitBtn.disabled = true;
    
    // Hide any previous results
    document.getElementById('verificationResult').style.display = 'none';
    
    // Simulate API call
    // TODO: Replace with actual PAN verification API
    setTimeout(() => {
        // Mock verification result
        const isValid = mockPanVerification(panNumber, name, dob);
        
        // Show result
        displayVerificationResult(isValid, panNumber, name, dob);
        
        // Reset button
        btnText.style.display = 'inline';
        loader.style.display = 'none';
        submitBtn.disabled = false;
    }, 2000);
}

function mockPanVerification(panNumber, name, dob) {
    // Mock validation logic
    // In production, this should call actual PAN verification API
    
    // Simple mock: PAN starting with 'A' is valid
    return panNumber.charAt(0) === 'A';
}

function displayVerificationResult(isValid, panNumber, name, dob) {
    const resultDiv = document.getElementById('verificationResult');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultBody = document.getElementById('resultBody');
    const resultHeader = resultDiv.querySelector('.result-header');
    
    if (isValid) {
        // Success result
        resultHeader.classList.remove('error');
        resultIcon.textContent = '✓';
        resultTitle.textContent = 'PAN Verified Successfully';
        
        resultBody.innerHTML = `
            <div class="result-row">
                <span class="result-label">PAN Number:</span>
                <span class="result-value">${panNumber}</span>
            </div>
            <div class="result-row">
                <span class="result-label">Name:</span>
                <span class="result-value">${name}</span>
            </div>
            <div class="result-row">
                <span class="result-label">Date of Birth:</span>
                <span class="result-value">${formatDate(dob)}</span>
            </div>
            <div class="result-row">
                <span class="result-label">Status:</span>
                <span class="result-value" style="color: var(--success-color);">✓ Valid & Active</span>
            </div>
            <div class="result-row">
                <span class="result-label">Verified On:</span>
                <span class="result-value">${new Date().toLocaleString()}</span>
            </div>
        `;
    } else {
        // Error result
        resultHeader.classList.add('error');
        resultIcon.textContent = '✗';
        resultTitle.textContent = 'Verification Failed';
        
        resultBody.innerHTML = `
            <div class="result-row">
                <span class="result-label">PAN Number:</span>
                <span class="result-value">${panNumber}</span>
            </div>
            <div class="result-row">
                <span class="result-label">Status:</span>
                <span class="result-value" style="color: var(--danger-color);">✗ Invalid or Not Found</span>
            </div>
            <div class="result-row">
                <span class="result-label">Message:</span>
                <span class="result-value">The PAN details provided do not match our records. Please verify the information and try again.</span>
            </div>
            <div class="result-row">
                <span class="result-label">Attempted On:</span>
                <span class="result-value">${new Date().toLocaleString()}</span>
            </div>
        `;
    }
    
    // Show result
    resultDiv.style.display = 'block';
    
    // Scroll to result
    resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Save verification history
    saveVerificationHistory({
        panNumber,
        name,
        dob,
        isValid,
        verifiedAt: new Date().toISOString(),
        agentId: sessionStorage.getItem('agentId')
    });
}

function resetVerification() {
    document.getElementById('panVerifyForm').reset();
    document.getElementById('verificationResult').style.display = 'none';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

function saveVerificationHistory(data) {
    // Get existing history
    let history = JSON.parse(localStorage.getItem('panVerificationHistory') || '[]');
    history.push(data);
    
    // Keep only last 50 records
    if (history.length > 50) {
        history = history.slice(-50);
    }
    
    localStorage.setItem('panVerificationHistory', JSON.stringify(history));
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

function setupMobileMenu() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
}
