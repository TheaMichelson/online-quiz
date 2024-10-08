<?php
// Include the necessary files
require_once '../../models/User.php';
require_once '../../config/database.php'; // Ensure this path is correct
require_once '../../models/Quiz.php'; // Ensure this path is correct
include('../../views/layouts/header.php');
// Start the session to access session variables (if needed)
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Only start session if it hasn't been started yet
}

// Create a new database connection
$database = new Database();
$db = $database->getConnection();
$quiz = new Quiz($db);
$userModel = new User($db);

// Get the username from the session
$username = $_SESSION['username'] ?? null;

// Fetch the user ID using the username
$userId = $username ? $userModel->getUserIdByUsername($username) : null;
// Retrieve quiz ID and submitted answers from the POST request
$quizId = $_POST['quiz_id'] ?? null;
$answers = $_POST['answer'] ?? [];

// Check if quiz ID is provided
if (!$quizId) {
    echo "No quiz submitted.";
    exit;
}

// Check if answers were submitted
if (empty($answers)) {
    echo "No answers submitted.";
    exit;
}

// Fetch the correct answers for the quiz from the database
$correctAnswers = $quiz->getCorrectAnswers($quizId); // Assuming this method exists in the Quiz model

// Initialize score counter
$score = 0;
$totalQuestions = count($correctAnswers);

// Process the submitted answers and compare them with the correct ones
foreach ($correctAnswers as $correctAnswer) {
    if (isset($correctAnswer['question_id']) && isset($correctAnswer['correct_choice_id'])) {
        $questionId = $correctAnswer['question_id'];
        $correctChoiceId = $correctAnswer['correct_choice_id'];

        // Check if the user answered this question and if their answer is correct
        if (isset($answers[$questionId])) {
            $userAnswerId = $answers[$questionId];
            if ($userAnswerId == $correctChoiceId) {
                $score++; // Increment score for correct answer
            }
        }
    } else {
        echo "Error: question_id or correct_choice_id not found for one of the questions.<br>";
    }
}

// Calculate the percentage score
$percentageScore = ($score / $totalQuestions) * 100;

// If the user is logged in, store the quiz result in the database
if ($userId) {
    $query = "INSERT INTO quiz_results (user_id, quiz_id, score) VALUES (:user_id, :quiz_id, :score)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':quiz_id', $quizId);
    $stmt->bindParam(':score', $percentageScore);
    
    if ($stmt->execute()) {
        echo "<p>Your quiz result has been saved.</p>";
    } else {
        echo "<p>Failed to save quiz result.</p>";
    }
} else {
    echo "<p>You need to log in to save your quiz results.</p>";
}

// Display the score to the user
echo "<h2>Quiz Results</h2>";
echo "<p>You answered $score out of $totalQuestions questions correctly.</p>";
echo "<p>Your score: " . round($percentageScore, 2) . "%</p>";

// Optionally include the footer if you have one
include '../layouts/footer.php';
?>
