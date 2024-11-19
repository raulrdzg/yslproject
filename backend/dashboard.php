<?php
include('layout.php');
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Logo and Welcome Message -->
            <div class="sidebar-brand d-flex align-items-center justify-content-center">
                <!-- Logo Image -->
                <img src="../uploads/logos/logo.png" alt="Logo" class="img-fluid" style="max-height: 60px;"> <!-- Replace path to logo -->
            </div>

            <!-- Welcome Message (Below Logo) -->
            <?php if(isset($_SESSION['username'])): ?>
                <div class="text-white text-center mb-3">
                    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                </div>
            <?php endif; ?>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Dashboard Link -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Add Product Link -->
            <li class="nav-item">
                <a class="nav-link" href="add-product.php">
                    <i class="fas fa-fw fa-plus-circle"></i>
                    <span>Add Product</span>
                </a>
            </li>

            <!-- Products List Link -->
            <li class="nav-item">
                <a class="nav-link" href="products-list.php">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Products List</span>
                </a>
            </li>

            <!-- Logout Link (Red, Bold, and Centered) -->
            <li class="nav-item mt-auto text-center">
                <a class="nav-link text-danger font-weight-bold" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
                    <!-- Stats and other content here -->
                    <div class="row">
                        <!-- Example Cards for Stats -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Products</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">50</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>
