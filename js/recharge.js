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
});

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
function handleMobileRecharge(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    // Validate mobile number
    if (!/^\d{10}$/.test(data.mobileNumber)) {
        alert('Please enter a valid 10-digit mobile number');
        return;
    }
    
    // Validate amount
    if (parseInt(data.amount) < 10) {
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
    
    // Simulate API call
    // TODO: Replace with actual recharge API integration
    setTimeout(() => {
        const transactionId = 'TXN' + Date.now().toString().slice(-10);
        
        // Save transaction
        saveTransaction({
            type: 'Mobile Recharge',
            ...data,
            transactionId,
            status: 'Success',
            timestamp: new Date().toISOString(),
            agentId: sessionStorage.getItem('agentId')
        });
        
        // Show success modal
        showRechargeSuccess(
            `Mobile recharge of ₹${data.amount} for ${data.mobileNumber} (${data.operator}) completed successfully.`,
            transactionId
        );
        
        // Reset form and button
        e.target.reset();
        btnText.style.display = 'inline';
        loader.style.display = 'none';
        submitBtn.disabled = false;
    }, 2000);
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
