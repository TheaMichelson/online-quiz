<?php
require '../config/config.php';
require '../controllers/QuizController.php';
require '../controllers/UserController.php';

// Initialize action variable
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Check if an action is set and route the request accordingly
if ($action) {
    $userController = new UserController();
    $quizController = new QuizController();
    $database = new Database();
    $db = $database->getConnection();
    $userModel = new User($db);

    // Get the username from the session
    $username = $_SESSION['username'] ?? null;

    // Fetch the user ID using the username
    $userId = $username ? $userModel->getUserIdByUsername($username) : null;

    switch ($action) {
        case 'register':
            $userController->register();
            break;
        case 'login':
            $userController->login();
            break;
        case 'logout':
            $userController->logout();
            break;
        case 'performance':
            $userController->performance($userId);
            break;
        case 'start':
            if (isset($_GET['quiz_id'])) {
                $quizId = $_GET['quiz_id'];
                $quizController->start($quizId);
            } else {
                echo "Quiz ID is missing.";
            }
            break;
        default:
            $quizController = new QuizController();
            $quizController->index();
            break;
    }
} else {
    // Default action - show quizzes
    $quizController = new QuizController();
    $quizController->index();
}
?>