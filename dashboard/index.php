<?php session_start();
include "../includes/ui.php";
include "../includes/role.php"; ?>
<?php require_once "../includes/connection.php"; ?>

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

<?php

// Sanitize inputs (Reference from w3schools)
function test_input($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

if (isset($_GET["section"])) {
    $section = test_input($_GET["section"]);
}

?>

<?php

// Check user is logged in
if (!(isset($_SESSION["user_id"]))) {
    echo "<link rel='stylesheet' href='../css/bootstrap.min.css'>";
    echo "<style>body {padding: 10px;}</style>";
    echo error("You aren't <a href='/users/login'>login</a>");
    exit;
}

?>

<body>
    <?php include "../includes/header.php" ?>
    <div class="d-flex justify-content-center row g-1">
        <div class="col-2">
            <div class="card col">
                <a href="?section=account_detail" class="btn btn-info">Account detail</a>
            </div>
        </div>
        <?php

        $role = giveRole($_SESSION["user_id"]);

        if ($role == "admin") {

        ?>
            <div class="col-2">
                <div class="card col">
                    <a href="?section=blog" class="btn btn-warning">Blog</a>
                </div>
            </div>
        <?php } ?>
        <div class="col-2">
            <div class="card col">
                <a href="?section=logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
    <br>
    <?php

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
    <?php if (isset($section) && $section === "account_detail") { ?>
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
    <?php if (isset($section) && $section === "blog" && $role == "admin") { ?>
        <div class="d-flex justify-content-center row g-1">
            <div class="col-2">
                <div class="card col">
                    <a href="?section=blog&op=create_blog" class="btn btn-primary">Create blog</a>
                </div>
            </div>
            <div class="col-2">
                <div class="card col">
                    <a href="?section=blog&op=published_blog" class="btn btn-success">Published blog</a>
                </div>
            </div>
            <div class="col-2">
                <div class="card col">
                    <a href="?section=blog&op=deleted_blog" class="btn btn-danger">Deleted blog</a>
                </div>
            </div>
            <div class="col-2">
                <div class="card col">
                    <a href="?section=blog&op=unpublished_blog" class="btn btn-warning">Unpublished blog</a>
                </div>
            </div>
        </div>
        <?php if(isset($_GET["op"]) && $_GET["op"] == "create_blog") { ?>
        <p class="fs-2 fw-bold text-primary">Create blog</p>
        <form action="../blogs/create.php" enctype="multipart/form-data" method="post">
            <div class="mb-3">
                <label for="blogTitle" class="form-label">Blog title</label>
                <input type="text" class="form-control" id="blogTitle" name="title">
            </div>
            <div class="mb-3">
                <label for="coverBlog" class="form-label">Blog cover</label>
                <input class="form-control" type="file" id="coverBlog" name="image">
            </div>
            <div class="mb-3">
                <label for="blogContent" class="form-label">Blog content</label>
                <textarea class="form-control" id="blogContent" rows="8" name="content"></textarea>
            </div>
            <div class="mb-3">
                <label for="blogTags" class="form-label">Blog tags</label>
                <input type="text" class="form-control" id="blogTags" placeholder="tag1,tag2 (seperate with comma without any space)" name="tags">
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="publishBlog" name="publish">
                    <label class="form-check-label" for="publishBlog">
                        Publish when created
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-warning">Create</button>
            </div>
        </form>
        <?php } ?>
        <?php if(isset($_GET["op"]) && $_GET["op"] == "unpublished_blog") { ?>
        <p class="fs-2 fw-bold text-warning">Unpublished blogs</p>
        <div class="container text-center">
            <div class="row g-2">
                <?php

                // Give unpublished blogs from database
                try {
                    // Query
                    $sql = "SELECT id, title, content, featured_image FROM blogs WHERE status = 'unpublished'";

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
                    $content = substr($blog['content'], 0, 30);
                    echo "<div class='col-3'>";
                    echo "<div class='card col'>";
                    echo "<img src='/uploads/{$blog['featured_image']}' class='card-img-top' style='width=13rem; height: 13rem; object-fit: cover;'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>{$blog['title']}</h5>";
                    echo "<p class='card-text'>{$content}...</p>";
                    echo "<a href='/blogs/publish.php?id={$blog['id']}' class='btn btn-success'>Publish</a> ";
                    echo "<a href='/blogs/delete.php?id={$blog['id']}' class='btn btn-danger'>Delete</a> ";
                    echo "<a href='/blogs/blog.php?id={$blog['id']}' class='btn btn-primary'>Read</a>";
                    echo "</div></div></div>";
                }

                ?>
            </div>
        </div>
        <?php } ?>
        <?php if(isset($_GET["op"]) && $_GET["op"] == "published_blog") { ?>
        <p class="fs-2 fw-bold text-success">Published blogs</p>
        <div class="container text-center">
            <div class="row g-2">
                <?php

                // Give published blogs from database
                try {
                    // Query
                    $sql = "SELECT id, title, content, featured_image FROM blogs WHERE status = 'published'";

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
                    $content = substr($blog['content'], 0, 30);
                    echo "<div class='col-3'>";
                    echo "<div class='card col'>";
                    echo "<img src='/uploads/{$blog['featured_image']}' class='card-img-top' style='width=13rem; height: 13rem; object-fit: cover;'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>{$blog['title']}</h5>";
                    echo "<p class='card-text'>{$content}...</p>";
                    echo "<a href='/blogs/delete.php?id={$blog['id']}' class='btn btn-danger'>Delete</a> ";
                    echo "<a href='/blogs/blog.php?id={$blog['id']}' class='btn btn-primary'>Read</a>";
                    echo "</div></div></div>";
                }

                ?>
            </div>
        </div>
        <?php } ?>
        <?php if(isset($_GET["op"]) && $_GET["op"] == "deleted_blog") { ?>
        <p class="fs-2 fw-bold text-danger">Deleted blogs</p>
        <div class="container text-center">
            <div class="row g-2">
                <?php

                // Give deleted blogs from database
                try {
                    // Query
                    $sql = "SELECT id, title, content, featured_image FROM blogs WHERE status = 'deleted'";

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
                    $content = substr($blog['content'], 0, 30);
                    echo "<div class='col-3'>";
                    echo "<div class='card col'>";
                    echo "<img src='/uploads/{$blog['featured_image']}' class='card-img-top' style='width=13rem; height: 13rem; object-fit: cover;'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>{$blog['title']}</h5>";
                    echo "<p class='card-text'>{$content}...</p>";
                    echo "<a href='/blogs/delete.php?id={$blog['id']}' class='btn btn-success'>Restore</a> ";
                    echo "<a href='/blogs/blog.php?id={$blog['id']}' class='btn btn-primary'>Read</a>";
                    echo "</div></div></div>";
                }

                ?>
            </div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php

    if (!(isset($section))) {
        echo "<p class='text-center opacity-75'>Nothing to show, select a section :)</p>";
    }

    ?>
    <?php

    // Logout
    if (isset($section) && $section === "logout") {
        session_destroy();
        header("Location: /");
    }

    ?>
    <?php include "../includes/footer.php" ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>