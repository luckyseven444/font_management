<?php

require_once 'Database.php';

class Group {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    // Method to create a new group
    public function create() {
        // Retrieve input from POST request
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['name']) || !isset($data['fonts']) || !isset($data['count'])) {
            http_response_code(400);  // Bad request
            echo json_encode(['error' => 'Invalid input']);
            return;
        }

        $name = $this->db->real_escape_string($data['name']);
        $fonts = $this->db->real_escape_string(implode(',', $data['fonts']));
        $count = (int) $data['count'];

        // Insert into the groups table
        $query = "INSERT INTO groups (name, fonts, count) VALUES ('$name', '$fonts', $count)";

        if ($this->db->query($query)) {
            http_response_code(201);  // Created
            echo json_encode(['message' => 'Group created successfully']);
        } else {
            http_response_code(500);  // Internal server error
            echo json_encode(['error' => 'Failed to create group']);
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
