<link rel="stylesheet" href="../css/bootstrap.min.css">
<style>
    body {
        padding: 10px;
    }
</style>
<?php

// Includes
require_once "ui.php";

// Database info
$rdbms = "mysql"; // Change this if your database platform isn't mysql
$host = "localhost"; // Change this if your database isn't on your local computer (put your host ip)
$database = "aceBlog"; // Change this if you edit database name
$serverUsername = "root"; // Put your database username
$serverPassword = ""; // Put your database password

// Start connection operation
try {
    // Make connection
    $conn = new PDO("{$rdbms}:host={$host};dbname={$database}", $serverUsername, $serverPassword);

    // Set error mode for catch block
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional
    // echo success("Connected successfully");
} catch (PDOException $e) {
    echo error("Connection failed: {$e->getMessage()} (Please check database config section in README.md file)");
}