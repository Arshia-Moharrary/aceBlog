<link rel="stylesheet" href="../../css/bootstrap.min.css">
<?php

// Start session
session_start();

// Includes
require_once "../../includes/ui.php";

// Make connection
require_once "../../includes/connection.php";

// Check request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo error("Please login from this <a href='/users/login'>link</a>");
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
$email = test_input($_POST["email"]);
$password = test_input($_POST["password"]);

// Check user logged in
if (isset($_SESSION["user_id"])) {
    echo error("You are currently logged in");
    echo button("danger", "/", "Back to home");
    exit;
}

// Validation

// Error container
$message = [];

// For validate proccess (if inputs are empty, program will not validate)
$validate = true;

// Empty validation
if (strlen($email) == 0 || strlen($password) == 0) {
    $message["empty"] = "Please fill all inputs";
    $validate = false;
}


if ($validate) {
    // Content validation

    // Email
    if (!(preg_match("/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/", $email))) {
        $message["emailContent"] = "Your email is invalid";
    }

    // Length validation 'big' (you can change length but not more than database length)

    // Email
    if (strlen($email) > 90) {
        $message["emailLength"] = "Your email is too large (less than 90 characters)";
    }

    // Password
    if (strlen($password) > 150) {
        $message["passwordLength"] = "Your password is too large (less than 150 characters)";
    }

    // Length validation 'small' (you can change length but not more than database length)

    // Email
    if (strlen($email) < 11) {
        $message["emailLength"] = "Your email is too small (more than 11 characters)";
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
    // Start login operation
    try {
        // Query
        $sql = "SELECT email, pass, id FROM users WHERE email = ?";

        // Set error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare
        $stmt = $conn->prepare($sql);

        // Bind param and execute
        $stmt->execute([$email]);

        // Fetch result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/'>me</a>) to report bug)");
    }

    // Check user is exist
    if ($result) {
        // Check password
        if ($result["pass"] === $password && $result["email"] === $email) {
            // Login is successfully
            $_SESSION["user_id"] = $result["id"];
            echo success("Login successfully");
            echo button("success", "/", "Back to home");
        } else {
            // Login is failed because password or email doesn't match
            echo error("Your password is incorrect");
            echo button("danger", "/users/login/", "Back");
        }
    } else {
        echo error("There is no account (Please <a href='/users/signup/'>sign up</a>)");
    }
}