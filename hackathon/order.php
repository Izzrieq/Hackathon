<?php
include "db/connection.php";
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form was submitted

    // Retrieve user name
    $nama = $_SESSION['nama'];

    // Initialize an array to store order success messages
    $successMessages = array();

    // Iterate through POST data to find which "Order" button was clicked
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'order_') !== false) {
            // Extract item ID from the button name
            $itemId = str_replace('order_', '', $key);
            $quantityKey = 'quantity_' . $itemId;

            // Check if a valid quantity was provided for this item
            if (isset($_POST[$quantityKey]) && is_numeric($_POST[$quantityKey])) {
                // Retrieve the quantity for this item
                $quantity = intval($_POST[$quantityKey]);

                // Validate the quantity (between 1 and 10)
                if ($quantity >= 1 && $quantity <= 10) {
                    // Fetch menu item details based on the ID
                    $sql = "SELECT * FROM cafe_menu WHERE id = $itemId";
                    $result = $conn->query($sql);

                    if ($result === false) {
                        echo "Query error: " . $conn->error;
                        exit;
                    }

                    if ($result->num_rows == 1) {
                        $row = $result->fetch_assoc();
                        $totalPrice = $row['price'] * $quantity;

                        // Insert the order into the database
                        $sql = "INSERT INTO orders (nama, item_id, quantity, total_price)
                                VALUES ('$nama', $itemId, $quantity, $totalPrice)";

                        if ($conn->query($sql) === TRUE) {
                            // Add a success message to the array
                            $successMessages[] = "Order placed successfully for Item ID $itemId!";
                        } else {
                            echo "Error placing the order for Item ID $itemId: " . $conn->error;
                        }
                    } else {
                        echo "Menu item not found for Item ID $itemId.";
                    }
                } else {
                    echo "Invalid quantity. Please select a quantity between 1 and 10 for Item ID $itemId.";
                }
            }
        }
    }

    // Check if there are any success messages
    if (!empty($successMessages)) {
        // Display success messages as an alert
        echo "<script>";
        foreach ($successMessages as $message) {
            echo "alert('$message');";
        }
        echo "window.location.href = 'order_cafe.php';</script>";
    }
} else {
    // If the script is accessed directly without a POST request, handle it accordingly
    echo "Invalid request.";
}
?>
