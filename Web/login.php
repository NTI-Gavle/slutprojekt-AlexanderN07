<?php
include 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: Home.php');
    exit;
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error_message = 'Please fill in both username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id();
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: Home.php');
            exit;
        } else {
            $error_message = 'Invalid username or password.';
        }
    }
}
