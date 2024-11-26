<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Check if the delete button was clicked
if (isset($_POST['delete'])) {
    $product_id = $_POST['id'];

    // Delete the product from the database
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $product_id);

    if (mysqli_stmt_execute($stmt)) {
        // Set a session variable for success message
        $_SESSION['message'] = "Product deleted successfully.";
    } else {
        // Set a session variable for error message
        $_SESSION['message'] = "Failed to delete product.";
    }

    // Close the statement and redirect
    mysqli_stmt_close($stmt);
    header("Location: products.php");
    exit;
}
?>
