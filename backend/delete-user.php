<?php
session_start();
include("../includes/db.php");

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Check if user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete query
    $delete_query = "DELETE FROM users WHERE id = $user_id";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['success'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting user: " . mysqli_error($conn);
    }
} else {
    $_SESSION['error'] = "No user ID provided.";
}

header("Location: users-list.php");
exit;
?>
