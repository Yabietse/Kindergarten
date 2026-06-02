// Ensure the DOM is fully loaded before attaching event listeners
document.addEventListener('DOMContentLoaded', () => {

    // Get form elements and message display areas
    const changeEmailForm = document.getElementById('changeEmailForm');
    const newEmailInput = document.getElementById('new-email');
    const emailCurrentPasswordInput = document.getElementById('email-current-password');
    const emailMessage = document.getElementById('email-message');
    const currentUserEmailSpan = document.getElementById('current-user-email');
    // const dropdownUserEmailSpan = document.getElementById('dropdown-user-email'); // Not present in this standalone version's header

    const changePasswordForm = document.getElementById('changePasswordForm');
    const currentPasswordInput = document.getElementById('current-password');
    const newPasswordInput = document.getElementById('new-password');
    const confirmNewPasswordInput = document.getElementById('confirm-new-password');
    const passwordMessage = document.getElementById('password-message');

    const changeProfilePhotoForm = document.getElementById('changeProfilePhotoForm');
    const profilePhotoInput = document.getElementById('profile-photo');
    const photoMessage = document.getElementById('photo-message');

    const deleteAccountForm = document.getElementById('deleteAccountForm');
    const deleteAccountPasswordInput = document.getElementById('delete-account-password');
    const deleteMessage = document.getElementById('delete-message');

    // PHP variables made available in JavaScript from the current account_manager.php
    const isLoggedIn = window.isLoggedIn;
    const currentUserEmail = window.currentUserEmail;
    const currentUserId = window.currentUserId;

    /**
     * Displays a message in a specified HTML element.
     * @param {HTMLElement} element - The HTML element to display the message in.
     * @param {string} message - The message text.
     * @param {boolean} isSuccess - True for success messages (green), false for error messages (red).
     */
    function displayMessage(element, message, isSuccess) {
        element.textContent = message;
        element.classList.remove('success', 'error'); // Remove previous classes
        if (isSuccess) {
            element.classList.add('success');
        } else {
            element.classList.add('error');
        }
        // Clear message after a few seconds
        setTimeout(() => {
            element.textContent = '';
            element.classList.remove('success', 'error');
        }, 5000);
    }

    // Function to log activity (paths adjusted for this standalone page)
    async function logUserActivity(activityType, details = {}) {
        if (!currentUserId) {
            console.warn('Cannot log activity: User not authenticated for logging.');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('activity_type', activityType);
            formData.append('details', JSON.stringify(details));

            // *** IMPORTANT CHANGE HERE: PATH TO log_activity.php ***
            // account_manager.js is in /pages/, log_activity.php is in /api/
            // So, from /pages/ to /api/, it's go up one (../) then into api/ (api/)
            const response = await fetch('../api/log_activity.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (!data.success) {
                console.error('Failed to log activity:', data.message);
            }
        } catch (error) {
            console.error('Error logging activity:', error);
        }
    }


    // --- Change Email Form Submission ---
    if (changeEmailForm) {
        changeEmailForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const newEmail = newEmailInput.value;
            const currentPassword = emailCurrentPasswordInput.value;

            if (!newEmail || !currentPassword) {
                displayMessage(emailMessage, 'All fields are required.', false);
                return;
            }

            if (!/\S+@\S+\.\S+/.test(newEmail)) {
                displayMessage(emailMessage, 'Please enter a valid email address.', false);
                return;
            }

            const formData = new FormData();
            formData.append('new_email', newEmail);
            formData.append('current_password', currentPassword);

            try {
                // Path to update_email.php from account_manager.js (which is in pages/)
                const response = await fetch('./backend/update_email.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    displayMessage(emailMessage, data.message, true);
                    // Update displayed email on the page
                    currentUserEmailSpan.textContent = newEmail;
                    // No dropdownUserEmailSpan in this standalone version's header
                    newEmailInput.value = '';
                    emailCurrentPasswordInput.value = '';
                    logUserActivity('email_changed', { old_email: currentUserEmail, new_email: newEmail });
                } else {
                    displayMessage(emailMessage, data.message, false);
                }
            } catch (error) {
                console.error('Error:', error);
                displayMessage(emailMessage, 'An error occurred. Please try again.', false);
            }
        });
    }

    // --- Change Password Form Submission ---
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const currentPassword = currentPasswordInput.value;
            const newPassword = newPasswordInput.value;
            const confirmNewPassword = confirmNewPasswordInput.value;

            if (!currentPassword || !newPassword || !confirmNewPassword) {
                displayMessage(passwordMessage, 'All fields are required.', false);
                return;
            }

            if (newPassword.length < 6) {
                displayMessage(passwordMessage, 'New password must be at least 6 characters long.', false);
                return;
            }

            if (newPassword !== confirmNewPassword) {
                displayMessage(passwordMessage, 'New passwords do not match.', false);
                return;
            }

            const formData = new FormData();
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);

            try {
                // Path to update_password.php from account_manager.js (which is in pages/)
                const response = await fetch('./backend/update_password.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    displayMessage(passwordMessage, data.message, true);
                    currentPasswordInput.value = '';
                    newPasswordInput.value = '';
                    confirmNewPasswordInput.value = '';
                    logUserActivity('password_changed', { user_id: currentUserId });
                } else {
                    displayMessage(passwordMessage, data.message, false);
                }
            } catch (error) {
                console.error('Error:', error);
                displayMessage(passwordMessage, 'An error occurred. Please try again.', false);
            }
        });
    }

    // --- Change Profile Photo Form Submission (Placeholder) ---
    if (changeProfilePhotoForm) {
        changeProfilePhotoForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const photoFile = profilePhotoInput.files[0];
            if (!photoFile) {
                displayMessage(photoMessage, 'Please select a file to upload.', false);
                return;
            }

            console.log('Simulating photo upload for file:', photoFile.name);

            const formData = new FormData();
            formData.append('profile_photo', photoFile);

            try {
                // Path to upload_profile_photo.php from account_manager.js (which is in pages/)
                const response = await fetch('./backend/upload_profile_photo.php', {
                    method: 'POST',
                    body: formData // Send the file
                });
                const data = await response.json();

                if (data.success) {
                    displayMessage(photoMessage, data.message, true);
                    profilePhotoInput.value = ''; // Clear file input
                    logUserActivity('profile_photo_upload_attempt', { file_name: photoFile.name, status: 'success_placeholder' });
                } else {
                    displayMessage(photoMessage, data.message, false);
                    logUserActivity('profile_photo_upload_attempt', { file_name: photoFile.name, status: 'failed', message: data.message });
                }
            } catch (error) {
                console.error('Error uploading photo:', error);
                displayMessage(photoMessage, 'An error occurred during upload. Please try again.', false);
                logUserActivity('profile_photo_upload_attempt', { status: 'error', error: error.message });
            }
        });
    }

    // --- Delete Account Form Submission ---
    if (deleteAccountForm) {
        deleteAccountForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const password = deleteAccountPasswordInput.value;

            if (!password) {
                displayMessage(deleteMessage, 'Please enter your password to confirm.', false);
                return;
            }

            // Custom confirmation dialog (since alert/confirm are disallowed)
            const confirmation = window.confirm("Are you sure you want to delete your account? This action is irreversible.");
            if (!confirmation) {
                displayMessage(deleteMessage, 'Account deletion cancelled.', false);
                return;
            }

            const formData = new FormData();
            formData.append('password', password);

            try {
                // Path to delete_account.php from account_manager.js (which is in pages/)
                const response = await fetch('./backend/delete_account.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    displayMessage(deleteMessage, data.message + ' Redirecting to home...', true);
                    logUserActivity('account_deleted', { email: currentUserEmail, user_id: currentUserId });
                    // Redirect after successful deletion
                    setTimeout(() => {
                        window.location.href = '../index.php'; // Redirect to home page
                    }, 2000);
                } else {
                    displayMessage(deleteMessage, data.message, false);
                }
            } catch (error) {
                console.error('Error:', error);
                displayMessage(deleteMessage, 'An error occurred. Please try again.', false);
            }
        });
    }

});
