<?php
// Include database connection and start session
require_once '../config/db.php';
session_start();
session_regenerate_id(true); // Regenerate session ID to prevent fixation attacks

// Auto-logout mechanism
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // Last activity was over 30 minutes ago, destroy the session
    session_unset();
    session_destroy();
    header("Location: ../public/login.php"); // Redirect to login page
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data
    $username = trim(htmlspecialchars($_POST['username']));
    $password = trim($_POST['password']);

    // Check if inputs are not empty
    if (empty($username) || empty($password)) {
        echo "Please fill in both username/email and password.";
    } else {
        // Query the database for the user
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                // User found, verify password
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Password is correct, set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: ../public/dashboard.php");
                    exit();
                } else {
                    echo "Invalid password.";
                }
            } else {
                echo "No user found with this username or email.";
            }

            $stmt->close();
        } else {
            // Handle query preparation error
            echo "Database query failed. Please try again later.";
        }

        $conn->close();
    }
}
?>
