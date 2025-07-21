// Existing JavaScript (from your previous script.js)
let loginForm = document.querySelector('.login-form');
let navbar = document.querySelector('.navbar');

// Get new elements
const authIcon = document.getElementById('auth-icon'); // The user icon that will toggle login form or user dropdown
const loginRegisterForm = document.getElementById('loginRegisterForm');
const userAccountDropdown = document.getElementById('userAccountDropdown');
const authEmailInput = document.getElementById('auth-email');
const authPasswordInput = document.getElementById('auth-password');
const authSubmitBtn = document.getElementById('auth-submit-btn');
const formTitle = document.getElementById('form-title');
const toggleRegisterLogin = document.getElementById('toggle-register-login');
const forgotPasswordLink = document.getElementById('forgot-password-link');
const toggleAuthModeText = document.getElementById('toggle-auth-mode-text');
const signOutBtn = document.getElementById('sign-out-btn');
const manageAccountBtn = document.getElementById('manage-account-btn');
const manageAccountModal = document.getElementById('manageAccountModal');
const closeManageAccountModalBtn = document.getElementById('closeManageAccountModalBtn');
const managedEmailSpan = document.getElementById('managed-email');

// PHP variables made available in JavaScript from index.php
let isLoggedIn = window.isLoggedIn;
let currentUserEmail = window.currentUserEmail;
let currentUserId = window.currentUserId;

// Function to log user activity to the PHP backend
async function logUserActivity(activityType, details = {}) {
    if (!currentUserId) {
        console.warn("Cannot log activity: User not authenticated.");
        return;
    }
    try {
        const formData = new FormData();
        formData.append('activity_type', activityType);
        formData.append('details', JSON.stringify(details));

        const response = await fetch('api/log_activity.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            console.log(`Activity "${activityType}" logged for user ${currentUserId}`);
        } else {
            console.error("Error logging activity:", result.message);
        }
    } catch (error) {
        console.error("Fetch error logging activity:", error);
    }
}

// Function to update UI based on login status
function updateAuthUI() {
    const userDisplayEmail = document.getElementById('user-display-email');
    const userDisplayId = document.getElementById('user-display-id');

    if (isLoggedIn) {
        loginRegisterForm.classList.remove('active'); // Ensure login form is hidden
        userAccountDropdown.classList.remove('active'); // Ensure dropdown is hidden
        authIcon.style.display = 'block'; // Show user icon

        userDisplayEmail.textContent = currentUserEmail || 'Guest';
        userDisplayId.textContent = currentUserId || '';
    } else {
        loginRegisterForm.classList.remove('active'); // Keep form hidden initially
        userAccountDropdown.classList.remove('active'); // Hide dropdown
        authIcon.style.display = 'block'; // Show login icon (user icon)

        userDisplayEmail.textContent = '';
        userDisplayId.textContent = '';
    }
}

// Initial UI update on page load
document.addEventListener('DOMContentLoaded', updateAuthUI);

// Event listener for the auth icon (replaces original login-btn functionality)
authIcon.onclick = () => {
    // Hide navbar if active
    navbar.classList.remove('active');

    if (isLoggedIn) {
        // User is logged in, show user dropdown
        userAccountDropdown.classList.toggle('active');
        loginRegisterForm.classList.remove('active'); // Hide login form if it was active
    } else {
        // User is not logged in, show login/register form
        loginRegisterForm.classList.toggle('active');
        userAccountDropdown.classList.remove('active'); // Hide dropdown if it was active
    }
};

// Toggle between login and register mode
let isRegisterMode = false; // To toggle between login and register

