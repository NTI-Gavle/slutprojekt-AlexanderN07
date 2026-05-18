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
    && $_POST['action'] == 'register'
) {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (
        empty($username)
        || empty($email)
        || empty($password)
        || empty($confirm_password)
    ) {

        $error_message = 'Please fill in all fields.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error_message = 'Please enter a valid email address.';

    } elseif ($password !== $confirm_password) {

        $error_message = 'Passwords do not match.';

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM users
            WHERE username = ?
               OR email = ?
            LIMIT 1
        ");

        $stmt->execute([
            $username,
            $email
        ]);

        if ($stmt->fetch()) {

            $error_message = 'Username or email is already taken.';

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare("
                INSERT INTO users (
                    username,
                    email,
                    password_hash
                )
                VALUES (?, ?, ?)
            ");

            $success = $stmt->execute([
                $username,
                $email,
                $hashed_password
            ]);

            if ($success) {

                $user_id = (int)$pdo->lastInsertId();

                session_regenerate_id(true);

                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;

                header('Location: home.php');
                exit;

            } else {

                $error_message = 'An error occurred during registration. Please try again.';
            }
        }
    }
}
?>