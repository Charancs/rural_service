// Recharge Logic
document.addEventListener('DOMContentLoaded', function() {
    // Setup mobile menu and logout
    setupMobileMenu();
    setupLogout();
    
    // Form submissions
    const mobileRechargeForm = document.getElementById('mobileRechargeForm');
    const dthRechargeForm = document.getElementById('dthRechargeForm');
    
    mobileRechargeForm.addEventListener('submit', handleMobileRecharge);
    dthRechargeForm.addEventListener('submit', handleDthRecharge);
    
    // Auto-detect operator when mobile number is entered
    const mobileNumberInput = document.getElementById('mobileNumber');
    mobileNumberInput.addEventListener('blur', autoDetectOperator);
});

// Auto-detect operator
async function autoDetectOperator(e) {
    const mobile = e.target.value.trim();
    
    if (!/^\d{10}$/.test(mobile)) {
        return;
    }
    
    const operatorSelect = document.getElementById('operator');
    const circleSelect = document.getElementById('circle');
    
    // Show loading state
    operatorSelect.disabled = true;
    circleSelect.disabled = true;
    operatorSelect.innerHTML = '<option value="">Detecting operator...</option>';
    
    try {
        const formData = new FormData();
        formData.append('action', 'fetch_operator');
        formData.append('mobile', mobile);
        
        const response = await fetch('php/recharge-api.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Set operator
            operatorSelect.innerHTML = `<option value="${result.data.operatorCode}">${result.data.operator}</option>`;
            operatorSelect.value = result.data.operatorCode;
            
            // Set circle
            circleSelect.value = result.data.circleCode;
            
            // Store operator code and circle code for plan fetching
            operatorSelect.dataset.opcode = result.data.operatorCode;
            circleSelect.dataset.circleCode = result.data.circleCode;
            
            // Fetch plans
            await fetchOperatorPlans(mobile, result.data.operatorCode, result.data.circleCode);
            
            // Show success message
            showNotification('Operator detected: ' + result.data.operator, 'success');
        } else {
            operatorSelect.innerHTML = '<option value="">Select Operator</option>';
            showNotification(result.message || 'Failed to detect operator', 'error');
        }
    } catch (error) {
        console.error('Error detecting operator:', error);
        operatorSelect.innerHTML = '<option value="">Select Operator</option>';
        showNotification('Error detecting operator', 'error');
    } finally {
        operatorSelect.disabled = false;
        circleSelect.disabled = false;
    }
}

// Fetch operator plans
async function fetchOperatorPlans(mobile, opcode, circle) {
    try {
        const formData = new FormData();
        formData.append('action', 'fetch_plans');
        formData.append('mobile', mobile);
        formData.append('opcode', opcode);
        formData.append('circle', circle);
        
        const response = await fetch('php/recharge-api.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success && result.data) {
            displayRechargePlans(result.data);
        }
    } catch (error) {
        console.error('Error fetching plans:', error);
    }
}

// Display recharge plans
function displayRechargePlans(plans) {
    const quickAmountsDiv = document.querySelector('.quick-amounts');
    
    if (!plans || Object.keys(plans).length === 0) {
        return;
    }
    
    // Clear existing quick amounts
    quickAmountsDiv.innerHTML = '';
    
    // Get popular plans from different categories
    const popularPlans = [];
    
    // Add from TOPUP
    if (plans.TOPUP && plans.TOPUP.length > 0) {
        popularPlans.push(...plans.TOPUP.slice(0, 2));
    }
    
    // Add from COMBO or STV
    if (plans.COMBO && plans.COMBO.length > 0) {
        popularPlans.push(...plans.COMBO.slice(0, 2));
    } else if (plans.STV && plans.STV.length > 0) {
        popularPlans.push(...plans.STV.slice(0, 2));
    }
    
    // Add from DATA
    if (plans.DATA && plans.DATA.length > 0) {
        popularPlans.push(plans.DATA[0]);
    }
    
    // Display up to 6 popular plans
    popularPlans.slice(0, 6).forEach(plan => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'amount-btn';
        button.onclick = () => setAmount(plan.rs);
        button.innerHTML = `₹${plan.rs}`;
        button.title = plan.desc || plan.validity || '';
        quickAmountsDiv.appendChild(button);
    });
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Tab switching
function switchTab(tab) {
    const mobileForm = document.getElementById('mobileRechargeForm');
    const dthForm = document.getElementById('dthRechargeForm');
    const tabBtns = document.querySelectorAll('.tab-btn');
    
    if (tab === 'mobile') {
        mobileForm.classList.add('active');
        dthForm.classList.remove('active');
        tabBtns[0].classList.add('active');
        tabBtns[1].classList.remove('active');
    } else {
        dthForm.classList.add('active');
        mobileForm.classList.remove('active');
        tabBtns[1].classList.add('active');
        tabBtns[0].classList.remove('active');
    }
}

