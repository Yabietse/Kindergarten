<?php
session_start(); // Start the session to access user ID
require_once __DIR__ . '/../../config.php'; // Adjust path to config.php

header('Content-Type: application/json'); // Set header for JSON response

$response = ['success' => false, 'message' => ''];

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = "User not authenticated.";
    echo json_encode($response);
    exit();
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $userEmail = $_SESSION['user_email']; // Get email for logging purposes
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($password)) {
        $response['message'] = "Password is required to delete account.";
        echo json_encode($response);
        exit();
    }

    // --- Verify current password ---
    $sql_verify = "SELECT password_hash FROM users WHERE id = ?";
    if ($stmt_verify = $conn->prepare($sql_verify)) {
        $stmt_verify->bind_param("i", $userId);
        $stmt_verify->execute();
        $stmt_verify->store_result();
        $stmt_verify->bind_result($hashed_password);
        $stmt_verify->fetch();

        if ($stmt_verify->num_rows == 0 || !password_verify($password, $hashed_password)) {
            $response['message'] = "Incorrect password. Account not deleted.";
            $stmt_verify->close();
            $conn->close();
            echo json_encode($response);
            exit();
        }
        $stmt_verify->close();
    } else {
        $response['message'] = "Database error during password verification: " . $conn->error;
        $conn->close();
        echo json_encode($response);
        exit();
    }

    // --- Delete user's activities first (optional, but good for data integrity) ---
    $sql_delete_activities = "DELETE FROM activities WHERE user_id = ?";
    if ($stmt_del_activities = $conn->prepare($sql_delete_activities)) {
        $stmt_del_activities->bind_param("i", $userId);
        $stmt_del_activities->execute();
        $stmt_del_activities->close();
    } else {
        error_log("Error deleting user activities: " . $conn->error); // Log error but don't stop process
    }


    // --- Delete the user from the database ---
    $sql_delete = "DELETE FROM users WHERE id = ?";
    if ($stmt_delete = $conn->prepare($sql_delete)) {
        $stmt_delete->bind_param("i", $userId);

        if ($stmt_delete->execute()) {
            $response['success'] = true;
            $response['message'] = "Your account has been successfully deleted.";

            // Log activity: account deleted BEFORE session is destroyed
            // This is a special case where we log *before* logging out.
            $activity_type = "account_deleted";
            $details = json_encode(['email' => $userEmail, 'user_id' => $userId]); // Log user_id for audit
            // Re-use connection from config.php for this log
            $activity_sql = "INSERT INTO activities (user_id, activity_type, details) VALUES (?, ?, ?)";
            if ($activity_stmt = $conn->prepare($activity_sql)) {
                // Note: user_id might be invalid after deletion, so log with the existing ID if possible
                // Or consider logging this from a different, less strict context
                $activity_stmt->bind_param("iss", $userId, $activity_type, $details);
                $activity_stmt->execute();
                $activity_stmt->close();
            }

            // Destroy the session after successful deletion
            $_SESSION = array(); // Unset all session variables
            session_destroy(); // Destroy the session

            // Clear session cookies
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

        } else {
            $response['message'] = "Error deleting account: " . $stmt_delete->error;
        }
        $stmt_delete->close();
    } else {
        $response['message'] = "Database prepare error: " . $conn->error;
    }
} else {
    $response['message'] = "Invalid request method.";
}

$conn->close();
echo json_encode($response);
?>
