<?php
session_start();
include "db/connection.php";
include "assets/header.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['type'] !== 'student') {
    // Redirect to login page if not logged in or not a student
    header("Location: login.php");
    exit();
}

// Retrieve student's name from the session
$studentName = $_SESSION['nama'];

// Retrieve student's attendance data from the database using their name
$sql = "SELECT * FROM attendance_records WHERE nama = '$studentName'";
$result = $conn->query($sql);

if ($result === false) {
    echo "Query error: " . $conn->error;
    exit;
}

// Calculate total attendance count
$totalAttendance = $result->num_rows;

// Check if attendance is below 3 and set header accordingly
if ($totalAttendance < 3) {
    $headerText = "No Surau, No Outing";

    // Retrieve data from the places table when attendance is below 3
    $sqlPlaces = "SELECT * FROM places WHERE NAMES = 'SURAU'";
    $resultPlaces = $conn->query($sqlPlaces);

    if ($resultPlaces === false) {
        echo "Query error: " . $conn->error;
        exit;
    }

    // Check if data is available in the places table
    if ($resultPlaces->num_rows > 0) {
        // Fetch the data
        $rowPlaces = $resultPlaces->fetch_assoc();
        // Display the iframe link
        $iframeLink = $rowPlaces['LINK'];
    }
} else {
    $headerText = "Your Attendance";
    // Set the iframe link to an empty string if attendance is 3 or more
    $iframeLink = "";
}

// Retrieve the image data from the database
$sqlImage = "SELECT IMG FROM places WHERE NAMES = 'SURAU'"; // Change 1 to the desired image ID
$resultImage = $conn->query($sqlImage);

if ($resultImage === false) {
    echo "Query error: " . $conn->error;
    exit;
}

// Check if the query returned any rows
if ($resultImage->num_rows > 0) {
    // Fetch the image data
    $rowImage = $resultImage->fetch_assoc();
    // Set the image data
    $imageData = $rowImage['IMG'];
    // Convert and display the image data
    $imageDataEncoded = base64_encode($imageData);
    $imageMimeType = 'image/jpeg'; // Change to the appropriate image MIME type
    $imageDataUri = "data:$imageMimeType;base64,$imageDataEncoded";
} else {
    $imageDataUri = ''; // Set to an empty string if no image data is found
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Attendance</title>
    <style>
        .highlight-red {
            background: lightcoral;
            color: white;
        }

        .highlight-green {
            background: lightgreen;
            color: white;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 3px;
            text-align: left;
        }
        a.logout-button {
        text-decoration: none;
        background-color: transparent;
        color: black;
        padding: 5px 10px;
        border: 1px solid blue;
        transition: background-color 0.3s;
    }

    /* Change background color to blue on hover */
    a.logout-button:hover {
        background-color: blue;
        color: white; /* Change text color on hover */
    }

    /* Back button styles */
    a.back-button {
        text-decoration: none;
        background-color: transparent;
        color: black;
        padding: 5px 10px;
        border: 1px solid red;
        transition: background-color 0.3s;
    }

    /* Change background color to red on hover */
    a.back-button:hover {
        background-color: red;
        color: white; /* Change text color on hover */
    }
        @media (max-width: 1170px) {
        /* Media query for screens with a maximum width of 768px */
        img.center {
            display: block;
            margin: 0 auto; /* Center the image horizontally */


        }
    }
    </style>
</head>

<body>
    <h1
        <?php if ($totalAttendance < 3) echo 'class="highlight-red"'; elseif ($totalAttendance >= 3) echo 'class="highlight-green"'; ?>>
        <?php echo $headerText; ?></h1>

    <table style="margin-bottom: 15px;">
        <tbody>
            <?php
            // Display attendance data in a table
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo    "<th>Date</th>";
                echo    "<th>Attendance Type</th>";
                echo  "</tr>";
                echo "<tr>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . $row['attendance_type'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Display the iframe link if it's available
    if (!empty($iframeLink)) {
        echo "<h2>Surau Location just for you</h2>";
        echo "<iframe src='$iframeLink' width='400px' height='400px' frameborder='0'></iframe>";
        echo "<h2>Sila rujuk gambar dibawah jika anda masih sesat.</h2>";
        echo "<div style='text-align: center; margin-bottom: 15px;'>"; // Center-align the image
        echo "<img src='$imageDataUri' alt='Image' style='width: 200px; height: 200px; border: 2px solid #000; border-radius: 20%;' class='center'>";
        echo "</div>";
    }
    ?>
    <a href="logout.php" class="logout-button">Logout</a>
    <a href="home.php" class="back-button">Back</a>

</body>

</html>