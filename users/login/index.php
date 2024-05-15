<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <title>Login</title>
    <style>
        body {
            padding: 10px;
        }
    </style>
</head>

<body>
    <p class="fw-bold fs-2">Login form</p>
    <form class="row g-3" action="/users/login/login.php" method="post" id="login">
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
    <br>
    <div id="message"></div>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <!--  JQuery  -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function () {
            $("#login").submit(function (event) {
                // Prevent default behavior of form
                event.preventDefault();

                // Values
                let email = $("#emailInput").val();
                let password = $("#passwordInput").val();

                // Container of searched blogs
                let result = $("#message");

                // Send ajax request
                $.ajax(
                    {
                        url: $(this).attr("action"),
                        data: {email: email, password: password},
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