<?php
include 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: Home.php');
    exit;
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'register') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error_message = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error_message = 'Username is already taken.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashed_password])) {
                $user_id = $pdo->lastInsertId();
                session_regenerate_id();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;

                header('Location: Home.php');
                exit;
            } else {
                $error_message = 'An error occurred during registration. Please try again.';
            }
        }
    }
}
