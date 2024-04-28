<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <title>Login</title>
    <style>
        body {
            padding: 10px;
        }
    </style>
</head>

<body>
    <p class="fw-bold fs-2">Login form</p>
    <form class="row g-3" action="/users/login/login.php" method="post">
        <div class="col-md-6">
            <label for="emailInput" class="form-label">Email</label>
            <input type="email" class="form-control" id="emailInput" name="email">
        </div>
        <div class="col-md-6">
            <label for="passwordInput" class="form-label">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password">
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </div>
    </form>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>