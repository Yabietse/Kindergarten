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
    $newEmail = filter_var($_POST['new_email'] ?? '', FILTER_SANITIZE_EMAIL);
    $currentPassword = $_POST['current_password'] ?? '';

    // Validate inputs
    if (empty($newEmail) || empty($currentPassword)) {
        $response['message'] = "New email and current password cannot be empty.";
        echo json_encode($response);
        exit();
    }
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Invalid new email format.";
        echo json_encode($response);
        exit();
    }

    // --- Verify current password ---
    // First, fetch the current user's hashed password from the database
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

    // --- Check if the new email already exists (for another user) ---
    $sql_check_email = "SELECT id FROM users WHERE email = ? AND id != ?";
    if ($stmt_check_email = $conn->prepare($sql_check_email)) {
        $stmt_check_email->bind_param("si", $newEmail, $userId);
        $stmt_check_email->execute();
        $stmt_check_email->store_result();
        if ($stmt_check_email->num_rows > 0) {
            $response['message'] = "This email is already taken by another account.";
            $stmt_check_email->close();
            $conn->close();
            echo json_encode($response);
            exit();
        }
        $stmt_check_email->close();
    } else {
        $response['message'] = "Database error during email check: " . $conn->error;
        $conn->close();
        echo json_encode($response);
        exit();
    }

    // --- Update the email in the database ---
    $sql_update = "UPDATE users SET email = ? WHERE id = ?";
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param("si", $newEmail, $userId);

        if ($stmt_update->execute()) {
            // Update session email
            $_SESSION['user_email'] = $newEmail;

            $response['success'] = true;
            $response['message'] = "Email updated successfully!";

            // Log activity: email changed
            $activity_type = "email_changed";
            $details = json_encode(['old_email' => $_SESSION['user_email'], 'new_email' => $newEmail]);
            $activity_sql = "INSERT INTO activities (user_id, activity_type, details) VALUES (?, ?, ?)";
            if ($activity_stmt = $conn->prepare($activity_sql)) {
                $activity_stmt->bind_param("iss", $userId, $activity_type, $details);
                $activity_stmt->execute();
                $activity_stmt->close();
            }

        } else {
            $response['message'] = "Error updating email: " . $stmt_update->error;
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
