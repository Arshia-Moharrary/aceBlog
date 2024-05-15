<style>
    body {
        padding: 10px;
    }
</style>
<?php

// Includes
include "dbinfo.php";

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