toggleRegisterLogin.onclick = (e) => {
    e.preventDefault();
    isRegisterMode = !isRegisterMode;
    if (isRegisterMode) {
        formTitle.textContent = 'register now';
        authSubmitBtn.value = 'register now';
        forgotPasswordLink.style.display = 'none';
        toggleAuthModeText.innerHTML = 'already have an account <a href="#" id="toggle-register-login">login now</a>';
        // Re-attach listener as innerHTML rewrites the element
        document.getElementById('toggle-register-login').onclick = toggleRegisterLogin.onclick;
    } else {
        formTitle.textContent = 'login now';
        authSubmitBtn.value = 'login now';
        forgotPasswordLink.style.display = 'block';
        toggleAuthModeText.innerHTML = 'don\'t have an account <a href="#" id="toggle-register-login">register now</a>';
        // Re-attach listener
        document.getElementById('toggle-register-login').onclick = toggleRegisterLogin.onclick;
    }
};


document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
    loginRegisterForm.classList.remove('active');
    userAccountDropdown.classList.remove('active'); // Hide user dropdown if it was active
};

window.onscroll = () => {
    loginRegisterForm.classList.remove('active');
    userAccountDropdown.classList.remove('active'); // Hide user dropdown on scroll
    navbar.classList.remove('active');
};

// Handle login/register form submission
loginRegisterForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = authEmailInput.value;
    const password = authPasswordInput.value;

    if (!email || !password) {
        displayMessage("Please enter both email and password.");
        return;
    }

    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);

    const apiUrl = isRegisterMode ? 'api/register.php' : 'api/login.php';

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.success) {
            displayMessage(result.message, 'success');
            // Update client-side session state
            isLoggedIn = true;
            currentUserEmail = result.user_email;
            currentUserId = result.user_id;
            updateAuthUI(); // Update UI after successful login/registration
            loginRegisterForm.classList.remove('active'); // Hide form
            authEmailInput.value = '';
            authPasswordInput.value = '';
        } else {
            displayMessage(result.message, 'error');
        }
    } catch (error) {
        console.error("Authentication fetch error:", error);
        displayMessage("An unexpected error occurred. Please try again.", 'error');
    }
});

// Handle sign out
signOutBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    try {
        const response = await fetch('api/logout.php', {
            method: 'POST' // Logout usually doesn't need a body
        });
        const result = await response.json();

        if (result.success) {
            displayMessage(result.message, 'success');
            // Update client-side session state
            isLoggedIn = false;
            currentUserEmail = '';
            currentUserId = '';
            updateAuthUI(); // Update UI after logout
            userAccountDropdown.classList.remove('active'); // Hide dropdown
        } else {
            displayMessage(result.message, 'error');
        }
    } catch (error) {
        console.error("Sign out fetch error:", error);
        displayMessage("An unexpected error occurred during sign out. Please try again.", 'error');
    }
});

// Handle manage account
manageAccountBtn.addEventListener('click', (e) => {
    e.preventDefault();
    if (isLoggedIn) {
        managedEmailSpan.textContent = currentUserEmail || 'N/A';
        manageAccountModal.style.display = 'flex'; // Show the manage account modal
        userAccountDropdown.classList.remove('active'); // Hide the dropdown
    } else {
        displayMessage("Please log in to manage your account.", 'info');
    }
});

// Close manage account modal
closeManageAccountModalBtn.addEventListener('click', () => {
    manageAccountModal.style.display = 'none';
});

// Add event listener for clicking outside the manage account modal to close it
window.addEventListener('click', function(event) {
    if (event.target === manageAccountModal) {
        manageAccountModal.style.display = 'none';
    }
});


// Custom Message Box (instead of alert)
function displayMessage(message, type = 'info') {
    const messageBox = document.createElement('div');
    messageBox.classList.add('custom-message-box');
    messageBox.textContent = message;

    if (type === 'error') {
        messageBox.style.backgroundColor = '#f44336';
    } else if (type === 'success') {
        messageBox.style.backgroundColor = '#4CAF50';
    } else { // info
        messageBox.style.backgroundColor = '#2196F3';
    }

    Object.assign(messageBox.style, {
        position: 'fixed',
        top: '20px',
        left: '50%',
        transform: 'translateX(-50%)',
        padding: '15px 25px',
        color: 'white',
        borderRadius: '5px',
        zIndex: '10000',
        opacity: '0',
        transition: 'opacity 0.5s ease-in-out',
        textAlign: 'center'
    });

    document.body.appendChild(messageBox);

    setTimeout(() => {
        messageBox.style.opacity = '1';
    }, 10); // Small delay to trigger transition

    setTimeout(() => {
        messageBox.style.opacity = '0';
        messageBox.addEventListener('transitionend', () => messageBox.remove());
    }, 3000); // Message disappears after 3 seconds
}


