<?php
require_once '../config/db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if the email exists in the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save the token and expiry in the database
        $update_query = "UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sss", $token, $expiry, $email);
        $update_stmt->execute();

        // Send the reset email (dummy URL for now)
        $reset_link = "http://localhost/finance_app/public/reset_password.php?token=$token";
        echo "Password reset link: <a href='$reset_link'>$reset_link</a>"; // Replace with email sending in production
    } else {
        echo "No user found with this email.";
    }

    $stmt->close();
    $conn->close();
}
?>
