<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch products
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Apply filters
$query = "SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY $order_by $order";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <!-- Custom fonts and styles -->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table-container {
            max-width: 100%;
            overflow-x: auto;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-text mx-3">Admin Panel</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="products.php">
                    <i class="fas fa-fw fa-boxes"></i>
                    <span>Manage Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add-product.php">
                    <i class="fas fa-fw fa-plus-circle"></i>
                    <span>Add Product</span>
                </a>
            </li>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="users.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Manage Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add-user.php">
                    <i class="fas fa-fw fa-user-plus"></i>
                    <span>Add User</span>
                </a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
        </ul>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="logout.php">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            
            <!-- Page Content -->
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Products</h1>

                <!-- Display success or error message -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-info" role="alert">
                        <?php echo $_SESSION['message']; ?>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <form method="GET" action="" class="mb-3">
                    <div class="form-row align-items-center">
                        <div class="col-sm-4 my-1">
                            <input type="text" name="search" class="form-control" placeholder="Search by name" value="<?php echo $search; ?>">
                        </div>
                        <div class="col-sm-3 my-1">
                            <select name="order_by" class="form-control">
                                <option value="id" <?php if ($order_by === 'id') echo 'selected'; ?>>ID</option>
                                <option value="name" <?php if ($order_by === 'name') echo 'selected'; ?>>Name</option>
                                <option value="price" <?php if ($order_by === 'price') echo 'selected'; ?>>Price</option>
                                <option value="quantity" <?php if ($order_by === 'quantity') echo 'selected'; ?>>Quantity</option>
                            </select>
                        </div>
                        <div class="col-sm-3 my-1">
                            <select name="order" class="form-control">
                                <option value="ASC" <?php if ($order === 'ASC') echo 'selected'; ?>>Ascending</option>
                                <option value="DESC" <?php if ($order === 'DESC') echo 'selected'; ?>>Descending</option>
                            </select>
                        </div>
                        <div class="col-auto my-1">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="products.php" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-container">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0) : ?>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                                        <td><?php echo number_format($row['price'], 2); ?></td>
                                        <td><?php echo $row['quantity']; ?></td>
                                        <td><img src="../uploads/images/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" width="80"></td>
                                        <td>
                                            <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <form method="POST" action="del-product.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center">No products found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="export-products.php?format=csv" class="btn btn-outline-success mr-2">Export as CSV</a>
                    <a href="export-products.php?format=pdf" class="btn btn-outline-danger">Export as PDF</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>
