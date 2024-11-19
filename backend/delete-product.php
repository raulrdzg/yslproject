<?php
session_start();
include("../includes/db.php"); // Ensure the path to your DB connection is correct

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

// Check if the product ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the product details to get the image filename
    $query = "SELECT image FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    if ($product) {
        // Check if the product has an image and delete it
        if ($product['image']) {
            $image_path = "../uploads/" . $product['image']; // Set the path to the image file
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image from the server
            }
        }

        // Prepare the SQL query to delete the product from the database
        $query = "DELETE FROM products WHERE id = $product_id";
        
        if (mysqli_query($conn, $query)) {
            // Successfully deleted, redirect to products list with a success message
            header("Location: products-list.php?message=Product deleted successfully");
            exit;
        } else {
            // Error deleting the product from the database
            $error = "Failed to delete the product!";
        }
    } else {
        // Product not found in the database
        $error = "Product not found!";
    }
} else {
    // Invalid ID, redirect to products list with an error message
    header("Location: products-list.php?error=Invalid product ID");
    exit;
}
?>
