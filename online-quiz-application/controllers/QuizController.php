<?php
require_once '../models/Quiz.php';
require_once '../config/database.php';

class QuizController {
    private $db;
    private $quiz;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->quiz = new Quiz($this->db);
    }

    public function index() {
        $quizzes = $this->quiz->getAllQuizzes();
        include '../views/quiz/index.php';
    }

    public function start($quizId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Only start session if it hasn't been started yet
        }
        // Fetch the quiz based on the ID
        $quiz = $this->quiz->getQuiz($quizId); // Ensure this method exists and works
        $questions = $this->quiz->getQuestions($quizId); // Ensure this method exists and works
    
        // Check if quiz and questions are fetched successfully
        if (!$quiz || !$questions) {
            // Handle the error, maybe redirect or show a message
            echo "Quiz or questions not found.";
            return;
        }
    
        // Pass the quiz and questions to the view
        include '../views/quiz/start.php'; // Adjust the path if necessary
    }
    
    
}
?>
