<?php
session_start();

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    // Database connection
    include("../includes/db.php");

    // Check if the username exists
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;

        // Remember Me logic: Set cookies if checked
        if ($remember) {
            setcookie("username", $username, time() + (86400 * 30), "/"); // Cookie lasts for 30 days
        } else {
            setcookie("username", "", time() - 3600, "/"); // Delete cookie if not checked
        }

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img src="../uploads/logos/logo.png" alt="Logo" class="img-fluid" style="max-height: 100%; width: 100%;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form method="POST" action="">
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control form-control-user" id="username" name="username" required value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control form-control-user" id="password" name="password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text password-toggle" id="show-password" onclick="togglePassword()">◉</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="remember" name="remember" <?php echo isset($_COOKIE['username']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="remember">Remember Me</label>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <?php if (isset($error)) echo "<p class='text-danger text-center mt-2'>$error</p>"; ?>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
    <script>
        // Function to toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var showPasswordIcon = document.getElementById('show-password');

            if (passwordField.type === "password") {
                passwordField.type = "text";
                showPasswordIcon.textContent = "◎";  // Change icon to a closed-eye
            } else {
                passwordField.type = "password";
                showPasswordIcon.textContent = "◉";  // Change icon to an open-eye
            }
        }
    </script>
</body>
</html>
