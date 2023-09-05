<?php
    include "db/connection.php";

    // Initialize a variable to track the success status
    $success = false;

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $memo_date = $_POST["memo_date"];
        $memo_title = $_POST["memo_title"];
        $memo_description = $_POST["memo_description"];

        // Perform validation if needed (e.g., check for empty fields)

        // Insert memo data into the database
        $insert_query = "INSERT INTO memos (memo_date, memo_title, memo_description) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $memo_date, $memo_title, $memo_description);

            if (mysqli_stmt_execute($stmt)) {
                // Memo added successfully
                $success = true;
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
            } else {
                // Error occurred while adding memo
                $error_message = "Error: " . mysqli_error($conn);
            }
        } else {
            // Error preparing the statement
            $error_message = "Error: " . mysqli_error($conn);
        }
    } else {
        // The form was not submitted
        header("Location: admin_dashboard.php");
        exit();
    }

    // Redirect to admin_dashboard.php with appropriate alert
    if ($success) {
        // Success alert and redirect
        header("Location: admin_dashboard.php?success=1");
    } else {
        // Error alert and redirect
        header("Location: admin_dashboard.php?success=0&error=" . urlencode($error_message));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include your meta tags, title, and CSS links here -->
</head>
<body>
    <!-- Your HTML content goes here -->

    <!-- JavaScript to show an alert if the memo was successfully added -->
    <?php if ($success) { ?>
        <script>
            alert("Memo added successfully!");
        </script>
    <?php } else { ?>
        <!-- JavaScript to show an error alert if there was an error -->
        <script>
            alert("Error adding memo: <?php echo htmlspecialchars($error_message); ?>");
        </script>
    <?php } ?>
</body>
</html>
