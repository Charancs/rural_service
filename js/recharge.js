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
    
    if (!/^[6-9]\d{9}$/.test(mobile)) {
        return;
    }
    
    const operatorInput = document.getElementById('operator');
    const operatorCodeInput = document.getElementById('operatorCode');
    const circleInput = document.getElementById('circle');
    const circleCodeInput = document.getElementById('circleCode');
    const quickAmountsDiv = document.querySelector('.quick-amounts');
    
    // Show loading in quick amounts section
    if (quickAmountsDiv) {
        quickAmountsDiv.innerHTML = '<div style="text-align: center; padding: 1rem; color: var(--text-secondary);"><i class="fas fa-spinner fa-spin"></i> Fetching recharge plans...</div>';
    }
    
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
            // Store hidden values (don't show to user)
            operatorInput.value = result.data.operator;
            operatorCodeInput.value = result.data.operatorCode;
            circleInput.value = result.data.circle;
            circleCodeInput.value = result.data.circleCode;
            
            // Fetch and display plans automatically
            await fetchOperatorPlans(mobile, result.data.operatorCode, result.data.circleCode);
        } else {
            if (quickAmountsDiv) {
                quickAmountsDiv.innerHTML = '';
            }
            showNotification(result.message || 'Failed to detect operator', 'error');
        }
    } catch (error) {
        console.error('Error detecting operator:', error);
        if (quickAmountsDiv) {
            quickAmountsDiv.innerHTML = '';
        }
        showNotification('Error detecting operator', 'error');
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
        quickAmountsDiv.innerHTML = '<div style="text-align: center; padding: 1rem; color: var(--text-secondary);">No plans available</div>';
        return;
    }
    
    // Clear existing content
    quickAmountsDiv.innerHTML = '';
    
    // Display all plan categories
    const categories = ['TOPUP', 'DATA', 'SMS', 'Romaing', 'COMBO', 'FRC', 'STV', 'BSNLValidExtension'];
    
    categories.forEach(category => {
        if (plans[category] && Array.isArray(plans[category]) && plans[category].length > 0) {
            // Create category header
            const categoryHeader = document.createElement('h4');
            categoryHeader.textContent = category.replace(/([A-Z])/g, ' $1').trim();
            categoryHeader.style.cssText = 'margin: 1.5rem 0 0.75rem 0; color: var(--text-primary); font-size: 1rem;';
            quickAmountsDiv.appendChild(categoryHeader);
            
            // Create plans grid
            const plansGrid = document.createElement('div');
            plansGrid.style.cssText = 'display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; margin-bottom: 1rem;';
            
            plans[category].forEach(plan => {
                const planCard = document.createElement('div');
                planCard.className = 'plan-card';
                planCard.style.cssText = 'border: 2px solid var(--border-color); border-radius: 8px; padding: 0.75rem; cursor: pointer; transition: all 0.3s;';
                planCard.innerHTML = `
                    <div style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 0.25rem;">₹${plan.rs}</div>
                    <div style="font-size: 0.85rem; color: var(--success-color); margin-bottom: 0.25rem;">${plan.validity || 'NA'}</div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary); line-height: 1.3;">${plan.desc || ''}</div>
                `;
                
                planCard.addEventListener('click', () => {
                    // Remove previous selection
                    document.querySelectorAll('.plan-card').forEach(card => {
                        card.style.borderColor = 'var(--border-color)';
                        card.style.background = 'transparent';
                    });
                    // Highlight selected
                    planCard.style.borderColor = 'var(--primary-color)';
                    planCard.style.background = 'rgba(99, 102, 241, 0.05)';
                    // Set amount
                    document.getElementById('amount').value = plan.rs;
                });
                
                planCard.addEventListener('mouseenter', () => {
                    if (planCard.style.borderColor !== 'var(--primary-color)') {
                        planCard.style.borderColor = 'var(--primary-dark)';
                    }
                });
                
                planCard.addEventListener('mouseleave', () => {
                    if (planCard.style.borderColor !== 'var(--primary-color)') {
                        planCard.style.borderColor = 'var(--border-color)';
                    }
                });
                
                plansGrid.appendChild(planCard);
            });
            
            quickAmountsDiv.appendChild(plansGrid);
        }
    });
    
    if (quickAmountsDiv.children.length === 0) {
        quickAmountsDiv.innerHTML = '<div style="text-align: center; padding: 1rem; color: var(--text-secondary);">No plans available for this operator</div>';
    }
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
    const amount = document.getElementById('amount').value;
    
    // Validate mobile number
    if (!/^[6-9]\d{9}$/.test(mobile)) {
        showNotification('Please enter a valid mobile number', 'error');
        return;
    }
    
    // Validate amount
    if (!amount || parseInt(amount) < 10) {
        showNotification('Please select a recharge plan', 'error');
        return;
    }
    
    // Validate operator
    if (!operator) {
        showNotification('Operator not detected. Please re-enter mobile number', 'error');
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
        // Store transaction locally (actual recharge API not yet integrated)
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
