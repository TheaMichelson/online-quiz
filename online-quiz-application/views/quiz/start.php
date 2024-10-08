<?php include('../views/layouts/header.php'); ?>

<?php if (isset($quiz)): // Check if the quiz is set ?>
    <h2><?= htmlspecialchars($quiz['title']) ?></h2> <!-- Use htmlspecialchars to prevent XSS -->
    <form action="../views/quiz/submit.php" method="POST"> <!-- Ensure this path is correct -->
        <input type="hidden" name="quiz_id" value="<?= htmlspecialchars($quiz['id']) ?>"> <!-- Hidden input for quiz ID -->
        <?php foreach ($questions as $question): ?>
            <div class="form-group">
                <label><?= htmlspecialchars($question['text']) ?></label> <!-- Prevent XSS -->
                <?php foreach ($question['choices'] as $choice): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer[<?= $question['id'] ?>]" value="<?= $choice['id'] ?>">
                        <label class="form-check-label"><?= htmlspecialchars($choice['text']) ?></label> <!-- Prevent XSS -->
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
<?php else: ?>
    <p>Quiz not found.</p> <!-- Display a friendly message -->
<?php endif; ?>

<?php include '../views/layouts/footer.php'; ?>
