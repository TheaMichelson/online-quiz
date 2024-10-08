<?php
require_once '../models/User.php';
require_once '../config/database.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];

            if ($this->user->register()) {
                header("Location: ../views/user/login.php");
            } else {
                echo "Error: Unable to register.";
            }
        }
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Only start session if it hasn't been started yet
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            // Validate input (basic example)
            if (empty($username) || empty($password)) {
                echo "Username and password cannot be empty.";
                return;
            }
    
            // Attempt to log the user in
            if ($this->user->login($username, $password)) {
    
                // Assuming your login method returns the user ID or info
                $_SESSION['user_id'] = $this->user->id; // Set the user ID in session
                $_SESSION['username'] = $username; // Set the username in session
                
                // Redirect to the quiz index or profile page after successful login
                header("Location: /online-quiz-application/public/index.php");
                exit;
            } else {
                echo "Invalid username or password.";
            }
        } else {
            // Display the login form or handle GET request
            include '../views/user/login.php'; // Path to your login form view
        }
    }
    

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /login.php");
    }

    public function getUserProfile($userId) {
        return $this->user->getUserById($userId); // Retrieve user info from the model
    }

    public function updateProfile() {
        // Validate and update user profile information
        $userId = $_SESSION['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];

        // Perform validation here (e.g., check for valid email format)

        if ($this->user->updateUser($userId, $username, $email)) {
            // Redirect to the profile page or display a success message
            header("Location: /online-quiz-application/public/profile.php");
            exit;
        } else {
            // Handle the error (e.g., display an error message)
            echo "Failed to update profile.";
        }
    }

    public function performance($userId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($userId) {
            $performanceHistory = $this->user->getPerformanceHistory($userId);
            include '../views/user/performance.php'; // Path to the view file
        } else {
            echo "You need to log in to view your performance history.";
        }
    }
    
}
?>