// Set amount for mobile recharge
function setAmount(amount) {
    document.getElementById('amount').value = amount;
}

// Set amount for DTH recharge
function setDthAmount(amount) {
    document.getElementById('dthAmount').value = amount;
}

// Handle mobile recharge
async function handleMobileRecharge(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const mobile = formData.get('mobileNumber');
    const operator = document.getElementById('operator').value;
    const circle = document.getElementById('circle').value;
    const amount = formData.get('amount');
    
    // Validate mobile number
    if (!/^\d{10}$/.test(mobile)) {
        alert('Please enter a valid 10-digit mobile number');
        return;
    }
    
    // Validate amount
    if (parseInt(amount) < 10) {
        alert('Minimum recharge amount is ₹10');
        return;
    }
    
    // Show loading state
    const btnText = document.getElementById('mobileRechargeBtnText');
    const loader = document.getElementById('mobileRechargeLoader');
    const submitBtn = e.target.querySelector('button[type="submit"]');
    
    btnText.style.display = 'none';
    loader.style.display = 'inline-block';
    submitBtn.disabled = true;
    
    try {
        const rechargeData = new FormData();
        rechargeData.append('action', 'process_recharge');
        rechargeData.append('mobile', mobile);
        rechargeData.append('operator', operator);
        rechargeData.append('circle', circle);
        rechargeData.append('amount', amount);
        
        const response = await fetch('php/recharge-api.php', {
            method: 'POST',
            body: rechargeData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success modal
            showRechargeSuccess(
                `Mobile recharge of ₹${amount} for ${mobile} completed successfully.`,
                result.transactionId
            );
            
            // Reset form
            e.target.reset();
            document.querySelector('.quick-amounts').innerHTML = `
                <button type="button" class="amount-btn" onclick="setAmount(99)">₹99</button>
                <button type="button" class="amount-btn" onclick="setAmount(199)">₹199</button>
                <button type="button" class="amount-btn" onclick="setAmount(299)">₹299</button>
                <button type="button" class="amount-btn" onclick="setAmount(499)">₹499</button>
                <button type="button" class="amount-btn" onclick="setAmount(999)">₹999</button>
            `;
        } else {
            alert(result.message || 'Recharge failed. Please try again.');
        }
    } catch (error) {
        console.error('Error processing recharge:', error);
        alert('An error occurred. Please try again.');
    } finally {
        btnText.style.display = 'inline';
        loader.style.display = 'none';
        submitBtn.disabled = false;
    }
}

// Handle DTH recharge
function handleDthRecharge(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    // Validate amount
    if (parseInt(data.dthAmount) < 100) {
        alert('Minimum DTH recharge amount is ₹100');
        return;
    }
    
    // Show loading state
    const btnText = document.getElementById('dthRechargeBtnText');
    const loader = document.getElementById('dthRechargeLoader');
    const submitBtn = e.target.querySelector('button[type="submit"]');
    
    btnText.style.display = 'none';
    loader.style.display = 'inline-block';
    submitBtn.disabled = true;
    
    // Simulate API call
    // TODO: Replace with actual recharge API integration
    setTimeout(() => {
        const transactionId = 'TXN' + Date.now().toString().slice(-10);
        
        // Save transaction
        saveTransaction({
            type: 'DTH Recharge',
            subscriberId: data.dthNumber,
            operator: data.dthOperator,
            amount: data.dthAmount,
            transactionId,
            status: 'Success',
            timestamp: new Date().toISOString(),
            agentId: sessionStorage.getItem('agentId')
        });
        
        // Show success modal
        showRechargeSuccess(
            `DTH recharge of ₹${data.dthAmount} for subscriber ${data.dthNumber} (${data.dthOperator}) completed successfully.`,
            transactionId
        );
        
        // Reset form and button
        e.target.reset();
        btnText.style.display = 'inline';
        loader.style.display = 'none';
        submitBtn.disabled = false;
    }, 2000);
}

function showRechargeSuccess(message, transactionId) {
    const modal = document.getElementById('rechargeSuccessModal');
    document.getElementById('rechargeMessage').textContent = message;
    document.getElementById('transactionId').textContent = transactionId;
    modal.classList.add('show');
}

function closeRechargeModal() {
    const modal = document.getElementById('rechargeSuccessModal');
    modal.classList.remove('show');
}

function saveTransaction(data) {
    // Get existing transactions
    let transactions = JSON.parse(localStorage.getItem('rechargeTransactions') || '[]');
    transactions.push(data);
    
    // Keep only last 100 transactions
    if (transactions.length > 100) {
        transactions = transactions.slice(-100);
    }
    
    localStorage.setItem('rechargeTransactions', JSON.stringify(transactions));
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
