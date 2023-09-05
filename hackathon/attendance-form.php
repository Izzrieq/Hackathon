<?php
include "db/connection.php";
include "delete_old_attendance.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is authenticated and has the required role (e.g., "mpp")
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['jawatan']) && $_SESSION['jawatan'] === 'mpp') {
    // User is authenticated and has the required role
    // Continue to display the form

    // Check if the form was successfully submitted and display a pop-up message if 'success' is set
    if (isset($_GET['success']) && $_GET['success'] === 'true') {
        echo '<script>showSuccessPopup();</script>';
    }
} else {
    // User is not authenticated or does not have the required role
    // Redirect them to a login page or display an error message
    echo "Access denied. Please login with the required role.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Data</title>
    <script>
        function sortTable(tableId, columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById(tableId);
            switching = true;

            while (switching) {
                switching = false;
                rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];

                    // Check if we are sorting by semester (columnIndex 1)
                    if (columnIndex === 1) {
                        x = parseInt(x.innerHTML);
                        y = parseInt(y.innerHTML);
                    } else {
                        x = x.innerHTML.toLowerCase();
                        y = y.innerHTML.toLowerCase();
                    }

                    if (x > y) {
                        shouldSwitch = true;
                        break;
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }

        function showSuccessPopup() {
            alert("Attendance data submitted successfully!");
        }
    </script>
</head>
        <style>
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

            h1,h2{
                font-size: 25px;
            }
          body, html {
            margin: 0;
            padding: 0;
            font-size: 15px; /* Increase the font size to make it bigger */
        }

        /* Style for normal screens (greater than 425px) */
        table {
            margin: 0;
            width: 100%; /* Set the table to 100% width on normal screens */
            max-width: 100%; /* Ensure it doesn't exceed the screen width */
            border-collapse: collapse;
        }

        .table-container {
            display: flex;
            justify-content: center;
        }
       
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .highlight-red {
            background-color: #FFDDDD; /* Light red background color */
        }
        @media (max-width: 1170px) {
                body, html {
                    margin: 0;
                    padding: 0;
                    font-size: 30px; /* Increase the font size to make it bigger */
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
    <h1>Student Data</h1>

    <?php

    date_default_timezone_set('Asia/Kuala_Lumpur');

    // Fetch data from the "users" table
    $sql = "SELECT nama, course, semester, level FROM users WHERE type = 'student'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Create an associative array to store data by course and semester
        $courseSemesterData = array();

        while ($row = $result->fetch_assoc()) {
            $nama = $row['nama'];
            $course = $row['course'];
            $level = $row['level'];
            $semester = $row['semester'];
        
            // Create a key for each course and semester combination
            $key = "$course Semester $semester ($level)";
        
            if (!isset($courseSemesterData[$key])) {
                $courseSemesterData[$key] = array();
            }
        
            // Initialize the attendance days counter for each student to 0
            $courseSemesterData[$key][$nama] = array(
                'nama' => $row['nama'],
                'semester' => $row['semester'],
                'attendance' => 0 // Initialize attendance counter
            );
        
            // You can add more code here to fetch attendance records from your database and update the counter
            // For example, fetch attendance records for each student within this course and semester and increment the counter
        }
        

        // Close the database connection
        $conn->close();

        echo "<form method='POST' action='insert_attendance.php'>";
        
        // Function to generate the HTML table
        function generateTable($courseSemester, $data) {
            echo "<h2>$courseSemester</h2>";
            echo "<table>";
            echo "<thead><tr>";
            echo "<th onclick='sortTable(\"$courseSemester\", 0)'>Student Name</th>";
            echo "<th onclick='sortTable(\"$courseSemester\", 1)'>Semester</th>";
            // Get the current date and display it in the header
            $currentDate = date("l, d M");
            echo "<th>$currentDate (M)</th>";
            echo "<th>$currentDate (I)</th>";
        
            echo "</tr></thead>";
            echo "<tbody>";
        
            foreach ($data as $nama => $studentData) {
                $highlightClass = ($studentData['attendance'] < 3) ? "highlight-red" : ""; // Apply red background if attendance is less than 3
        
                echo "<tr class='$highlightClass'>";
                echo "<td>" . $studentData['nama'] . "</td>";
                echo "<td>" . $studentData['semester'] . "</td>";
                // Add checkboxes for the current day and both M and I
                $dateKey = date("Y-m-d");
                echo "<td><input type='checkbox' name='attendance[$courseSemester][$nama][$dateKey][M]'> M</td>";
                echo "<td><input type='checkbox' name='attendance[$courseSemester][$nama][$dateKey][I]'> I</td>";
        
                echo "</tr>";
            }
        
            echo "</tbody></table>";
        }

        // Generate tables for each course and semester combination
        foreach ($courseSemesterData as $courseSemester => $data) {
            generateTable($courseSemester, $data);
        }

        echo "<input type='hidden' name='courseSemester' value='$courseSemester'>";
        echo "<input style='font-size: 35px; margin-top: 15px;' type='submit' name='submit' value='Submit Attendance'>";
        echo "<a href='home.php' class='back-button' style='font-size: 35px; margin-top: 15px; background-color: red; color: white; margin-left: 10px; text-decoration: none; padding: 10px; border-radius:10px;'>Back</a>";
        echo "</form>";
    } else {
        echo "No data found in the database.";
    }
    ?>
</body>
</html> 
