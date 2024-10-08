<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Only start session if it hasn't been started yet
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Quiz Application</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <?php

            // Check if user is logged in
            if (isset($_SESSION['username'])) {
                // User is logged in, display their name, a logout link, and performance history
                echo '<h1 class="nav-item"><a class="nav-link">Welcome, ' . htmlspecialchars($_SESSION['username']) . '!</a></h1>';
                echo '<p class="nav-item"><a href="/online-quiz-application/public/logout.php" class="nav-link">Logout</a></p>';
                echo '<p class="nav-item"><a href="/online-quiz-application/public/index.php?action=performance" class="nav-link">Performance History</a></p>';
            } else {
                // User is not logged in, display login/register links
                echo '<p class="nav-item"><a href="/online-quiz-application/views/user/login.php" class="nav-link">Login</a></p>';
                echo '<p class="nav-item"><a href="/online-quiz-application/views/user/register.php" class="nav-link">Register</a></p>';
            }
            ?>
        </ul>
    </div>
</nav>
<div class="container">
