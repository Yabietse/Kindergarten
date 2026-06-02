<?php
session_start(); // Start the session for user authentication

// Include database configuration
// Assuming config.php is one level up from the 'backend' or 'pages' directory where this file might reside.
// Adjust the path if necessary based on your actual file structure.
require_once __DIR__ . '/../config.php';

// Check if a user is logged in. If not, redirect to the home page or login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to your main index/home page
    exit();
}

$loggedIn = true;
$userEmail = $_SESSION['user_email'];
$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Your Account - Kindergarten</title>
    <!-- font awesome cdn link for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- custom css file link - assuming style.css is in the parent directory -->
    <link rel="stylesheet" href="../style.css">
    <!-- custom css file for account manager specific styles -->
    <link rel="stylesheet" href="account_manager.css">
</head>
<body>

    <!-- Header section starts - Re-using existing header structure from index.php -->
    <header class="header">
        <a href="../index.php" class="logo"> <i class="fas fa-school"></i> Kindergarten</a>
        <nav class="navbar">
            <a href="../index.php#home">home</a>
            <a href="../index.php#about">about</a>
            <a href="../index.php#subjects">subjects</a>
            <a href="../index.php#courses">courses</a>
            <a href="../index.php#pricing">pricing</a>
            <a href="../index.php#gallery">gallery</a>
            <a href="../index.php#teachers">teachers</a>
            <a href="../index.php#contact">contact</a>
        </nav>
        <div class="icons">
            <div id="auth-icon" class="fas fa-user"></div>
            <div id="menu-btn" class="fas fa-bars"></div>
        </div>
        <!-- Login/Register Form (hidden by default) - From index.php, just included for consistency -->
        <form action="" class="login-form" id="loginRegisterForm">
            <h3 id="form-title">login form</h3>
            <input type="email" placeholder="enter your email" id="auth-email" class="box">
            <input type="password" placeholder="enter your password" id="auth-password" class="box">
            <p>forget password? <a href="#" id="forgot-password-link">click here</a></p>
            <p id="toggle-auth-mode-text">don't have an account? <a href="#" id="toggle-register-login">register now</a></p>
            <input type="submit" value="login now" id="auth-submit-btn" class="btn">
        </form>

        <!-- User Account Dropdown (hidden by default) - From index.php -->
        <div class="user-account-dropdown" id="userAccountDropdown">
            <p>Logged in as: <span id="dropdown-user-email"><?php echo htmlspecialchars($userEmail); ?></span></p>
            <a href="#" class="btn" id="manage-account-btn">Manage Account</a>
            <a href="#" class="btn" id="sign-out-btn">Sign Out</a>
        </div>
    </header>
    <!-- Header section ends -->

    <!-- Account Manager Section Starts -->
    <section class="account-manager-section" style="padding-top: 10rem;">
        <h1 class="heading">Manage Your <span>Account</span></h1>

        <div class="account-container">

            <!-- Current Profile Information -->
            <div class="account-box profile-info">
                <h3>Your Profile</h3>
                <p><strong>Email:</strong> <span id="current-user-email"><?php echo htmlspecialchars($userEmail); ?></span></p>
                <p><strong>User ID:</strong> <span id="current-user-id"><?php echo htmlspecialchars($userId); ?></span></p>
                <!-- Add other profile details here if available, e.g., Last Login -->
            </div>

            <!-- Change Email Form -->
            <div class="account-box">
                <h3>Change Email</h3>
                <form id="changeEmailForm">
                    <div class="inputBox">
                        <input type="email" placeholder="New Email" id="new-email" class="box" required>
                    </div>
                    <div class="inputBox">
                        <input type="password" placeholder="Current Password" id="email-current-password" class="box" required>
                    </div>
                    <input type="submit" value="Update Email" class="btn">
                    <p class="form-message" id="email-message"></p>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="account-box">
                <h3>Change Password</h3>
                <form id="changePasswordForm">
                    <div class="inputBox">
                        <input type="password" placeholder="Current Password" id="current-password" class="box" required>
                    </div>
                    <div class="inputBox">
                        <input type="password" placeholder="New Password (min 6 chars)" id="new-password" class="box" required>
                    </div>
                    <div class="inputBox">
                        <input type="password" placeholder="Confirm New Password" id="confirm-new-password" class="box" required>
                    </div>
                    <input type="submit" value="Update Password" class="btn">
                    <p class="form-message" id="password-message"></p>
                </form>
            </div>

            <!-- Change Profile Photo Form (Placeholder) -->
            <div class="account-box">
                <h3>Change Profile Photo</h3>
                <form id="changeProfilePhotoForm" enctype="multipart/form-data">
                    <div class="inputBox">
                        <input type="file" id="profile-photo" accept="image/*" class="box">
                    </div>
                    <input type="submit" value="Upload Photo" class="btn">
                    <p class="form-message" id="photo-message"></p>
                    <p class="info-text">Note: Actual photo storage requires server-side setup. This is a demonstration.</p>
                </form>
            </div>

            <!-- Delete Account Section -->
            <div class="account-box delete-account">
                <h3>Delete Account</h3>
                <p>This action is irreversible. Please proceed with caution.</p>
                <form id="deleteAccountForm">
                    <div class="inputBox">
                        <input type="password" placeholder="Enter Password to Confirm" id="delete-account-password" class="box" required>
                    </div>
                    <input type="submit" value="Delete My Account" class="btn delete-btn">
                    <p class="form-message" id="delete-message"></p>
                </form>
            </div>

        </div>

    </section>
    <!-- Account Manager Section Ends -->

    <!-- Footer section (re-using your existing footer structure/styles) -->
    <!-- Assuming your footer is part of index.php's structure, you might want to replicate it here, or include a separate footer.php -->
    <!-- For simplicity, I'm omitting a full footer here, but you can add it if you have a reusable component. -->


    <!-- custom js file link - assuming script.js is in the parent directory -->
    <script src="../script.js"></script>
    <!-- custom js file for account manager specific functionality -->
    <script src="account_manager.js"></script>

    <script>
        // PHP variables made available in JavaScript from the session
        const isLoggedIn = <?php echo json_encode($loggedIn); ?>;
        const currentUserEmail = <?php echo json_encode($userEmail); ?>;
        const currentUserId = <?php echo json_encode($userId); ?>;

        // Initialize Lightgallery if you have a gallery section on this page (unlikely for account manager)
        // document.addEventListener('DOMContentLoaded', () => {
        //     if (document.querySelector('.gallery .gallery-container')) {
        //         lightGallery(document.querySelector('.gallery .gallery-container'));
        //     }
        // });
    </script>

</body>
</html>
