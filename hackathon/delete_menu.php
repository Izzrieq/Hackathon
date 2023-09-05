<?php
include "db/connection.php";

// Wajib ada setiap page
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
    exit;
}

// Check if the user is an admin with a cafe jawatan
if ($_SESSION['type'] === 'admin' && $_SESSION['jawatan'] === 'cafe') {
    // Check if an item ID is provided in the URL
    if (isset($_GET['id'])) {
        $item_id = $_GET['id'];

        // Query to retrieve the menu item based on the provided item ID
        $sql = "SELECT * FROM cafe_menu WHERE id = $item_id";
        $result = $conn->query($sql);

        if ($result === false) {
            echo "Query error: " . $conn->error;
            exit;
        }

        // Check if the query returned any rows
        if ($result->num_rows == 1) {
            $menuItem = $result->fetch_assoc();
        } else {
            echo "Menu item not found.";
            exit;
        }

        // Check if the form was submitted for confirming the deletion
        
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    // Delete associated orders first
    $deleteOrdersSql = "DELETE FROM orders WHERE item_id = $item_id";

    if ($conn->query($deleteOrdersSql) === TRUE) {
        // Then delete the menu item
        $deleteSql = "DELETE FROM cafe_menu WHERE id = $item_id";

        if ($conn->query($deleteSql) === TRUE) {
            echo "<script>alert('Menu item and associated orders deleted successfully!');</script>";
            // Redirect to view menu page after deletion
            echo "<script>window.location.href = 'view_menu.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error deleting the menu item: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error deleting associated orders: " . $conn->error . "');</script>";
    }
}
    } else {
        echo "Item ID not provided.";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Menu Item</title>
<style>
body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            font-size: 32px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        p {
            margin-bottom: 10px;
        }

        strong {
            font-weight: bold;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 18px;
            text-decoration: none;
            color: red;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Delete Menu Item</h1>
    <div class="container">
        <p>Are you sure you want to delete the following menu item?</p>
        <p><strong>Item Name:</strong> <?php echo $menuItem['item_name']; ?></p>
        <p><strong>Description:</strong> <?php echo $menuItem['description']; ?></p>
        <p><strong>Price (RM):</strong> <?php echo number_format($menuItem['price'], 2); ?></p>
        <p><strong>Available:</strong> <?php echo ($menuItem['is_available']) ? 'Yes' : 'No'; ?></p>
        <form method="POST" action="delete_menu.php?id=<?php echo $item_id; ?>">
            <input type="submit" name="confirm_delete" value="Confirm Delete">
        </form>
        <a href="view_menu.php">Cancel</a>
    </div>
</body>
</html>

<?php
} else {
    echo "Access denied. You do not have permission to view this page.";
}
?>
