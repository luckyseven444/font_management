<?php
// Enable CORS globally
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Preflight request response
    header("HTTP/1.1 200 OK");
    exit;
}

// Get the requested URI
$requestUri = $_SERVER['REQUEST_URI'];

// Serve .ttf files with CORS headers
if (preg_match('/\.ttf$/', $requestUri)) {
    $filePath = __DIR__ . $requestUri; // Determine the full path to the file
    if (file_exists($filePath)) {
        header("Content-Type: font/ttf");
        header("Access-Control-Allow-Origin: *");
        readfile($filePath); // Serve the .ttf file
        exit;
    } else {
        // File not found
        header("HTTP/1.0 404 Not Found");
        echo json_encode(['status' => 'error', 'message' => 'File not found.']);
        exit;
    }
}

// Autoload the classes (PSR-4 or simple autoloader)
spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
});

// Create a database instance
$db = new Database();

// Simple router for POST and GET requests
$method = $_SERVER['REQUEST_METHOD'];
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Trim leading and trailing slashes
$pathSegments = explode('/', $path); // Split path into segments

if ($method === 'POST') {
    if ($requestUri === '/server/upload') {
        // Handle file upload
        $file = $_FILES['file'] ?? null;
        $fileName = $_POST['fileName'] ?? null;
        $fontName = $_POST['fontName'] ?? null;  // Get the font name from the POST request

        if ($file && $fontName) {
            $fileUpload = new FileUpload();
            // Pass the file and fontName to the upload method
            $response = $fileUpload->upload($file, $fileName);

            // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
        } elseif (!$file) {
            // No file was uploaded
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded.']);
        } elseif (!$fontName) {
            // Font name was not provided
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(['status' => 'error', 'message' => 'Font name is missing.']);
        }
    } elseif ($pathSegments[0] === 'group' && $pathSegments[1] === 'create') {
        // Handle group creation
        $group = new Group($db);
        $group->create();
    } elseif ($pathSegments[0] === 'group' && $pathSegments[1] === 'update' && isset($pathSegments[2])) {
        // Handle group update
        $id = (int)$pathSegments[2];
        $group = new Group($db);
        $group->update($id);
    } else {
        // Route not found
        header("HTTP/1.0 404 Not Found");
        echo json_encode(['status' => 'error', 'message' => 'Route not found.']);
    }
} elseif ($method === 'GET' && $pathSegments[0] === 'group' && $pathSegments[1] === 'get' && isset($pathSegments[2])) {
    // Handle fetching group by ID
    $id = (int)$pathSegments[2];
    $group = new Group($db);
    $group->get($id);
} else {
    // Only handle POST and GET requests
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
}

// Close the database connection at the end of the script
$db->close();
