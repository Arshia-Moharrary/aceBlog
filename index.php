<?php
session_start();
include "includes/ui.php";
include "includes/role.php";
require_once "includes/connection.php";

?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>ACE blog</title>
    <style>
        body {
            padding: 10px;
        }
    </style>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <br>
    <p class="fs-2 text-center">Blogs</p>
    <div class="container text-center">
        <div class="row g-2">
            <?php

            // Give published blogs from database
            try {
                // Query
                $sql = "SELECT id, title, content, featured_image FROM blogs WHERE status = 'published' ORDER BY published_date DESC";

                // Set error mode
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Prepare
                $stmt = $conn->prepare($sql);

                // Bind param and execute
                $stmt->execute();

                // Fetch result
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> to report bug)");
            }

            foreach ($result as $blog) {
                $content = substr($blog['content'], 0, 70);
                echo "<div class='col-3'>";
                echo "<div class='card col'>";
                echo "<img src='/uploads/{$blog['featured_image']}' class='card-img-top' style='width=13rem; height: 13rem; object-fit: cover;'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>{$blog['title']}</h5>";
                echo "<p class='card-text'>{$content}...</p>";
                echo "<a href='/blogs/blog.php?id={$blog['id']}' class='btn btn-primary'>Read</a>";
                echo "</div></div></div>";
            }

            ?>
        </div>
    </div>
    <?php include "includes/footer.php" ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>