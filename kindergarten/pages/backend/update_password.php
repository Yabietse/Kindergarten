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
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    // Validate inputs
    if (empty($currentPassword) || empty($newPassword)) {
        $response['message'] = "Current and new passwords cannot be empty.";
        echo json_encode($response);
        exit();
    }
    if (strlen($newPassword) < 6) {
        $response['message'] = "New password must be at least 6 characters long.";
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

        if ($stmt_verify->num_rows == 0 || !password_verify($currentPassword, $hashed_password)) {
            $response['message'] = "Incorrect current password.";
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

    // --- Hash the new password and update in the database ---
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql_update = "UPDATE users SET password_hash = ? WHERE id = ?";
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param("si", $newPasswordHash, $userId);

        if ($stmt_update->execute()) {
            $response['success'] = true;
            $response['message'] = "Password updated successfully!";

            // Log activity: password changed
            $activity_type = "password_changed";
            $details = json_encode(['user_id' => $userId]); // Don't log the password itself
            $activity_sql = "INSERT INTO activities (user_id, activity_type, details) VALUES (?, ?, ?)";
            if ($activity_stmt = $conn->prepare($activity_sql)) {
                $activity_stmt->bind_param("iss", $userId, $activity_type, $details);
                $activity_stmt->execute();
                $activity_stmt->close();
            }

        } else {
            $response['message'] = "Error updating password: " . $stmt_update->error;
        }
        $stmt_update->close();
    } else {
        $response['message'] = "Database prepare error: " . $conn->error;
    }
} else {
    $response['message'] = "Invalid request method.";
}

$conn->close();
echo json_encode($response);
?>
