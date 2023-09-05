<?php 
date_default_timezone_set('Asia/Kuala_Lumpur');
include "assets/header.php";
include "db/connection.php";


// Wajib ada setiap page
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
    exit;
}

$currentHour = date('H'); // Get the current hour in 24-hour format


// Define the opening and closing hours for ordering
$openingHour = 3; // 7:00 PM
$closingHour = 24; // 8:00 PM

// Check if the current time is within the ordering hours
$isOrderCafeOpen = ($currentHour >= $openingHour && $currentHour < $closingHour);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
    .sidebar {
        flex: 1;
        /* Allow sidebar to grow and take available space */
        background-color: #f0f0f0;
        padding: 20px;
        border: 1px solid #ccc;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background-color: #333; /* Change this to your header background color */
    }

    a.logout-button {
        text-decoration: none;
        background-color: transparent;
        color: white;
        padding: 5px 10px;
        border: 1px solid blue;
        transition: background-color 0.3s;
        border-radius: 5px;
    }
    a.logout-button:hover {
        background-color: blue;
        color: white; /* Change text color on hover */
        border-radius: 5px;
    }
</style>

<body class="bg-slate-200">
<div class="header">
        <h2 class="mb-0 text-white text-2xl">WELCOME, <?php echo strtoupper($_SESSION['nama']); ?>!<br>
            <span class="text-secondary"><?php echo ($_SESSION['jawatan'])?></span>
        </h2>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>

    <?php
                 $query = "SELECT memo_date, memo_title, memo_description FROM memos ORDER BY memo_date DESC LIMIT 2";
                 $result = mysqli_query($conn, $query);
             
                 if (mysqli_num_rows($result) > 0) {
                     echo '<div class="sidebar">';
                     echo '<h2 class="text-center text-black text-xl">Memo Student Baharu</h2>';
             
                     while ($row = mysqli_fetch_assoc($result)) {
                         $memoDate = $row['memo_date'];
                         $memoTitle = $row['memo_title'];
                         $memoDescription = $row['memo_description'];
             
                         // Display memo data as needed
                         echo "<strong>Date:</strong> $memoDate<br>";
                         echo "<strong>Title:</strong> $memoTitle<br>";
                         echo "<strong>Description:</strong> $memoDescription<br><br>";
                     }
             
                     echo '</div>';
                 } else {
                     echo '<p>No memos available.</p>';
                 }
            ?>

<?php if ($isOrderCafeOpen) { ?>
    <a href="order_cafe.php" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded md:inline-block block mb-2 md:mb-0">Order Cafe</a>
<?php } else { ?>
    <p class="mb-4 md:hidden">Order Cafe is currently closed. It will be open from 7:00 PM to 8:00 PM.</p>
<?php } ?>

<?php if ($_SESSION['jawatan'] === 'mpp') { ?>
    <a href="attendance-form.php" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded md:inline-block block mb-2 md:mb-0">Attendance Surau</a>
<?php } ?>

<a href="view_attendance.php" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded md:inline-block block mb-2 md:mb-0">View Attendance Surau</a>

</body>

</html>