<?php
session_start();
include("../includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CPSM - Login</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        /* Center the card and make it larger */
        .card {
            max-width: 1000px; /* Increase card width */
            margin: 100px auto;
            padding: 40px;
            border-radius: 20px;
        }

        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Left side with logo */
        .card-body .logo-container {
            width: 45%;
            height: 100%; /* Ensure the logo takes up full height */
            text-align: center;
        }

        .card-body .logo-container img {
            width: 100%; /* Make logo take up full width */
            height: 100%; /* Make logo take up full height */
            object-fit: cover; /* Cover the full area of the logo container */
        }

        /* Form styling */
        .form-container {
            width: 55%; /* Form takes up 55% of the card width */
            padding: 20px;
        }

        .form-container h1 {
            font-size: 32px; /* Larger heading */
            margin-bottom: 30px;
        }

        .form-container .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            font-size: 18px; /* Larger form input text */
            padding: 1.25rem; /* Increase padding for inputs */
        }

        .btn-user {
            font-size: 18px;
            padding: 1rem;
        }

        /* Password toggle emoji */
        .input-group-append .toggle-password {
            cursor: pointer;
            font-size: 1.5rem;
        }

    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body">
                <!-- Left side for logo -->
                <div class="logo-container">
                    <img src="../uploads/logos/Logo True.png" alt="Logo" class="img-fluid">
                </div>

                <!-- Form on the right side -->
                <div class="form-container">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                    </div>

                    <?php if (isset($error)) echo "<p class='alert alert-danger'>$error</p>"; ?>

                    <form method="POST" action="" class="user">
                        <div class="form-group">
                            <input type="username" class="form-control form-control-user" id="username" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="toggle-password" id="toggle-password">⦾</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="small" href="register.php">Don't have an account? Register!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/sb-admin-2.min.js"></script>

    <script>
        // Password toggle script with emoji
        document.getElementById('toggle-password').addEventListener('click', function () {
            var passwordField = document.getElementById('password');
            var toggleIcon = document.getElementById('toggle-password');

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.textContent = '⦿';  // Eye emoji when password is visible
            } else {
                passwordField.type = "password";
                toggleIcon.textContent = '⦾';  // See-no-evil monkey emoji when password is hidden
            }
        });
    </script>

</body>

</html>
