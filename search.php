<?php

include "includes/ui.php";
require_once "includes/connection.php";

$q = $_GET["q"];
$qd = "%" . $_GET["q"] . "%";

try {
    // Query
    $sql = "SELECT id, title, content, featured_image FROM blogs WHERE status = 'published' AND title LIKE ?";

    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare
    $stmt = $conn->prepare($sql);

    // Bind param and execute
    $stmt->execute([$qd]);

    // Fetch result
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> to report bug)");
}

if (count($result) === 0) {
    echo "<p class='text-center fs-4 text-secondary'>We couldn't find result for '{$q}' :(</p>";
}

// Return results
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
