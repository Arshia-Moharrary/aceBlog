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
    <p class="fw-bold fs-2">Sign up form</p>
    <form class="row g-3" action="/users/signup/signup.php" method="post" id="signup">
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
            <input type="password" class="form-control" id="repeatPasswordInput" name="repeatPassword">
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Sign up</button>
        </div>
    </form>
    <br>
    <div id="message"></div>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <!--  JQuery  -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function () {
            $("#signup").submit(function (event) {
                // Prevent default behavior of form
                event.preventDefault();

                // Values
                let username = $("#usernameInput").val();
                let email = $("#emailInput").val();
                let password = $("#passwordInput").val();
                let repeatPassword = $("#repeatPasswordInput").val();

                // Container of searched blogs
                let result = $("#message");

                // Send ajax request
                $.ajax(
                    {
                        url: $(this).attr("action"),
                        data: {username: username, email: email, password: password, repeatPassword: repeatPassword},
                        method: $(this).attr("method"),
                        success: function (response) {
                            result.html(response);
                        }
                    }
                )
            })
        })
    </script>
</body>

</html>