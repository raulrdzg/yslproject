<?php
// register.php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Trim and sanitize input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Database connection
        include("../includes/db.php");

        // Check if username or email exists
        $query = "SELECT COUNT(*) AS count FROM users WHERE LOWER(username) = LOWER(?) OR LOWER(email) = LOWER(?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data['count'] > 0) {
            $error = "Username or email already taken!";
        } else {
            // Hash password and insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $_SESSION['admin_logged_in'] = true;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Failed to register!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script>
        function validateForm() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirmPassword").value;
            const passwordError = document.getElementById("passwordError");

            // Check password strength
            const passwordStrength = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordStrength.test(password)) {
                passwordError.textContent = "Password must be at least 8 characters long and include uppercase, lowercase, a number, and a special character.";
                return false;
            }

            // Confirm passwords match
            if (password !== confirmPassword) {
                passwordError.textContent = "Passwords do not match.";
                return false;
            }

            passwordError.textContent = ""; // Clear errors
            return true;
        }
    </script>
</head>
<body class="bg-gradient-primary">
    <div class="container">
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
                                        <h1 class="h4 text-gray-900 mb-4">Create an Account</h1>
                                    </div>
                                    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                                    <form method="POST" action="" onsubmit="return validateForm()">
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control form-control-user" id="username" name="username" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control form-control-user" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control form-control-user" id="password" name="password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmPassword">Confirm Password</label>
                                            <input type="password" class="form-control form-control-user" id="confirmPassword" name="confirmPassword" required>
                                            <p id="passwordError" class="text-danger mt-1"></p>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Register
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="login.php">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
