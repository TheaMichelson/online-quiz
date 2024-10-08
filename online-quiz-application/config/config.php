<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Only start session if it hasn't been started yet
}

// Set default timezone
date_default_timezone_set('America/Chicago');
?>