/*// --- Video Modal Logic for local videos ---
document.addEventListener('DOMContentLoaded', function() {
    const videoModal = document.getElementById('videoModal');
    const closeVideoModalBtn = document.querySelector('#videoModal .close-button');
    const localVideoPlayer = document.getElementById('local-video-player');
    const educationBoxes = document.querySelectorAll('.education .box');

    educationBoxes.forEach(box => {
        box.style.cursor = 'pointer';
        box.addEventListener('click', function() {
            const videoSrc = this.dataset.videoSrc;
            const lessonName = this.dataset.lessonName; // Get the lesson name

            if (videoSrc) {
                if (localVideoPlayer) localVideoPlayer.src = videoSrc;
                if (localVideoPlayer) localVideoPlayer.play();
                if (videoModal) videoModal.style.display = 'flex';

                // Log video playback activity for logged-in users
                if (isLoggedIn) {
                    logUserActivity("watched_video", { lesson: lessonName, videoSrc: videoSrc });
                }
            }
        });
    });

    if (closeVideoModalBtn) {
        closeVideoModalBtn.addEventListener('click', function() {
            if (localVideoPlayer) localVideoPlayer.pause();
            if (localVideoPlayer) localVideoPlayer.src = '';
            if (videoModal) videoModal.style.display = 'none';
        });
    }*/

    // --- New Text Modal Logic for "Learn More" (Home section) ---
    const learnMoreBtn = document.getElementById('learnMoreBtn');
    const textModal = document.getElementById('textModal');
    const closeTextModalBtn = document.getElementById('closeTextModalBtn');

    if (learnMoreBtn) {
        learnMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (textModal) textModal.style.display = 'flex';
        });
    }
    if (closeTextModalBtn) {
        closeTextModalBtn.addEventListener('click', function() {
            if (textModal) textModal.style.display = 'none';
        });
    }

    // --- New Full About Us Modal Logic for "Read More" (About Us section) ---
    const readMoreAboutBtn = document.getElementById('readMoreAboutBtn');
    const fullAboutModal = document.getElementById('fullAboutModal');
    const closeFullAboutModalBtn = document.getElementById('closeFullAboutModalBtn');

    if (readMoreAboutBtn) {
        readMoreAboutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (fullAboutModal) fullAboutModal.style.display = 'flex';
        });
    }
    if (closeFullAboutModalBtn) {
        closeFullAboutModalBtn.addEventListener('click', function() {
            if (fullAboutModal) fullAboutModal.style.display = 'none';
        });
    }

    // --- Universal click-outside-to-close for all modals ---
    window.addEventListener('click', function(event) {
        if (event.target === textModal) {
            if (textModal) textModal.style.display = 'none';
        }
        if (event.target === videoModal) {
            if (localVideoPlayer) localVideoPlayer.pause();
            if (localVideoPlayer) localVideoPlayer.src = '';
            if (videoModal) videoModal.style.display = 'none';
        }
        if (event.target === fullAboutModal) {
            if (fullAboutModal) fullAboutModal.style.display = 'none';
        }
        // Exclude header elements from closing behavior for login/dropdown
        if (!loginRegisterForm.contains(event.target) && !userAccountDropdown.contains(event.target) && event.target !== authIcon) {
            loginRegisterForm.classList.remove('active');
            userAccountDropdown.classList.remove('active');
        }
    });
/*});*/

