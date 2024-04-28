<link rel="stylesheet" href="../../css/bootstrap.min.css">
<style>
    body {
        padding: 10px;
    }
</style>
<?php

// Start session
session_start();

// Includes
require_once "../../includes/ui.php";

// Check request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo "<div class='alert alert-danger'>Please signup from this <a href='/users/signup'>link</a></div>";
    exit;
}

// Sanitize inputs (Reference from w3schools)
function test_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}


// Check user is not login
if (isset($_SESSION["user_id"])) {
    echo "<div class='alert alert-danger'>You are logged in website, Please logout of your account to create a new account</div>";
}

// Inputs
$username = test_input($_POST["username"]);
$email = test_input($_POST["email"]);
$password = test_input($_POST["password"]);
$repeatPassword = test_input($_POST["repeatPassword"]); 

// Error container
$message = [];

// For validate proccess (if inputs are empty, program will not validate)
$validate = true;

// Empty validation
if (strlen($username) == 0 || strlen($email) == 0 || strlen($password) == 0 || strlen($repeatPassword) == 0) {
    $message["empty"] = "Please fill all inputs";
    $validate = false;
}


if ($validate) {
    // Content validation

    // Username
    if (!(preg_match("/^[A-Za-z0-9_]+$/", $username))) {
        $message["usernameContent"] = "Your username is invalid (just letter, number and underscore)";
    }

    // Email
    if (!(preg_match("/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/", $email))) {
        $message["emailContent"] = "Your email is invalid";
    }

    // Password (match with repeat password)
    if (!($password === $repeatPassword)) {
        $message["passwordContent"] = "Your password doesn't match with repeat password";
    }

    // Length validation 'big' (you can change length but not more than database length)

    // Username
    if (strlen($username) > 32) {
        $message["usernameLength"] = "Your username is too large (less than 32 characters)";
    }

    // Email
    if (strlen($email) > 90) {
        $message["emailLength"] = "Your email is too large (less than 90 characters)";
    }

    // Password
    if (strlen($password) > 150) {
        $message["passwordLength"] = "Your password is too large (less than 150 characters)";
    }

    // Length validation 'small' (you can change length but not more than database length)

    // Username
    if (strlen($username) < 4) {
        $message["usernameLength"] = "Your username is too small (more than 4 characters)";
    }

    // Email
    if (strlen($email) < 17) {
        $message["emailLength"] = "Your email is too small (more than 17 characters)";
    }

    // Password
    if (strlen($password) < 8) {
        $message["passwordLength"] = "Your password is too small (more than 8 characters)";
    }


}

// Show messages
foreach ($message as $key => $val) {
    echo error($val);
}

if (count($message) === 0) {
    // Make connection
    require_once "../../includes/connection.php";

    // Start signup operation
    try {
        // Query
        $sql = "INSERT INTO users (username, email, pass, status, role) VALUES (?, ?, ?, ?, ?)";

        // Set error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare
        $stmt = $conn->prepare($sql);

        // Bind param and execute
        $stmt->execute([$username, $email, $password, "enable", "user"]);

        echo success("Sign up successfully");
        echo "<a href='/' class='btn btn-success'>Back to home</a>";

        // Set session for user
        $_SESSION["user_id"] = $conn->lastInsertId();
    } catch (PDOException $e) {
        echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> and report bug) <");
    }
}