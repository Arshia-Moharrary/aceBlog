<?php session_start();
include "../includes/ui.php" ?>
<?php

// Check user is logged in
if (!(isset($_SESSION["user_id"]))) {
    echo "<link rel='stylesheet' href='../css/bootstrap.min.css'>";
    echo "<style>body {padding: 10px;}</style>";
    echo error("You aren't <a href='/users/login'>login</a>");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Dashboard</title>
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>

<body>
    <?php include "../includes/header.php" ?>
    <div class="d-flex justify-content-center row g-1">
        <div class="col-2">
            <div class="card col">
                <a href="?section=account_detail" class="btn btn-info">Account detail</a>
            </div>
        </div>
        <div class="col-2">
            <div class="card col">
                <a href="?section=logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
    <br>
    <?php

    // Make connection
    require_once "../includes/connection.php";

    try {
        // Query
        $sql = "SELECT username, email, status, role FROM users WHERE id = ?";

        // Set error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare
        $stmt = $conn->prepare($sql);

        // Bind param and execute
        $stmt->execute([$_SESSION["user_id"]]);

        // Fetch result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> and report bug)");
    }

    ?>
    <?php if (isset($_GET["section"]) && $_GET["section"] === "account_detail") { ?>
    <!-- Account detail -->
    <div class="d-flex justify-content-center">
        <table class="table text-center">
            <tbody>
                <?php

                foreach ($result as $key => $val) {
                    $key = ucfirst($key);
                    echo "<tr>";
                    echo "<th scope='row'>{$key}</th>";
                    echo "<td>{$val}</td>";
                }

                ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php
    
    if (!(isset($_GET["section"]))) {
        echo "<p class='text-center opacity-75'>Nothing to show, select a section :)</p>";
    }

    ?>
    <?php
    
    // Logout
    if (isset($_GET["section"]) && $_GET["section"] === "logout") {
        session_destroy();
        header("Location: /");
    }

    ?>
    <?php include "../includes/footer.php" ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>