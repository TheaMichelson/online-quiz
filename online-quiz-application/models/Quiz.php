<?php
class Quiz {
    private $conn;
    private $table_name = "quizzes";
    private $questions_table = "questions";
    private $choices_table = "choices";

    public $id;
    public $title;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllQuizzes() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuiz($quiz_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $quiz_id);
        if (!$stmt->execute()) {
            // Log error or print for debugging
            echo "Error executing query: " . implode(", ", $stmt->errorInfo());
            return null;
        }
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add the getQuestions method here
    public function getQuestions($quiz_id) {
        $query = "
            SELECT q.id as question_id, q.text as question_text, c.id as choice_id, c.text as choice_text 
            FROM " . $this->questions_table . " q
            LEFT JOIN " . $this->choices_table . " c ON q.id = c.question_id
            WHERE q.quiz_id = :quiz_id";
            
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->execute();
        
        $questions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $question_id = $row['question_id'];
            $choice = [
                'id' => $row['choice_id'],
                'text' => $row['choice_text']
            ];

            // Group choices by question
            if (!isset($questions[$question_id])) {
                $questions[$question_id] = [
                    'id' => $question_id,
                    'text' => $row['question_text'],
                    'choices' => []
                ];
            }

            $questions[$question_id]['choices'][] = $choice;
        }

        return array_values($questions);  // Re-index array numerically
    }

    public function getCorrectAnswers($quizId) {
        $query = "
            SELECT q.id AS question_id, c.id AS correct_choice_id 
            FROM questions q
            JOIN choices c ON q.id = c.question_id
            WHERE q.quiz_id = :quiz_id AND c.is_correct = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $quizId);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Ensure this returns an associative array
    }

    public function saveQuizResult($userId, $quizId, $score) {
        $query = "INSERT INTO quiz_results (user_id, quiz_id, score) VALUES (:user_id, :quiz_id, :score)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':quiz_id', $quizId);
        $stmt->bindParam(':score', $score);
        return $stmt->execute();
    }
    
    
}
?>
