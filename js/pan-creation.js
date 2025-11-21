// PAN Creation JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('panCreationForm');
    const resultContainer = document.getElementById('creationResult');
    const resultBody = document.getElementById('resultBody');
    const createBtn = document.getElementById('createBtn');
    
    form.addEventListener('submit', handlePanCreation);
});

async function handlePanCreation(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const createBtn = document.getElementById('createBtn');
    const originalBtnHtml = createBtn.innerHTML;
    
    // Show loading state
    createBtn.disabled = true;
    createBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating Link...';
    
    try {
        const response = await fetch('php/pan-api.php?action=pan_creation', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            displaySuccess(data);
        } else {
            displayError(data.message || 'Failed to generate application link');
        }
        
    } catch (error) {
        displayError('Network error. Please try again.');
    } finally {
        createBtn.disabled = false;
        createBtn.innerHTML = originalBtnHtml;
    }
}

function displaySuccess(data) {
    const resultContainer = document.getElementById('creationResult');
    const resultBody = document.getElementById('resultBody');
    const resultCard = resultContainer.querySelector('.result-card');
    
    resultCard.classList.remove('error');
    resultCard.classList.add('success');
    
    resultBody.innerHTML = `
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> ${data.message}
        </div>
        
        <div class="redirect-info">
            <h4><i class="fas fa-external-link-alt"></i> Application Portal Ready</h4>
            <p>Your secure application link has been generated. Click the button below to proceed to the official PAN application portal.</p>
            
            <div class="redirect-url-box">
                <label>Your Application URL:</label>
                <div class="url-display">
                    <input type="text" value="${data.redirect_url}" readonly id="redirectUrl">
                    <button class="btn-copy" onclick="copyUrl()">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
            </div>
            
            <div class="action-buttons">
                <button class="btn-primary btn-large" onclick="openApplicationPortal('${data.redirect_url}')">
                    <i class="fas fa-external-link-alt"></i> Open Application Portal
                </button>
            </div>
            
            <div class="info-points">
                <h5>What to do next:</h5>
                <ul>
                    <li><i class="fas fa-check"></i> Click on "Open Application Portal" button above</li>
                    <li><i class="fas fa-check"></i> Complete your PAN application form</li>
                    <li><i class="fas fa-check"></i> Upload required documents (Aadhaar, photo, signature)</li>
                    <li><i class="fas fa-check"></i> Choose between physical PAN or e-PAN</li>
                    <li><i class="fas fa-check"></i> Make payment and submit your application</li>
                </ul>
            </div>
            
            <div class="alert alert-info" style="margin-top: 1rem;">
                <i class="fas fa-info-circle"></i> 
                <strong>Note:</strong> This link is unique to your mobile number. You can save it and access it later if needed.
            </div>
        </div>
    `;
    
    resultContainer.style.display = 'block';
    resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function displayError(message) {
    const resultContainer = document.getElementById('creationResult');
    const resultBody = document.getElementById('resultBody');
    const resultCard = resultContainer.querySelector('.result-card');
    
    resultCard.classList.remove('success');
    resultCard.classList.add('error');
    
    const resultHeader = resultContainer.querySelector('.result-header i');
    resultHeader.className = 'fas fa-times-circle';
    
    const resultTitle = resultContainer.querySelector('.result-header h3');
    resultTitle.textContent = 'Request Failed';
    
    resultBody.innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> ${message}
        </div>
        <p style="margin-top: 1rem; color: var(--text-secondary);">
            Please check your mobile number and try again. If you've already created a request with this mobile number, the order ID might be duplicate.
        </p>
    `;
    
    resultContainer.style.display = 'block';
    resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function copyUrl() {
    const urlInput = document.getElementById('redirectUrl');
    urlInput.select();
    document.execCommand('copy');
    
    // Show feedback
    const copyBtn = event.target.closest('.btn-copy');
    const originalHtml = copyBtn.innerHTML;
    copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
    copyBtn.style.background = 'var(--success-color)';
    
    setTimeout(() => {
        copyBtn.innerHTML = originalHtml;
        copyBtn.style.background = '';
    }, 2000);
}

function openApplicationPortal(url) {
    if (url) {
        window.open(url, '_blank');
    }
}

function resetForm() {
    document.getElementById('panCreationForm').reset();
    document.getElementById('creationResult').style.display = 'none';
    document.getElementById('mobileNumber').focus();
}
