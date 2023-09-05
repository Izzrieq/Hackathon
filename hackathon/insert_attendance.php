<?php
// Include your database connection here
include "db/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form was submitted

    // Retrieve the course and semester information from the hidden input
    $courseSemester = $_POST["courseSemester"];

    // Retrieve the attendance data from the form
    $attendanceData = $_POST["attendance"];

    $success = true; // Variable to track insertion success

    // Iterate through the attendance data and insert records into the database
    foreach ($attendanceData as $courseSemesterKey => $students) {
        // $courseSemesterKey will contain the course and semester information

        foreach ($students as $studentName => $attendanceDates) {
            // $studentName will contain the student's name
            foreach ($attendanceDates as $date => $attendanceTypes) {
                // $date will contain the date, and $attendanceTypes will contain 'M' or 'I'
                foreach ($attendanceTypes as $attendanceType => $value) {
                    // Check if the attendance type is empty, and if so, set it to 'A' (absent)
                    if (empty($attendanceType)) {
                        $attendanceType = 'A'; // Set it as absent
                    }

                    // Insert each attendance record into the database
                    $sql = "INSERT INTO attendance_records (id, course, semester, nama, date, attendance_type)
                            VALUES (NULL, '$courseSemesterKey', '$courseSemester', '$studentName', '$date', '$attendanceType')";
                    if ($conn->query($sql) !== TRUE) {
                        // Handle the case where the insertion fails
                        $success = false;
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            }
        }
    }

    // Close the database connection
    $conn->close();

    if ($success) {
        // Insertion was successful, display a success message using JavaScript
        echo "<script>alert('Attendance data has been successfully inserted!');</script>";
    }

    // Redirect back to the attendance page or any other desired location
    header("Location: attendance-form.php");
    exit();
} else {
    // If the script is accessed directly without a POST request, handle it accordingly
    echo "<script>alert('Data not success!');</script>";
}
?>
