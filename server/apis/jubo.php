<?php
// Debug information
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log request information
error_log("Request received: " . date('Y-m-d H:i:s'));
error_log("POST data: " . print_r($_POST, true));
error_log("FILES data: " . print_r($_FILES, true));

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

try {
    error_log("Starting execution...");
    
    include_once('../common/include.php');
    error_log("Include files loaded successfully");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log("Processing POST request");
        
        // Check for password first
        if (!isset($_POST['password'])) {
            sendResponse(400, null, 'Password is required');
            exit;
        }

        // Load environment variables
        $env_path = __DIR__ . '/../../.env';
        error_log("Looking for .env file at: " . $env_path);
        error_log("Current directory: " . __DIR__);
        
        if (!file_exists($env_path)) {
            error_log("Error: .env file not found at: " . $env_path);
            sendResponse(500, null, 'Configuration file not found');
            exit;
        }

        try {
            error_log("Reading .env file");
            $env_lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($env_lines === false) {
                error_log("Error: Could not read .env file");
                sendResponse(500, null, 'Could not read configuration file');
                exit;
            }

            $_ENV['JUBO_UPLOAD_PASSWORD'] = null;
            foreach ($env_lines as $line) {
                error_log("Processing env line: " . substr($line, 0, strpos($line, '='))); // Only log the key part
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $_ENV[trim($key)] = trim($value);
                }
            }

            error_log("ENV password set: " . (!empty($_ENV['JUBO_UPLOAD_PASSWORD']) ? 'yes' : 'no'));
        } catch (Exception $e) {
            error_log("Error reading .env file: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            sendResponse(500, null, 'Error reading configuration');
            exit;
        }

        $correct_password = $_ENV['JUBO_UPLOAD_PASSWORD'] ?? null;
        
        if (!$correct_password) {
            error_log("Error: JUBO_UPLOAD_PASSWORD not set in .env file");
            sendResponse(500, null, 'Server configuration error - password not set');
            exit;
        }
        
        if ($_POST['password'] !== $correct_password) {
            sendResponse(401, null, 'Incorrect password');
            exit;
        }

        if (isset($_FILES['juboFile'])) {
            // Check if there was an upload error
            if ($_FILES['juboFile']['error'] !== UPLOAD_ERR_OK) {
                error_log("Upload error: " . $_FILES['juboFile']['error']);
                sendResponse(500, null, 'File upload error: ' . $_FILES['juboFile']['error']);
                exit;
            }

            $uploadDir = '../../jubofile/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    error_log("Failed to create directory: " . $uploadDir);
                    sendResponse(500, null, 'Failed to create upload directory');
                    exit;
                }
            }
            
            // Check if directory is writable
            if (!is_writable($uploadDir)) {
                error_log("Directory not writable: " . $uploadDir);
                sendResponse(500, null, 'Upload directory is not writable');
                exit;
            }
            
            $targetFile = $uploadDir . 'jubo.pdf';
            
            // Check if file is a PDF
            $fileType = strtolower(pathinfo($_FILES['juboFile']['name'], PATHINFO_EXTENSION));
            if ($fileType !== 'pdf') {
                sendResponse(400, null, 'Only PDF files are allowed');
                exit;
            }
            
            // Log file details
            error_log("Attempting to move file:");
            error_log("Temp file: " . $_FILES['juboFile']['tmp_name']);
            error_log("Target location: " . $targetFile);
            
            if (move_uploaded_file($_FILES['juboFile']['tmp_name'], $targetFile)) {
                // Verify file was actually created
                if (file_exists($targetFile)) {
                    sendResponse(200, null, 'File uploaded successfully');
                } else {
                    error_log("File not found after upload: " . $targetFile);
                    sendResponse(500, null, 'File upload failed - file not found after upload');
                }
            } else {
                error_log("Failed to move uploaded file to: " . $targetFile);
                error_log("PHP error: " . error_get_last()['message']);
                sendResponse(500, null, 'Error uploading file');
            }
        } else {
            sendResponse(400, null, 'No file uploaded');
        }
    } else {
        sendResponse(405, null, 'Method not allowed');
    }
} catch (Exception $e) {
    error_log("Unexpected error: " . $e->getMessage());
    error_log("Error occurred in file: " . $e->getFile() . " on line " . $e->getLine());
    error_log("Stack trace: " . $e->getTraceAsString());
    sendResponse(500, null, 'An unexpected error occurred: ' . $e->getMessage());
}
?>
