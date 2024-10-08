<?php 
// Start the session to access session variables
include('../views/layouts/header.php'); 
?>

<h2>Available Quizzes</h2>

<?php
// Check if user is logged in
if (isset($_SESSION['username'])) {
    // User is logged in, show the quizzes
    if (!empty($quizzes)): ?>
        <ul>
            <?php foreach ($quizzes as $quiz): ?>
                <li><a href="/online-quiz-application/public/index.php?action=start&quiz_id=<?= $quiz['id'] ?>"><?= htmlspecialchars($quiz['title']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No quizzes available.</p>
    <?php endif;
} else {
    // User is not logged in, show a message
    echo "<p>You need to log in to see available quizzes.</p>";
}
?>

<?php include '../views/layouts/footer.php'; ?>
