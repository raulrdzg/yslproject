<?php
session_start();
include("../includes/db.php"); // Ensure correct path to your DB connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate the form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        if (mysqli_query($conn, $query)) {
            $success = "User registered successfully!";
        } else {
            $error = "Error registering user: " . mysqli_error($conn);
        }
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

    <title>CPSM - Register</title>

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
                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                    </div>

                    <?php if (isset($success)) echo "<p class='alert alert-success'>$success</p>"; ?>
                    <?php if (isset($error)) echo "<p class='alert alert-danger'>$error</p>"; ?>

                    <form method="POST" action="" class="user">
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Username" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email Address" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="password" class="form-control form-control-user" id="confirm_password" name="confirm_password" placeholder="Repeat Password" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="small" href="login.php">Already have an account? Login!</a>
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

</body>

</html>
