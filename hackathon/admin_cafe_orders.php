
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Body Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    /* Header Styles */
    h1 {
        font-size: 24px;
        text-align: center;
        margin-top: 20px;
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #007bff;
        color: #fff;
    }

    /* Button Styles */
    .done-button {
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        padding: 5px 10px;
    }

    /* Highlighted Order Styles */
    .highlighted-order {
        background-color: lightgreen;
    }

    /* Add Menu Link Styles */
    .button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        margin: 0 5px; /* Add margin-left and margin-right of 5px */
        color: #fff;
        text-decoration: none;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
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

    .button:hover {
        background-color: #0056b3;
    }
     /* Media Query for 1170px and Below */
     @media (max-width: 1170px) {
        h1 {
            font-size: 15px;
        }
        table {
            font-size: 10px;
        }
        th,
        td {
            padding: 3px;
        }
        .done-button {
            padding: 4px 8px;
        }
        .add-menu-link,
        .view-menu-link,
        .logout-link {
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
</head>
<body>
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
<?php
include "db/connection.php";
include "delete_old_orders.php";

// Wajib ada setiap page
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
    exit;
}

// Check if the user is an admin with a cafe jawatan
if ($_SESSION['type'] === 'admin' && $_SESSION['jawatan'] === 'cafe') {
// Query to retrieve all orders with user names
$sql = "SELECT o.id, o.nama AS user_name, m.item_name, o.quantity, o.total_price, o.order_date
        FROM orders AS o
        INNER JOIN cafe_menu AS m ON o.item_id = m.id";
        $bil = 1;


    $result = $conn->query($sql);

    if ($result === false) {
        echo "Query error: " . $conn->error;
        exit;
    }

    // Display orders in a table
    echo "<h1>Cafe Orders</h1>";
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Order ID</th>";
    echo "<th>User Name</th>";
    echo "<th>Item Name</th>";
    echo "<th>Quantity</th>";
    echo "<th>Total Price (RM)</th>";
    echo "<th>Order Date</th>";
    echo "<th>Action</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr data-order-id='{$row['id']}'>"; // Add the data-order-id attribute here
        echo "<td>" . $bil. "</td>";
        echo "<td>" . $row['user_name'] . "</td>";
        echo "<td>" . $row['item_name'] . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>RM " . number_format($row['total_price'], 2) . "</td>";
        echo "<td>" . $row['order_date'] . "</td>";
        echo "<td><button class='done-button' data-order-id='{$row['id']}'>Done</button></td>";
        echo "</tr>";
        $bil++;  
    }  
    

    echo "</tbody>";
    echo "</table>";
    echo "<a href='add_menu.php' class='button'>Add Menu</a>";
    echo "<a href='view_menu.php' class='button'>View Menu</a>";
    echo "<a href='logout.php' class='button'>Log Out</a>";
} else {
    echo "Access denied. You do not have permission to view this page.";
}

// Close your database connection here
?>
<!-- Add this JavaScript code within a <script> tag in your HTML file -->
<script>
    // Get all the "Done" buttons
    const doneButtons = document.querySelectorAll('.done-button');

    // Add a click event listener to each "Done" button
    doneButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Get the order ID from the data-order-id attribute
            const orderId = button.getAttribute('data-order-id');

            // Find the corresponding order row and add the 'highlighted-order' class
            const orderRow = document.querySelector(`tr[data-order-id='${orderId}']`);
            orderRow.classList.add('highlighted-order');
        });
    });
</script>

</body>
</html>