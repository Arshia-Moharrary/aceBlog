<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <title>Sign up</title>
    <style>
        body {
            padding: 10px;
        }
    </style>
</head>

<body>
    <p class="fw-bold fs-2">Sign up admin account</p>
    <form class="row g-3" action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
        <div class="col-md-6">
            <label for="usernameInput" class="form-label">Username</label>
            <input type="text" class="form-control" id="usernameInput" name="username">
        </div>
        <div class="col-md-6">
            <label for="emailInput" class="form-label">Email</label>
            <input type="email" class="form-control" id="emailInput" name="email">
        </div>
        <div class="col-12">
            <label for="passwordInput" class="form-label">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password">
        </div>
        <div class="col-12">
            <label for="passwordInput" class="form-label">Repeat password</label>
            <input type="password" class="form-control" id="passwordInput" name="repeatPassword">
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary" name="sign">Sign up</button>
        </div>
    </form>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php

// Start session
session_start();

if (isset($_POST)) {
    // Includes
    require_once "../../includes/ui.php";

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
        if (strlen($email) < 11) {
            $message["emailLength"] = "Your email is too small (more than 11 characters)";
        }

        // Password
        if (strlen($password) < 8) {
            $message["passwordLength"] = "Your password is too small (more than 8 characters)";
        }

        // Username exist validate
        if (count($message) === 0) {
            // Start check operation
            try {
                // Query
                $sql = "SELECT username FROM users WHERE username = ?";

                // Set error mode
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Prepare
                $stmt = $conn->prepare($sql);

                // Bind param and execute
                $stmt->execute([$username]);

                // Fetch result
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> and report bug) <");
            }

            if ($result) {
                $message["usernameExist"] = "This username already taken";
            }
        }

        // Email exist validate
        if (count($message) === 0) {
            // Start check operation
            try {
                // Query
                $sql = "SELECT email FROM users WHERE email = ?";

                // Set error mode
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Prepare
                $stmt = $conn->prepare($sql);

                // Bind param and execute
                $stmt->execute([$email]);

                // Fetch result
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> and report bug) <");
            }

            if ($result) {
                $message["emailExist"] = "This email already taken, Please <a href='/users/login/'>login</a>";
            }
        }
    }

    // Show messages
    foreach ($message as $key => $val) {
        echo error($val);
    }



    if (count($message) === 0) {
        // Start signup operation
        try {
            // Query
            $sql = "INSERT INTO users (username, email, pass, status, role) VALUES (?, ?, ?, ?, ?)";

            // Set error mode
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare
            $stmt = $conn->prepare($sql);

            // Bind param and execute
            $stmt->execute([$username, $email, $password, "enable", "admin"]);

            echo success("Sign up successfully");
            echo button("success", "/", "Back to home");

            // Set session for user
            $_SESSION["user_id"] = $conn->lastInsertId();
        } catch (PDOException $e) {
            echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> and report bug) <");
        }
    }
}
