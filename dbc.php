<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database config</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <style>
        body {
            padding: 10px;
        }
    </style>
</head>

<body>
    <form class="row g-3" action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
        <div class="col-md-6">
            <label for="config">Tap to start: </label>
            <button type="submit" class="btn btn-primary" name="conf" id="config">Config</button>
        </div>
    </form>
    <br>
</body>

</html>

<?php

include "includes/ui.php";

if (isset($_POST["conf"])) {
    // Make connection
    require_once "includes/connection.php";

    // Error container
    $message = [];

    // Create database
    try {
        // Query
        $sql = "CREATE DATABASE {$database}";

        // Set error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare
        $stmt = $conn->prepare($sql);

        // Execute
        $stmt->execute();
    } catch (PDOException $e) {
        $message["database"] = "Can't create database: {$e->getMessage()}";
    }

    // Create user table
    try {
        // Query
        $sql = "CREATE TABLE users (
            id INT(11) AUTO_INCREMENT,
            username VARCHAR(32),
            email VARCHAR(256),
            pass VARCHAR(256),
            created_date DATETIME DEFAULT CURRENT_TIME,
            updated_date DATETIME DEFAULT NULL,
            status ENUM('enable', 'disable'),
            role ENUM('admin', 'user')
        );";

        // Set error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare
        $stmt = $conn->prepare($sql);

        // Execute
        $stmt->execute();
    } catch (PDOException $e) {
        $message["user"] = "Can't create user table: {$e->getMessage()}";
    }

    // Create blog table
    try {
        // Query
        $sql = "CREATE TABLE blogs (
            id INT(11) AUTO_INCREMENT,
            title VARCHAR(100),
            content TEXT,
            author_id INT(11),
            published_date DATETIME,
            created_date DATETIME DEFAULT CURRENT_TIME,
            updated_date DATETIME,
            tags VARCHAR(128),
            status 	ENUM('published', 'unpublished', 'deleted'),
            featured_image TEXT,
            PRIMARY KEY (id),
            FOREIGN KEY (id) REFERENCES users(id)
        );";

        // Set error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare
        $stmt = $conn->prepare($sql);

        // Execute
        $stmt->execute();
    } catch (PDOException $e) {
        $message["blog"] = "Can't create blog table: {$e->getMessage()}";
    }

    // Success message
    if (count($message) === 0) {
        echo success("Database config successfully");
    } else {
        // Show messages
        foreach ($message as $error) {
            echo error($error);
        }
    }
}
