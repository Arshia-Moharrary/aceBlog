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
    <?php if (isset($_GET["section"]) && $_GET["section"] === "blog" && $role == "admin") { ?>
        <p class="fs-2 fw-bold">Create blog</p>
        <form action="">
            <div class="mb-3">
                <label for="blogTitle" class="form-label">Blog title</label>
                <input type="email" class="form-control" id="blogTitle">
            </div>
            <div class="mb-3">
                <label for="coverBlog" class="form-label">Blog cover</label>
                <input class="form-control" type="file" id="coverBlog">
            </div>
            <div class="mb-3">
                <label for="blogContent" class="form-label">Blog content (for new line: \n)</label>
                <textarea class="form-control" id="blogContent" rows="8"></textarea>
            </div>
            <div class="mb-3">
                <label for="blogTags" class="form-label">Blog tags</label>
                <input type="text" class="form-control" id="blogTags" placeholder="tag1,tag2 (seperate with comma without any space)">
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="publishBlog">
                    <label class="form-check-label" for="publishBlog">
                        Publish when created
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-warning" name="sign">Create</button>
            </div>
        </form>
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