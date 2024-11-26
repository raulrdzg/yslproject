<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['format'])) {
    die("Invalid format specified.");
}

$format = $_GET['format'];

// Fetch products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching products: " . mysqli_error($conn));
}

// Handle CSV Export
if ($format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=products.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Description', 'Price', 'Quantity', 'Image', 'Created At']);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['description'],
            $row['price'],
            $row['quantity'],
            $row['image'],
            $row['created_at']
        ]);
    }

    fclose($output);
    exit;
}

// Handle PDF Export (using HTML to PDF rendering)
elseif ($format === 'pdf') {
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=products.pdf");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products PDF Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Products List</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars(substr($row['description'], 0, 50)) . '...'; ?></td>
                    <td><?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo htmlspecialchars($row['image']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>
<?php
    exit;
} else {
    die("Unsupported export format.");
}
