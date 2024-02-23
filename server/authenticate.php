<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../utils/config.php';

use Respect\Validation\Validator as v;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validation rules
    $usernameValidator = v::alnum()->noWhitespace()->length(1, 20);
    $passwordValidator = v::length(6, null);

    if (!$usernameValidator->validate($username) || !$passwordValidator->validate($password)) {
        // Validation failed, redirect to login page with error message
        header('Location: ../index.php?error=invalid_input');
        exit;
    }

    // Check if the username exists in the database
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        // Verify the provided password against the stored password hash
        if (password_verify($password, $user['password_hash'])) {
            // Authentication successful, start session
            session_start();
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            header('Location: ../home.php');
            exit;
        }
    }

    // Authentication failed, redirect to login page with error message
    header('Location: ../index.php?error=invalid_credentials');
    exit;
}
