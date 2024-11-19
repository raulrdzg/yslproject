<?php
session_start();
include("../includes/db.php"); // Database connection

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Get the user ID from the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    header("Location: users-list.php"); // Redirect if no ID is provided
    exit;
}

// Fetch the user's details from the database
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

// Check if the user exists
if (mysqli_num_rows($result) == 0) {
    die("User not found.");
}

$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Update query
    $update_query = "UPDATE users SET username = '$username', email = '$email', role = '$role' WHERE id = $user_id";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['success'] = "User updated successfully!";
        header("Location: users-list.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <li class="nav-item active">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="users-list.php">
                <i class="fas fa-fw fa-users"></i>
                <span>Users</span>
            </a>
        </li>
    </ul>
    <!-- End of Sidebar -->

    <!-- Main Content Area -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Edit User</h1>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Update User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

</body>

</html>