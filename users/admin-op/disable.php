<link rel="stylesheet" href="/css/bootstrap.min.css">
<style>
    body {
        padding: 10px;
    }
</style>
<?php

session_start();

// Includes
include "../../includes/ui.php";
include "../../includes/role.php";

// Make connection
require_once "../../includes/connection.php";

// Sanitize inputs (Reference from w3schools)
function test_input($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Check user is logged in
if (!(isset($_SESSION["user_id"]))) {
    header("Location: ../");
    exit;
}

// Check user is admin
if (giveRole($_SESSION["user_id"]) !== "admin") {
    header("Location: ../");
    exit;
}

// Check id is exist
if (isset($_POST["id"])) {
    // Inputs
    $id = test_input($_POST["id"]);
} else {
    echo error("You not set user id");
    exit;
}

// Check user is exist
try {
    // Query
    $sql = "SELECT status FROM users WHERE id = ?";

    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare
    $stmt = $conn->prepare($sql);

    // Bind param and execute
    $stmt->execute([$id]);

    // Fetch result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/'>me</a>) to report bug)");
}

if ($result === false) {
    echo error("User isn't exist");
    exit;
}

// Check target user isn't admin
if (giveRole($id) === "admin") {
    echo error("You can't disable admin account");
    exit;
}

// Check user isn't disable
if ($result["status"] === "disable") {
    echo error("User is disabled");
    exit;
}

// Start disable operation
try {
    // Query
    $sql = "UPDATE users SET status = 'disable' WHERE id = ?";

    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare
    $stmt = $conn->prepare($sql);

    // Bind param and execute
    $stmt->execute([$id]);

    echo success("User disabled successfully");
    echo button("success", "/dashboard/?section=user&op=users", "Ok");
} catch (PDOException $e) {
    echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/'>me</a>) to report bug)");
}