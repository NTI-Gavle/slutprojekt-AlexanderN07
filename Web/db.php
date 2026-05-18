<?php
session_start();

try {

    $pdo = new PDO(
        "mysql:host=localhost;dbname=finalproj;charset=utf8mb4",
        "root",
        getenv('DB_PASS') ?: ''
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Database connection failed: " . $e->getMessage());

}