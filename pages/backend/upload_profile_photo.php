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

// Check if the request method is POST and a file was uploaded
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_photo'])) {
    $userId = $_SESSION['user_id'];
    $userEmail = $_SESSION['user_email'];

    $file = $_FILES['profile_photo'];

    // --- Placeholder for actual file upload logic ---
    // In a real application, you would:
    // 1. Validate file type (e.g., image/jpeg, image/png) and size.
    // 2. Define a secure upload directory outside the web root.
    // 3. Generate a unique filename to prevent overwrites and security issues.
    // 4. Move the uploaded file from temporary location to your designated directory using move_uploaded_file().
    // 5. Store the file path/URL in the 'users' table (e.g., in a 'profile_photo_url' column).
    // 6. Handle errors like upload limits, invalid files, etc.

    // For this demonstration, we'll just acknowledge the upload and log the activity.
    if ($file['error'] === UPLOAD_ERR_OK) {
        $response['success'] = true;
        $response['message'] = "Profile photo received. (Actual storage and update to database would happen here)";

        // Log activity: profile photo upload attempt
        $activity_type = "profile_photo_upload";
        $details = json_encode([
            'file_name' => $file['name'],
            'file_type' => $file['type'],
            'file_size' => $file['size'],
            'status' => 'received_placeholder'
        ]);
        $activity_sql = "INSERT INTO activities (user_id, activity_type, details) VALUES (?, ?, ?)";
        if ($activity_stmt = $conn->prepare($activity_sql)) {
            $activity_stmt->bind_param("iss", $userId, $activity_type, $details);
            $activity_stmt->execute();
            $activity_stmt->close();
        }

    } else {
        $response['message'] = "Error uploading file: " . $file['error'];
        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $response['message'] = "Uploaded file exceeds maximum size.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $response['message'] = "File upload was incomplete.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $response['message'] = "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $response['message'] = "Missing a temporary folder for uploads.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $response['message'] = "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $response['message'] = "A PHP extension stopped the file upload.";
                break;
        }
    }
} else {
    $response['message'] = "No file uploaded or invalid request method.";
}

$conn->close();
echo json_encode($response);
?>
