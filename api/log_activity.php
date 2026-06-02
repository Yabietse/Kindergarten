<?php
session_start(); // Start the session to get user ID
require_once '../config.php'; // Include database configuration

header('Content-Type: application/json'); // Set header for JSON response

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'] ?? null;
    $activityType = $_POST['activity_type'] ?? '';
    $details = $_POST['details'] ?? '{}'; // Expect JSON string for details

    if (empty($userId)) {
        $response['message'] = "User not authenticated.";
        echo json_encode($response);
        exit();
    }
    if (empty($activityType)) {
        $response['message'] = "Activity type is required.";
        echo json_encode($response);
        exit();
    }

    // Check if details is valid JSON
    $decodedDetails = json_decode($details);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response['message'] = "Invalid JSON for activity details.";
        echo json_encode($response);
        exit();
    }

    $sql = "INSERT INTO activities (user_id, activity_type, details) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iss", $userId, $activityType, $details); // 'i' for int, 's' for string

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Activity logged successfully.";
        } else {
            $response['message'] = "Error logging activity: " . $stmt->error;
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
