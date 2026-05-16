<?php

require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

$error_message = '';

if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['action'])
    && $_POST['action'] == 'login'
) {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {

        $error_message = 'Please fill in both username and password.';

    } else {

        $stmt = $pdo->prepare("
            SELECT
                id,
                username,
                password_hash
            FROM users
            WHERE username = ?
            LIMIT 1
        ");

        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (
            $user &&
            password_verify($password, $user['password_hash'])
        ) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: home.php');
            exit;

        } else {

            $error_message = 'Invalid username or password.';
        }
    }
}
?>