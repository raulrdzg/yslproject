<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include("../includes/db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch product details
    $query = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        header("Location: products-list.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $imageName);
    } else {
        $imageName = $product['image']; // Keep the old image if no new one is uploaded
    }

    // Update product in database
    $query = "UPDATE products SET name = '$name', description = '$description', price = '$price', quantity = '$quantity', image = '$imageName' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: products-list.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
            
            <!-- Products List Link -->
            <li class="nav-item">
                <a class="nav-link" href="products-list.php">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Products</span>
                </a>
            </li>

            <!-- Add Product Link -->
            <li class="nav-item">
                <a class="nav-link" href="add-product.php">
                    <i class="fas fa-fw fa-plus-circle"></i>
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
                
        <!-- Content -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Edit Product</h1>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Product Description</label>
                            <textarea class="form-control" id="description" name="description" required><?php echo $product['description']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Product Price</label>
                            <input type="text" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Product Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <p>Current Image: <?php echo $product['image']; ?></p>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>
