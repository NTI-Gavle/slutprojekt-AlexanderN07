<?php
require_once __DIR__ . '/../config/env.php';

// Load the .env file
$env = loadEnv(__DIR__ . '/../.env');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=quiz;charset=utf8mb4",
        "root",
        getenv('DB_PASS')
    );
    echo 'Connected to database'; // Remove after it works
    $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo 'Connection failed: ' . $e->getMessage();
}
