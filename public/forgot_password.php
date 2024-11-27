<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- Add your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <form action="../src/forgot_password_action.php" method="POST">
            <label for="email">Enter your email:</label>
            <input type="email" id="email" name="email" required>
            
            <button type="submit">Request Reset</button>
        </form>
    </div>
</body>
</html>
