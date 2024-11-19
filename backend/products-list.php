<?php
session_start();
include("../includes/db.php"); // Ensure correct path to your DB connection

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

// Handle search and sorting parameters
$search = isset($_GET['search']) ? $_GET['search'] : ''; // Search term
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'name'; // Default sort by name
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC'; // Default sort order

// Query to fetch products with search and sorting functionality
$query = "SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY $sort_by $sort_order";
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
    <title>Products List</title>
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>

<body>
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

            <!-- Products List Link -->
            <li class="nav-item">
                <a class="nav-link" href="products-list.php">
                    <i class="fas fa-fw fa-plus-circle"></i>
                    <span>Products</span>
                </a>
            </li>

            <!-- Add Product List Link -->
            <li class="nav-item">
                <a class="nav-link" href="add-product.php">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Add Product</span>
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
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Product List</h1>

                    <!-- Search and Sort Options -->
                    <form method="GET" action="" class="mb-4">
                        <div class="form-row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" placeholder="Search by name" value="<?php echo $search; ?>">
                            </div>
                            <div class="col-md-2">
                                <select name="sort_by" class="form-control">
                                    <option value="name" <?php if ($sort_by == 'name') echo 'selected'; ?>>Name</option>
                                    <option value="price" <?php if ($sort_by == 'price') echo 'selected'; ?>>Price</option>
                                    <option value="quantity" <?php if ($sort_by == 'quantity') echo 'selected'; ?>>Quantity</option>
                                    <option value="created_at" <?php if ($sort_by == 'created_at') echo 'selected'; ?>>Date Added</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="sort_order" class="form-control">
                                    <option value="ASC" <?php if ($sort_order == 'ASC') echo 'selected'; ?>>Ascending</option>
                                    <option value="DESC" <?php if ($sort_order == 'DESC') echo 'selected'; ?>>Descending</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">Filter</button>
                            </div>
                        </div>
                    </form>

                    <!-- Product Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Image</th>
                                    <th>Date Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                                <td>{$row['id']}</td>
                                                <td>{$row['name']}</td>
                                                <td>{$row['description']}</td>
                                                <td>{$row['price']}</td>
                                                <td>{$row['quantity']}</td>
                                                <td><img src='../uploads/images/{$row['image']}' alt='{$row['name']}' class='img-thumbnail' style='max-width: 100px;'></td>
                                                <td>{$row['created_at']}</td>
                                                <td>
                                                    <a href='edit-product.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                                    <a href='delete-product.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>No products found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div> <!-- End of container-fluid -->

            </div> <!-- End of content -->

        </div> <!-- End of content-wrapper -->

    </div> <!-- End of wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/sb-admin-2.min.js"></script>

</body>

</html>

