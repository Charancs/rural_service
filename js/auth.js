// Authentication Logic
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const agentId = document.getElementById('agentId').value.trim();
        const password = document.getElementById('password').value;
        
        // Validate credentials (hardcoded for now)
        // In production, this should be replaced with actual API call
        if (agentId === 'AGT87832' && password === 'NAXZ6700') {
            // Store session data
            sessionStorage.setItem('isLoggedIn', 'true');
            sessionStorage.setItem('agentId', agentId);
            sessionStorage.setItem('agentName', 'Agent');
            
            // Redirect to dashboard
            window.location.href = 'dashboard.html';
        } else {
            alert('Invalid Agent ID or Password. Please try again.');
        }
    });
});
