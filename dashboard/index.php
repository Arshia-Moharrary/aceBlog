<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/sidebars.css">
    <title>Dashboard</title>
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>

<body>
    <?php include "../includes/header.php" ?>
    <div class="d-flex justify-content-center row g-1">
        <div class="col-2">
            <div class="card col">
                <a href="?section=account_detail" class="btn btn-info">Account detail</a>
            </div>
        </div>
        <div class="col-2">
            <div class="card col">
                <a href="?section=logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex justify-content-center">
        <table class="table text-center">
            <tbody>
                <tr>
                    <th scope="row">Username</th>
                    <td>arshia_moharrary</td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td>arshiabd1388@gmail.com</td>
                </tr>
                <tr>
                    <th scope="row">Status</th>
                    <td class="text-success">enable</td>
                </tr>
                <tr>
                    <th scope="row">Role</th>
                    <td class="text-primary">user</td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php include "../includes/footer.php" ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>