<?php
class User {
    private $conn;
    private $table_name = "users";

    private $db;
    public $id;
    public $username;
    public $password;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {

        // Step 1: Check if the email already exists
        $emailCheckQuery = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE email = :email";
        $emailCheckStmt = $this->conn->prepare($emailCheckQuery);
        $emailCheckStmt->bindParam(':email', $this->email);
        $emailCheckStmt->execute();
        $emailCount = $emailCheckStmt->fetchColumn();
    
        // Step 2: Check if the username already exists
        $usernameCheckQuery = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE username = :username";
        $usernameCheckStmt = $this->conn->prepare($usernameCheckQuery);
        $usernameCheckStmt->bindParam(':username', $this->username);
        $usernameCheckStmt->execute();
        $usernameCount = $usernameCheckStmt->fetchColumn();
    
        // Step 3: Handle duplicate email or username
        if ($emailCount > 0) {
            echo "Error: Email already exists!  ";
            return false;
        }
    
        if ($usernameCount > 0) {
            echo "Error: Username already exists!  ";
            return false;
        }
    
        // Step 4: Proceed with registration if both email and username are unique
        $query = "INSERT INTO " . $this->table_name . " (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($query);
    
        // Hash the password
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
    
        // Bind parameters
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashedPassword);
    
        return $stmt->execute(); // Execute the query and return the result
    }

    public function login($username, $password) {
        $query = "SELECT id, password FROM " . $this->table_name . " WHERE username = :username LIMIT 1"; // Ensure you reference the table name
        $stmt = $this->conn->prepare($query); // Use $this->conn instead of $this->db
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            return true; // Login successful
        }

        return false; // Login failed
    }

    public function getUserById($userId) {
        $query = "SELECT id, username, email FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Return the user as an associative array
    }

    public function getUserIdByUsername($username) {
        $query = "SELECT id FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Method to update user information
    public function updateUser($userId, $username, $email) {
        $query = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute(); // Execute the update query
    }

    public function getPerformanceHistory($userId) {
        $query = "SELECT quiz_results.quiz_id, quizzes.title, quiz_results.score, quiz_results.date_taken
                  FROM quiz_results
                  INNER JOIN quizzes ON quiz_results.quiz_id = quizzes.id
                  WHERE quiz_results.user_id = :user_id
                  ORDER BY quiz_results.date_taken DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
