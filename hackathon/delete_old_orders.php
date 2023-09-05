<?php
include "db/connection.php";

// Calculate the date and time 12 hours ago
$twelveHoursAgo = date("Y-m-d H:i:s", strtotime("-12 hours"));

// Query to select orders older than 12 hours
$sql = "SELECT * FROM orders WHERE order_date <= '$twelveHoursAgo'";
$result = $conn->query($sql);

if ($result === false) {
    echo "Query error: " . $conn->error;
    exit;
}

// Delete orders older than 12 hours
while ($row = $result->fetch_assoc()) {
    $orderId = $row['id'];
    $sqlDelete = "DELETE FROM orders WHERE id = $orderId";
    
    if ($conn->query($sqlDelete) === true) {
        echo "Order ID $orderId deleted successfully.<br>";
    } else {
        echo "Error deleting order ID $orderId: " . $conn->error . "<br>";
    }
}

// Close your database connection here
?>