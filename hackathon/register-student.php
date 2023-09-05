<?php
include "db/connection.php";

// Define variables to store user input
$nama = $ndp  = $password = $type = $jawatan = $course = $level = $semester = "";
$namaErr = $ndpErr = $passwordErr = $typeErr = $jawatanErr = $courseErr = $levelErr = $semesterErr = "";

$registrationSuccess = false; // Flag to check if registration is successful

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user inputs
    $nama = htmlspecialchars($_POST["nama"]);
    $ndp = htmlspecialchars($_POST["ndp"]);
    $password = htmlspecialchars($_POST["password"]);
    $type = htmlspecialchars($_POST["type"]);
    $jawatan = htmlspecialchars($_POST["jawatan"]);
    $course = htmlspecialchars($_POST["course"]);
    $level = htmlspecialchars($_POST["level"]);
    $semester = htmlspecialchars($_POST["semester"]);

    // Perform validation (you can add more validation rules as needed)
    if (empty($nama)) {
        $namaErr = "Name is required.";
    }

    if (empty($ndp)) {
        $ndpErr = "ndp is required.";
    }

    if (empty($password)) {
        $passwordErr = "Password is required.";
    }

    if (empty($type)) {
        $typeErr = "User type is required.";
    }

    if (empty($jawatan)) {
        $jawatanErr = "Jawatan is required.";
    }

    if (empty($course)) {
        $courseErr = "Course is required.";
    }

    if (empty($level)) {
        $levelErr = "Level is required.";
    }

    if (empty($semester)) {
        $semesterErr = "Semester is required.";
    }

    // If there are no errors, insert data into the database
    if (empty($namaErr) && empty($ndpErr)  && empty($passwordErr) && empty($typeErr) && empty($jawatanErr) && empty($courseErr) && empty($levelErr) && empty($semesterErr)) {
        // Prepare and execute the SQL query to insert data (without password hashing)
        $sql = "INSERT INTO users (nama, ndp, password, type, jawatan, course, level, semester)
                VALUES ('$nama', '$ndp', '$password', '$type', '$jawatan', '$course', '$level', '$semester')";

        if ($conn->query($sql) === TRUE) {
            // Registration successful, set the flag to true
            $registrationSuccess = true;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
</head>
<style>
     body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            padding: 20px 0;
            background-color: #007bff;
            color: #fff;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[readonly] {
            background-color: #eee;
        }

        span.error {
            color: red;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        a {
            margin-left: 5px;
            text-decoration: none;
            color: #007bff;
        }
        @media screen and (min-width: 1170px) {
            form {
                max-width: 600px;
            }

            input[type="text"],
            input[type="password"],
            select {
                width: 100%;
                padding: 15px;
                font-size: 18px;
            }

            input[type="submit"] {
                padding: 15px 30px;
                font-size: 18px;
            }
        }
</style>

<body>
    <h1>Student Registration</h1>
    <?php if ($registrationSuccess): ?>
    <!-- Display an alert and redirect to login.php using JavaScript -->
    <script>
        alert("Registration successful! You can now log in.");
        window.location.href = "login.php";
    </script>
    <?php endif; ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nama">Name:</label>
        <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>">
        <span class="error"><?php echo $namaErr; ?></span><br>

        <label for="ndp">NDP:</label>
        <input type="text" id="ndp" name="ndp">
        <span class="error"><?php echo $ndpErr; ?></span><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <span class="error"><?php echo $passwordErr; ?></span><br>

        <label for="type">User Type:</label>
        <input type="text" id="type" name="type" value="student" readonly>
        <span class="error"><?php echo $typeErr; ?></span><br>

        <label for="jawatan">Jawatan:</label>
        <input type="text" id="jawatan" name="jawatan" value="pelajar" readonly>
        <span class="error"><?php echo $jawatanErr; ?></span><br>

        <label for="course">Course:</label>
        <select id="course" name="course">
            <option value="TPP">TPP</option>
            <option value="TKR">TKR</option>
            <option value="CADD">CADD</option>
            <option value="TPM">TPM</option>
            <!-- Add more course options as needed -->
        </select>
        <span class="error"><?php echo $courseErr; ?></span><br>

        <label for="level">Level:</label>
        <select id="level" name="level">
            <option value="SIJIL">SIJIL</option>
            <option value="DIPLOMA">DIPLOMA</option>
        </select>
        <span class="error"><?php echo $levelErr; ?></span><br>

        <label for="semester">Semester:</label>
        <select id="semester" name="semester">
            <!-- Semester options will be populated dynamically using JavaScript -->
        </select>
        <span class="error"><?php echo $semesterErr; ?></span><br>

        <input type="submit" name="register" value="Register">

        <a href="index.php">Go Back</a>
    </form>
   

    <script>
        // Get references to the level and semester select elements
        const levelSelect = document.getElementById("level");
        const semesterSelect = document.getElementById("semester");

        // Define the semester options for SIJIL and DIPLOMA
        const sijilSemesters = ["1", "2", "3", "4"];
        const diplomaSemesters = ["4", "5", "6"];

        // Function to update the semester options based on the selected level
        function updateSemesterOptions() {
            // Get the selected level
            const selectedLevel = levelSelect.value;

            // Clear existing options
            semesterSelect.innerHTML = "";

            // Populate the semester options based on the selected level
            const semesterOptions = selectedLevel === "SIJIL" ? sijilSemesters : diplomaSemesters;
            semesterOptions.forEach((semester) => {
                const option = document.createElement("option");
                option.value = semester;
                option.textContent = semester;
                semesterSelect.appendChild(option);
            });
        }

        // Add an event listener to the level select element
        levelSelect.addEventListener("change", updateSemesterOptions);

        // Initialize the semester options based on the default selected level
        updateSemesterOptions();
    </script>
</body>

</html>