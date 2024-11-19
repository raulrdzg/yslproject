<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include("../includes/db.php"); // Ensure correct path to db.php

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate form inputs
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];

    // Image upload handling
    $product_image = $_FILES['product_image'];
    $image_name = $product_image['name'];
    $image_tmp = $product_image['tmp_name'];
    $image_size = $product_image['size'];
    $image_error = $product_image['error'];

    // Validate image
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    if (!in_array($image_extension, $allowed_extensions)) {
        $error = "Invalid image format. Please upload jpg, jpeg, png, or gif.";
    } elseif ($image_size > 5000000) { // Max file size 5MB
        $error = "Image size is too large. Maximum size is 5MB.";
    } elseif ($image_error !== 0) {
        $error = "Error uploading the image.";
    } else {
        // Move the uploaded image to the desired folder
        $upload_dir = "../uploads/images/";
        $image_new_name = uniqid('', true) . '.' . $image_extension;
        $image_path = $upload_dir . $image_new_name;
        if (move_uploaded_file($image_tmp, $image_path)) {
            // Insert new product into the database
            $query = "INSERT INTO products (name, description, price, quantity, image) 
                      VALUES ('$product_name', '$product_description', '$product_price', '$product_quantity', '$image_new_name')";

            if (mysqli_query($conn, $query)) {
                $success = "Product added successfully!";
            } else {
                $error = "Error adding product: " . mysqli_error($conn);
            }
        } else {
            $error = "Failed to upload image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 500px;
            margin: 0 auto;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-container input, .form-container textarea {
            margin-bottom: 10px;
        }
    </style>
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
                    <h1 class="h3 mb-4 text-gray-800">Add Product</h1>
                    <?php if (isset($success)) echo "<p class='alert alert-success'>$success</p>"; ?>
                    <?php if (isset($error)) echo "<p class='alert alert-danger'>$error</p>"; ?>
                    <div class="form-container">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" required>
                            </div>
                            <div class="form-group">
                                <label for="product_description">Product Description</label>
                                <textarea class="form-control" id="product_description" name="product_description" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="product_price">Product Price</label>
                                <input type="number" class="form-control" id="product_price" name="product_price" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="product_quantity">Product Quantity</label>
                                <input type="number" class="form-control" id="product_quantity" name="product_quantity" required>
                            </div>
                            <div class="form-group">
                                <label for="product_image">Product Image</label>
                                <input type="file" class="form-control" id="product_image" name="product_image" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>

</body>
</html>
