<?php
require_once '../config.php'; // Include database configuration

header('Content-Type: application/json'); // Set header for JSON response

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $response['message'] = "Email and password cannot be empty.";
        echo json_encode($response);
        exit();
    }

    // Prepare a select statement
    $sql = "SELECT id, email, password_hash FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $email, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Password is correct, start a new session
                session_start();
                $_SESSION['user_id'] = $id;
                $_SESSION['user_email'] = $email;

                // Update last login timestamp
                $update_sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?";
                if ($update_stmt = $conn->prepare($update_sql)) {
                    $update_stmt->bind_param("i", $id);
                    $update_stmt->execute();
                    $update_stmt->close();
                }

                $response['success'] = true;
                $response['message'] = "Login successful!";
                $response['user_id'] = $id;
                $response['user_email'] = $email;

                // Log activity: user logged in
                // Call log_activity.php internally or replicate its logic
                $activity_type = "user_logged_in";
                $details = json_encode(['email' => $email]);
                $activity_sql = "INSERT INTO activities (user_id, activity_type, details) VALUES (?, ?, ?)";
                if ($activity_stmt = $conn->prepare($activity_sql)) {
                    $activity_stmt->bind_param("iss", $id, $activity_type, $details);
                    $activity_stmt->execute();
                    $activity_stmt->close();
                }

            } else {
                $response['message'] = "Invalid email or password.";
            }
        } else {
            $response['message'] = "Invalid email or password.";
        }
        $stmt->close();
    } else {
        $response['message'] = "Database prepare error: " . $conn->error;
    }
} else {
    $response['message'] = "Invalid request method.";
}

$conn->close();
echo json_encode($response);
?>
