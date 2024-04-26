<?php

// For add 'ative' class to active link

if ($_SERVER["PHP_SELF"] == "/index.php") {
    $home = "dark";
} else {
    $home = "secondary";
}

if ($_SERVER["PHP_SELF"] == "/about.php") {
    $about = "dark";
} else {
    $about = "secondary";
}

?>

<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none fw-bold">
            ACE
        </a>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="/" class="nav-link px-2 link-<?= $home ?>">Home</a></li>
            <li><a href="/about.php" class="nav-link px-2 link-<?= $about ?>">About</a></li>
        </ul>

        <div class="col-md-3 text-end">
            <a class="btn btn-outline-primary me-2" href="users/login/">Login</a>
            <a class="btn btn-primary" href="users/signup/">Sign-up</a>
        </div>
    </header>
</div>