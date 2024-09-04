function validatePassword() {
    const password = document.getElementById("reg-password").value;
    const confirmPassword = document.getElementById("reg-confirm-password").value;
   

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', () => {
    const logoutButton = document.getElementById('logoutButton');

    if (logoutButton) {
        logoutButton.addEventListener('click', () => {
            fetch('logout.php')
                .then(response => {
                    if (response.ok) {
                        // Redirect to index.html after successful logout
                        window.location.href = 'index.html';
                    } else {
                        console.error('Failed to log out.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    }
});
