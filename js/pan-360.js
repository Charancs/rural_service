// PAN 360 JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pan360Form');
    const resultContainer = document.getElementById('verificationResult');
    const resultBody = document.getElementById('resultBody');
    const verifyBtn = document.getElementById('verifyBtn');
    
    form.addEventListener('submit', handlePan360);
});

async function handlePan360(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const verifyBtn = document.getElementById('verifyBtn');
    const originalBtnHtml = verifyBtn.innerHTML;
    
    // Show loading state
    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching Details...';
    
    try {
        const response = await fetch('php/pan-api.php?action=pan_360', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            displaySuccess(data);
        } else {
            displayError(data.message || 'Failed to fetch details');
        }
        
    } catch (error) {
        displayError('Network error. Please try again.');
    } finally {
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = originalBtnHtml;
    }
}

function displaySuccess(data) {
    const resultContainer = document.getElementById('verificationResult');
    const resultBody = document.getElementById('resultBody');
    const resultCard = resultContainer.querySelector('.result-card');
    
    resultCard.classList.remove('error');
    resultCard.classList.add('success');
    
    // Format Aadhaar linkage status
    const aadhaarStatus = data.aadhaar_linked 
        ? '<span class="status-success"><i class="fas fa-check-circle"></i> Linked</span>'
        : '<span class="status-warning"><i class="fas fa-exclamation-circle"></i> Not Linked</span>';
    
    resultBody.innerHTML = `
        <div class="result-grid">
            <div class="result-row">
                <span class="result-label"><i class="fas fa-id-card"></i> PAN Number:</span>
                <span class="result-value"><strong>${data.pan}</strong></span>
            </div>
            <div class="result-row">
                <span class="result-label"><i class="fas fa-user"></i> Registered Name:</span>
                <span class="result-value">${data.registered_name}</span>
            </div>
            <div class="result-row">
                <span class="result-label"><i class="fas fa-tag"></i> Type:</span>
                <span class="result-value">${data.type}</span>
            </div>
            <div class="result-row">
                <span class="result-label"><i class="fas fa-venus-mars"></i> Gender:</span>
                <span class="result-value">${data.gender || 'N/A'}</span>
            </div>
            <div class="result-row">
                <span class="result-label"><i class="fas fa-birthday-cake"></i> Date of Birth:</span>
                <span class="result-value">${data.date_of_birth || 'N/A'}</span>
            </div>
            <div class="result-row">
                <span class="result-label"><i class="fas fa-fingerprint"></i> Masked Aadhaar:</span>
                <span class="result-value">${data.masked_aadhaar_number || 'N/A'}</span>
            </div>
            <div class="result-row">
                <span class="result-label"><i class="fas fa-link"></i> Aadhaar Linkage:</span>
                <span class="result-value">${aadhaarStatus}</span>
            </div>
            <div class="result-row">
                <span class="result-label"><i class="fas fa-check-circle"></i> Status:</span>
                <span class="result-value status-success">✓ Verified Successfully</span>
            </div>
            <div class="result-row">
                <span class="result-label"><i class="fas fa-calendar"></i> Verified On:</span>
                <span class="result-value">${new Date().toLocaleString()}</span>
            </div>
        </div>
        
        ${!data.aadhaar_linked ? `
        <div class="alert alert-warning" style="margin-top: 1rem;">
            <i class="fas fa-exclamation-triangle"></i> 
            <strong>Aadhaar Not Linked!</strong> 
            Please link your Aadhaar with PAN to avoid any complications. Visit official income tax portal for linking.
        </div>
        ` : ''}
        
        <div class="alert alert-success" style="margin-top: 1rem;">
            <i class="fas fa-check-circle"></i> ${data.message}
        </div>
    `;
    
    resultContainer.style.display = 'block';
    resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function displayError(message) {
    const resultContainer = document.getElementById('verificationResult');
    const resultBody = document.getElementById('resultBody');
    const resultCard = resultContainer.querySelector('.result-card');
    
    resultCard.classList.remove('success');
    resultCard.classList.add('error');
    
    const resultHeader = resultContainer.querySelector('.result-header i');
    resultHeader.className = 'fas fa-times-circle';
    
    const resultTitle = resultContainer.querySelector('.result-header h3');
    resultTitle.textContent = 'Verification Failed';
    
    resultBody.innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> ${message}
        </div>
        <p style="margin-top: 1rem; color: var(--text-secondary);">
            Please check the PAN number and try again. Make sure it follows the correct format: ABCDE1234F
        </p>
    `;
    
    resultContainer.style.display = 'block';
    resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function resetForm() {
    document.getElementById('pan360Form').reset();
    document.getElementById('verificationResult').style.display = 'none';
    document.getElementById('panNumber').focus();
}
