<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete user
    $query = "DELETE FROM users WHERE id = '$user_id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting user.";
    }
}

header("Location: users.php");
exit;
?>
