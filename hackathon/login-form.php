<?php
session_start();
include "db/connection.php"; 

// Get user input
$ndp = $_POST['ndp'];
$password = $_POST['password'];

// Sanitize user input to prevent SQL injection
$ndp = mysqli_real_escape_string($conn, $ndp);
$password = mysqli_real_escape_string($conn, $password);

// Perform database query
$sql = "SELECT * FROM users WHERE ndp = '$ndp'";
$result = $conn->query($sql);

if ($result === false) {
    echo "Query error: " . $conn->error;
    exit;
}

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // Verify password
    if ($password == $row['password']) { // This is plain text comparison
        $_SESSION['logged_in'] = true;
        $_SESSION['id'] = $row['id'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['ndp'] = $ndp;
        $_SESSION['jawatan'] = $row['jawatan'];
        $_SESSION['type'] = $row['type'];

        if ($row['type'] == 'admin' && $row['jawatan'] == 'cafe') {
            header("Location: admin_cafe_orders.php"); // Redirect admin with jawatan=cafe to cafe orders page
        } else if ($row['type'] == 'admin' && $row['jawatan'] == 'admin') {
            header("Location: admin_dashboard.php"); // Redirect admin to this page
        } else if ($row['type'] == 'student') {
            header("Location: home.php"); // Redirect non-admin users to this page
        } else {
            echo "<script>alert('Invalid user type.'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid login credentials.'); window.location.href = 'login.php';</script>";
    }
} else {
    echo "<script>alert('Invalid login credentials.'); window.location.href = 'login.php';</script>";
}
?>