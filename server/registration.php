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
        // Validation failed, redirect to registration page with error message
        header('Location: ../register.php?error=invalid_input');
        exit;
    }

    // Check if the username already exists
    $stmt = $mysqli->prepare("SELECT COUNT(*) AS count FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result['count'] > 0) {
        // Username already exists, redirect to registration page with error message
        header('Location: ../register.php?error=username_exists');
        exit;
    }

    // Hash the password using password_hash() (compatible with ircmaxell/password-compat)
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $stmt = $mysqli->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$username, $passwordHash]);

    // Registration successful, redirect to login page with success message
    header('Location: ../index.php?registration=success');
    exit;
}
