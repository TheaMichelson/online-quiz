<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Only start session if it hasn't been started yet
}
session_destroy(); // Destroy all session data
header("Location: /online-quiz-application/public/index.php"); // Redirect to home
exit;
?>
