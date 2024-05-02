<link rel="stylesheet" href="../css/bootstrap.min.css">
<style>
    body {
        padding: 10px;
    }
</style>
<?php

session_start();

// Includes
include "../includes/ui.php";
include "../includes/role.php";

// Make connection
require_once "../includes/connection.php";

// Check user is logged in
if (!(isset($_SESSION["user_id"]))) {
    header("Location: /");
    exit;
}

// Check user is admin
if (giveRole($_SESSION["user_id"]) !== "admin") {
    header("Location: /");
    exit;
}

// Check id is exist
if (!(isset($_GET["id"]))) {
    echo error("There is no blog");
    exit;
}

// Sanitize inputs (Reference from w3schools)
function test_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Inputs
$id = test_input($_GET["id"]);

// Give blog info
try {
    // Query
    $sql = "SELECT status FROM blogs WHERE id = ?";

    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare
    $stmt = $conn->prepare($sql);

    // Bind param and execute
    $stmt->execute([$id]);

    // Fetch result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> to report bug)");
}

// Check blog is exist
if ($result === false) {
    echo error("There is no blog");
    exit;
}

// Check blog is unpublished
if ($result["status"] !== "unpublished") {
    echo error("Your blog is deleted or published");
    exit;
}

// Publish blog

// Now date
$date = date("Y-m-d H:i:s");

try {
    // Query
    $sql = "UPDATE blogs SET status = 'published', published_date = ? WHERE id = ?";

    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare
    $stmt = $conn->prepare($sql);

    // Bind param and execute
    $stmt->execute([$date, $id]);

    echo success("Your blog published successfully");
    echo button("success", "/dashboard/?section=blog&op=unpublished_blog", "Ok");
} catch (PDOException $e) {
    echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> to report bug)");
}