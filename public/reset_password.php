<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- Add your CSS file -->
</head>
<body>



    <div class="container">
        <h1>Reset Password</h1>
        <form action="../src/reset_password_action.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
