<?php
require '../../config/config.php'; // Adjust path if necessary
require '../../controllers/UserController.php'; // Adjust path if necessary

$userController = new UserController();
$user = $userController->getUserProfile($_SESSION['user_id']); // Assuming user_id is stored in session

if (!$user) {
    // Handle the case where the user is not found
    echo "User not found.";
    exit;
}
?>

<?php include('../../views/layouts/header.php'); ?>

<h2>User Profile</h2>
<form action="../controllers/UserController.php" method="POST">
    <input type="hidden" name="action" value="updateProfile"> <!-- Action to update the profile -->
    
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>

<?php include('../../views/layouts/footer.php'); ?>
