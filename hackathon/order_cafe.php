<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
include "db/connection.php";
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
    exit;
}
// Define the opening and closing times
$openTime = strtotime('3:00:00'); // 7:00 PM
$closeTime = strtotime('24:00:00'); // 8:00 PM
$currentTime = strtotime(date('H:i:s')); // Current time in 24-hour format

if ($currentTime < $openTime || $currentTime >= $closeTime) {
    // The cafe is closed
    echo "<script>alert('The cafe is currently closed. Please come back between 7:00 PM and 8:00 PM.'); window.location.href = 'home.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order'])) {
    // Check if the form was submitted
    
    // Retrieve user name from the session
    $nama = $_SESSION['nama'];

    // Initialize an array to store orders
    $orders = [];

    // Loop through POST data to find selected items
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'quantity_') !== false && $value > 0) {
            // Extract the item_id from the input name
            $item_id = str_replace('quantity_', '', $key);
            // Validate the quantity (between 1 and 10)
            $value = ($value < 1) ? 1 : (($value > 10) ? 10 : $value);
            // Add the order to the orders array
            $orders[] = [
                'item_id' => $item_id,
                'quantity' => $value
            ];
        }
    }

    // Insert orders into the database
    foreach ($orders as $order) {
        $item_id = $order['item_id'];
        $quantity = $order['quantity'];

        // Fetch menu item details based on the ID
        $sql = "SELECT * FROM cafe_menu WHERE id = $item_id";
        $result = $conn->query($sql);

        if ($result === false) {
            echo "Query error: " . $conn->error;
            exit;
        }

        // Check if the query returned any rows
        if ($result->num_rows == 1) {
            $menuItem = $result->fetch_assoc();

            // Calculate the total price based on the quantity
            $totalPrice = $menuItem['price'] * $quantity;

            // Get the current date and time
            $orderDate = date("Y-m-d H:i:s");

            // Insert the order into the database
            $sql = "INSERT INTO orders (nama, item_id, quantity, total_price, order_date)
                    VALUES ('$nama', $item_id, $quantity, $totalPrice, '$orderDate')";

            if ($conn->query($sql) === TRUE) {
                echo "Order placed successfully!";
            } else {
                echo "Error placing the order: " . $conn->error;
            }
        } else {
            echo "Menu item not found.";
        }
    }
}
?>
<style>
      body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
        }

        .logo-left {
            width: 130px;
            height: auto;
        }

        .logo-right {
            width: 130px; /* Adjust the width as needed */
            height: auto;
            text-align: center; /* Center the right logo */
        }

        .logo img {
            max-width: 100%;
            height: auto;
        }

        .typing-text {
            font-size: 2rem;
            margin: 0;
            padding: 0;
            white-space: nowrap; /* Prevent text from wrapping */
            overflow: hidden;
            border-right: .15em solid orange; /* Add a blinking cursor */
            width: 0;
            animation: typing 2s steps(9) forwards;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            font-size: 32px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ccc;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        table td img {
            max-width: 100px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        table td input[type="number"] {
            width: 60px;
            margin-right: 5px;
            font-size: 20px;
        }

        table td input[type="submit"] {
            font-size: 20px;
        }
        .goback{
            font-size: 30px;
        }

        @media (max-width: 1170px) {
            /* Style for smaller screens */
            h1 {
                font-size: 24px;
            }

            table {
                font-size: 16px;
            }

            table td img {
                max-width: 80px;
            }

            table td input[type="number"] {
                width: 50px;
            }

            table td input[type="submit"] {
                font-size: 16px;
            }
            .logo-left {
                display: none; /* Hide the left logo on smaller screens */
            }

            .logo-right {
                width: 100px; /* Adjust the width as needed */
                margin: 0 auto; /* Center the right logo */
            }

            .typing-text {
                display: none; /* Remove the typed text on smaller screens */
            }
        }
</style>
<!-- Display menu items -->
<header class="navbar mt-0 py-3">
        <div class="logo-left">
            <a href="home.php">
                <img src="assets/img/na-logo.png" alt="Left Logo" height="auto" width="130px">
            </a>
        </div>
        <div class="logo-right">
            <a href="home.php">
                <img src="assets/img/jata-ilp.png" alt="Right Logo" height="auto" width="130px">
            </a>
        </div>
        <h1 class="mt-3 m-3 text-primary">
            <span class="typing-text">ILPKLS</span>
        </h1>
    </header>
<h1>Cafe Menu</h1>
<form method="POST" action="order.php">
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Price (RM)</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM cafe_menu WHERE is_available = 1";
            $result = $conn->query($sql);

            if ($result === false) {
                echo "Query error: " . $conn->error;
                exit;
            }

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><img src='menu_images/" . $row['image'] . "' alt='" . $row['item_name'] . "' width='100'></td>";
                echo "<td>" . $row['item_name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>RM " . number_format($row['price'], 2) . "</td>";
                // Append item_id to the name attribute to make it unique
                echo "<td><input type='number' name='quantity_" . $row['id'] . "' min='1' max='10' value='1'></td>";
                echo "<td>";
                echo "<input type='hidden' name='item_id' value='" . $row['id'] . "'>";
                echo "<input type='submit' name='order_" . $row['id'] . "' value='Order'>"; // Unique button identifier
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</form>
<button onclick="history.back()" class="goback">Go Back</button>
