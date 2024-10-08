
<h2>Register</h2>
<form action="/online-quiz-application/public/index.php?action=register" method="POST">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form>
