<?php

require_once 'Database.php';

class Group {
    public $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    // Method to create a new group
    public function create() {
        // Get the raw POST data
        $input = file_get_contents('php://input');
        
        // Decode the JSON data
        $data = json_decode($input, true);

        // Ensure data is sanitized and present
        $name = $data['name'] ?? '';
        $fonts = $data['fonts'] ?? '';  // Expecting comma-separated fonts
        $count = $data['count'] ?? 0;  // Ensure count is an integer

        // Prepare an SQL statement using PDO
        $sql = "INSERT INTO `groups` (name, fonts, count) VALUES (:name, :fonts, :count)";
        $stmt = $this->db->pdo->prepare($sql);

        // Bind parameters to the SQL query
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':fonts', $fonts);
        $stmt->bindParam(':count', $count, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            // Return success message
            //echo json_encode(['status' => 'success', 'message' => 'Group created successfully.']);
             return ['status' => 'ok', 'message' => 'Font group created successfully'];
        } else {
            // Return error message
            //echo json_encode(['status' => 'error', 'message' => 'Failed to create group.']);
            return ['status' => 'error', 'message' => 'Font group create error '];
        }
    }

    // Method to get a group by ID
    public function get($id) {
        $id = (int)$id;
        $query = "SELECT * FROM groups WHERE id = $id";
        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            $group = $result->fetch_assoc();
            echo json_encode($group);
        } else {
            http_response_code(404);  // Not found
            echo json_encode(['error' => 'Group not found']);
        }
    }

    // Method to update a group by ID
    public function update($id) {
        // Retrieve input from POST request
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['name']) || !isset($data['fonts']) || !isset($data['count'])) {
            http_response_code(400);  // Bad request
            echo json_encode(['error' => 'Invalid input']);
            return;
        }

        $id = (int)$id;
        $name = $this->db->real_escape_string($data['name']);
        $fonts = $this->db->real_escape_string(implode(',', $data['fonts']));
        $count = (int) $data['count'];

        // Update the group in the database
        $query = "UPDATE groups SET name='$name', fonts='$fonts', count=$count WHERE id=$id";

        if ($this->db->query($query)) {
            echo json_encode(['message' => 'Group updated successfully']);
        } else {
            http_response_code(500);  // Internal server error
            echo json_encode(['error' => 'Failed to update group']);
        }
    }
}
