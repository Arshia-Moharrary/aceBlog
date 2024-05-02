<?php

session_start();
include "../includes/role.php";
include "../includes/ui.php";

// Make connection with database
require_once "../includes/connection.php";

// Give blog info
if (isset($_GET["id"])) {
    try {
        // Query
        $sql = "SELECT * FROM blogs WHERE id = ?";

        // Set error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare
        $stmt = $conn->prepare($sql);

        // Bind param and execute
        $stmt->execute([$_GET["id"]]);

        // Fetch result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/'>me</a> to report bug)");
    }
} else {
    echo error("There is no blog");
    exit;
}

// Check blog is exist
if ($result === false) {
    echo error("There is no blog");
    exit;
}

// Check blog deleted or unpublished
if ($result["status"] == "unpublished" || $result["status"] == "deleted") {
    // Check user is admin
    if (isset($_SESSION["user_id"])) {
        if (giveRole($_SESSION["user_id"]) != "admin") {
            echo error("There is no blog");
            exit;
        }
    } else {
        echo error("There is no blog");
        exit;
    }
}

// Give author info
try {
    // Query
    $sql = "SELECT username FROM users WHERE id = ?";

    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare
    $stmt = $conn->prepare($sql);

    // Bind param and execute
    $stmt->execute([$result["author_id"]]);

    // Fetch result
    $author = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/'>me</a> to report bug)");
}

// Explode tags
$tags = explode(",", $result["tags"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Blog</title>
    <style>
        body {
            padding: 10px;
        }
    </style>
</head>

<body>
    <?php include "../includes/header.php" ?>
    <p class="fs-1 fw-bold"><?= $result["title"]; ?></p>
    <img src="/uploads/<?= $result['featured_image'] ?>" style="width: 13rem; height: 13rem; object-fit: cover;" alt="">
    <br>
    <p class="fs-3 fw-semibold">Author:</p>
    <a class="list-group-item list-group-item-action d-flex align-items-center text-bg-secondary rounded p-2" href="">
        <img src="/img/user.png" alt="@mdo" width="32" height="32" class="rounded me-2" loading="lazy">
        <span>
            <strong><?= $author["username"]; ?></strong>
        </span>
    </a>
    <br>
    <!-- Blog content -->
    <div class="pre-scrollable">
        <p class="fs-4" style="white-space: pre-wrap; word-wrap: break-word;"><?= $result['content'] ?></p>
    </div>
    <br>
    <p class="fs-3 fw-semibold">Tags:</p>
    <?php

    foreach($tags as $tag) {
        echo "<span class='btn btn-secondary'>#{$tag}</span> ";
    }

    ?>
    <br>
    <?php include "../includes/footer.php" ?>
</body>

</html>