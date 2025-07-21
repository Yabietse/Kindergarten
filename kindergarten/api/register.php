<?php
require_once '../config.php'; // Include database configuration

header('Content-Type: application/json'); // Set header for JSON response

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($email) || empty($password)) {
        $response['message'] = "Email and password cannot be empty.";
        echo json_encode($response);
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Invalid email format.";
        echo json_encode($response);
        exit();
    }
    if (strlen($password) < 6) {
        $response['message'] = "Password must be at least 6 characters long.";
        echo json_encode($response);
        exit();
    }

    // Hash the password securely
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an insert statement
    $sql = "INSERT INTO users (email, password_hash) VALUES (?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $email, $password_hash);

        if ($stmt->execute()) {
            // Registration successful, log user in immediately
            session_start();
            $_SESSION['user_id'] = $conn->insert_id; // Get the ID of the newly inserted user
            $_SESSION['user_email'] = $email;
            $response['success'] = true;
            $response['message'] = "Registration successful!";
            $response['user_id'] = $_SESSION['user_id'];
            $response['user_email'] = $_SESSION['user_email'];

            // Log activity: user registered
            // Call log_activity.php internally or replicate its logic
            $activity_type = "user_registered";
            $details = json_encode(['email' => $email]);
            $activity_sql = "INSERT INTO activities (user_id, activity_type, details) VALUES (?, ?, ?)";
            if ($activity_stmt = $conn->prepare($activity_sql)) {
                $activity_stmt->bind_param("iss", $_SESSION['user_id'], $activity_type, $details);
                $activity_stmt->execute();
                $activity_stmt->close();
            }

        } else {
            if ($conn->errno == 1062) { // MySQL error code for duplicate entry
                $response['message'] = "This email is already registered.";
            } else {
                $response['message'] = "Error: Could not register user. " . $stmt->error;
            }
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
