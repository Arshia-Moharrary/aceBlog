<?php

// For add 'ative' class to active link

if ($_SERVER["PHP_SELF"] == "/index.php") {
    $home = "active";
}

if ($_SERVER["PHP_SELF"] == "/about.php") {
    $about = "active";
}

?>

<nav class="navbar navbar-expand-lg border-bottom border-black" style="--bs-border-opacity: .3;">
    <div class="container-fluid">
        <a class="navbar-brand" href="/index.html">ACE</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $home ?>" aria-current="page" href="/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $about ?>" href="/about.php">About me</a>
                </li>
            </ul>
            <div class="d-flex">
                <a href="users/signup/signup.php" class="btn btn-success me-2">Sign up</a>
                <a href="users/login/login.php" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</nav>
<br>