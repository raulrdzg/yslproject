<?php
include("includes/db.php");

if ($conn) {
    echo "Connection successful!";
} else {
    echo "Connection failed!";
}