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

// Check user logged in
if (!(isset($_SESSION["user_id"]))) {
    echo error("You haven't access this file");
    echo button("danger", "/", "Back to home");
    exit;
}

// Check user is admin
$role = giveRole($_SESSION["user_id"]);
if ($role !== "admin") {
    echo error("You haven't access this file");
    echo button("danger", "/", "Back to home");
    exit;
}

// Sanitize inputs (Reference from w3schools)
function test_input($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Inputs
$title = test_input($_POST["title"]);
$content = test_input($_POST["content"]);
$tags = test_input($_POST["tags"]);

// Validation

// Error container
$message = [];

// Empty validate
if (empty($title) || empty($content) || empty($tags)) {
    echo error("Please fill all inputs");
    exit;
}

// Content validation

// Title
if (!(preg_match("/^[a-zA-Z0-9\s]+$/", $title))) {
    $message["titleContent"] = "Your title is invalid (only letter, number and space)";
}

// Tags
if (!(preg_match("/^[a-zA-Z0-9]+(?:,[a-zA-Z0-9]+){0,5}$/", $tags))) {
    $message["tagsContent"] = "Your tags is invalid (Just letter, number. Seperate with comman without any space and the number of tags must be less than 5)";
}

// Length validation

// Title
if (strlen($title) > 100) {
    $message["titleLength"] = "Your title is too large (less than 100 characters)";
}

// Tags
if (strlen($tags) > 128) {
    $message["tagsLength"] = "Your tags is too large (less than 128 characters)";
}

// Image validation

// File info
$directory = "../uploads/"; /* The directory where the file will upload (You can change to your directory) */
$path = $directory . $_FILES["image"]["name"];
$upload = false;
$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if ($_FILES["image"]["size"] !== 0) {
    $size = getimagesize($_FILES["image"]["tmp_name"]);
    if (!($size)) {;
        $message["imageFake"] = "Your file isn't image";
    }
    
    // Check file size
    if ($_FILES["image"]["size"] > 1000000) {
        $message["imageSize"] = "Your image is larger than 1MB";
    }
    
    // Allow certain file formats
    if ($extension != "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "gif") {
        $message["imageType"] = "Your image type is invalid (jpg, jpeg, png, gif)";
    }
} else {
    $message["imageEmpty"] = "Please set a image for cover";
}

// Set publish mode
if (isset($_POST["publish"])) {
    $publish = "published";
    $publishDate = date("Y-m-d H:i:s");
} else {
    $publish = "unpublished";
    $publishDate = NULL;
}

// Show message
foreach($message as $key => $val) {
    echo error($val);
}

// Upload image
if (count($message) === 0) {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $path)) {
        $upload = true;
    } else {
        $upload = false;
        echo error("We can't upload your image, please try again");
    }

    $name = time() . "." . $extension;
    rename($path, $directory . $name);
}

// Insert to database
if (count($message) === 0) {
    try {
        // Query
        $sql = "INSERT INTO blogs (title, content, author_id, published_date, tags, status, featured_image) VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Set error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare
        $stmt = $conn->prepare($sql);

        // Bind param nad execute
        $stmt->execute([$title, $content, $_SESSION["user_id"], $publishDate, $tags, $publish, $name]);

        echo success("Blog created successfully");
        echo button("success", "/dashboard/?section=blog", "Ok");
    } catch (PDOException $e) {
        echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> to report bug)");
    }
}