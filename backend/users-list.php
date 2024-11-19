<?php
session_start();
include("../includes/db.php"); // Database connection

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

// Fetch users from the database
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

// Check if there was an error in the query
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Sidebar contents -->
        <li class="nav-item active">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="register-user.php">
                <i class="fas fa-fw fa-user-plus"></i>
                <span>Register User</span>
            </a>
        </li>
        <hr class="sidebar-divider my-0">
    </ul>
    <!-- End of Sidebar -->

    <!-- Main Content Area -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Users List</h1>

                <!-- User Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['username']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['role']}</td>
                                            <td>
                                                <a href='edit-user.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                                <a href='delete-user.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

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
