<link rel="stylesheet" href="../css/bootstrap.min.css">
<style>
    body {
        padding: 10px;
    }
</style>
<?php

// Database info
$rdbms = "mysql"; // Change this if your database platform isn't mysql
$host = "localhost"; // Change this if your database isn't on your local computer (put your host ip)
$database = "aceBlog"; // Change this if you edit database name
$username = "root"; // Put your database username
$password = ""; // Put your database password

// Start connection operation
try {
    // Make connection
    $conn = new PDO("{$rdbms}:host={$host};dbname={$database}", $username, $password);

    // Set error mode for catch block
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional
    echo "<div class='alert alert-success' role='alert'>Connected successfully</div>";
} catch (PDOException $e) {
    echo "<div class='alert alert-danger' role='alert'>Connection failed: {$e->getMessage()} (Please check database config section in README.md file)</div>";
}