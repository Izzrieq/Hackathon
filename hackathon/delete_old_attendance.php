<?php
include "db/connection.php";

// Calculate the date and time 12 hours ago
$twelveHoursAgo = date("Y-m-d H:i:s", strtotime("-12 hours"));

// Query to select orders older than 12 hours
$sql = "SELECT * FROM attendance_records WHERE date <= '$twelveHoursAgo'";
$result = $conn->query($sql);

if ($result === false) {
    echo "Query error: " . $conn->error;
    exit;
}

// Delete orders older than 12 hours
while ($row = $result->fetch_assoc()) {
    $attId = $row['id'];
    $sqlDelete = "DELETE FROM attendance_records WHERE id = $attId";
    
    if ($conn->query($sqlDelete) === true) {
        echo "ID $attId deleted successfully.<br>";
    } else {
        echo "Error deleting ID $attId: " . $conn->error . "<br>";
    }
}

// Close your database connection here
?>