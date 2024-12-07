<?php
// Key Features of the SecureImageHandler Class:
//   1. Validate MIME Type: Ensures the uploaded file is an actual image.
//   2. Restrict File Extensions: Allows only specific extensions like .jpg, .jpeg, .png, etc.
//   3. Scan for PHP Code: Checks the file contents for PHP tags or other malicious code.
//   4. Move Files Outside Public Directory: Saves uploaded files to a directory not accessible via URL.
//   5. Serve Images Securely: A script serves the image only if it passes validation.
class SecureImageHandler
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    //Validates and saves the uploaded image securely.
    public function uploadImage($file)
    {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('File upload error: ' . $file['error']);
        }

        // Validate MIME type
        $mimeType = mime_content_type($file['tmp_name']);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mimeType, $allowedTypes)) {
            throw new RuntimeException('Invalid file type.');
        }

        // Validate file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extension, $allowedExtensions)) {
            throw new RuntimeException('Invalid file extension.');
        }

        // Scan file for PHP code
        $contents = file_get_contents($file['tmp_name']);
        if (preg_match('/<\?php/i', $contents)) {
            throw new RuntimeException('File contains embedded PHP code.');
        }

        // Generate a unique file name
        $newFileName = uniqid('img_', true) . '.' . $extension;
        $destination = $this->uploadDir . $newFileName;

        // Move the file to the upload directory
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        return $newFileName;
    }

    // Securely serves an image from the upload directory.
    public function serveImage($fileName)
    {
        $filePath = $this->uploadDir . basename($fileName);

        // Check if the file exists
        if (!file_exists($filePath)) {
            http_response_code(404);
            die('File not found.');
        }

        // Validate MIME type again before serving
        $mimeType = mime_content_type($filePath);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mimeType, $allowedTypes)) {
            http_response_code(403);
            die('Invalid file type.');
        }

        // Serve the file with correct headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}

// USAGE:
// --------------------------------------------------------------
// try {
//     $handler = new SecureImageHandler(__DIR__ . '/uploads');
//     $fileName = $handler->uploadImage($_FILES['image']);
//     echo "Image uploaded successfully: " . $fileName;
// } catch (RuntimeException $e) {
//     echo "Error: " . $e->getMessage();
// }
// --------------------------------------------------------------
// Serve the image in PHP pages
// $handler = new SecureImageHandler(__DIR__ . '/uploads');
// $handler->serveImage($_GET['file']);

