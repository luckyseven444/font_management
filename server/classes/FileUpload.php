<?php
class FileUpload {
    private $uploadDir;

    public function __construct() {
        $this->uploadDir = 'uploads/';
    }

    public function upload($file, $fileName) {
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (strtolower($fileExtension) !== 'ttf') {
            return ['status' => 'error', 'message' => 'Invalid file type. Only .ttf files allowed.'];
        }

        $uploadFile = $this->uploadDir . basename($fileName);

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            // Return a success response
            return ['status' => 'ok', 'message' => 'File uploaded successfully.', 'fileName' => $fileName];
        } else {
            return ['status' => 'error', 'message' => 'Failed to upload file.'];
        }
    }
}
