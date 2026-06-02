<?php
session_start(); // Start the session to access session variables

header('Content-Type: application/json'); // Set header for JSON response

$response = ['success' => false, 'message' => ''];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_email = $_SESSION['user_email'];

    // Log activity: user signed out
    require_once '../config.php'; // Include database configuration for logging
    $activity_type = "user_signed_out";
    $details = json_encode(['email' => $user_email]);
    $activity_sql = "INSERT INTO activities (user_id, activity_type, details) VALUES (?, ?, ?)";
    if ($activity_stmt = $conn->prepare($activity_sql)) {
        $activity_stmt->bind_param("iss", $user_id, $activity_type, $details);
        $activity_stmt->execute();
        $activity_stmt->close();
    }
    $conn->close(); // Close connection after logging

    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Clear session cookies
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    $response['success'] = true;
    $response['message'] = "You have been signed out.";
} else {
    $response['message'] = "No user was logged in.";
}

echo json_encode($response);
?>
