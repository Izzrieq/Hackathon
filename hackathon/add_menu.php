<?php
include "db/connection.php";

// Wajib ada setiap page
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
    exit;
}

// Check if the user is an admin with a cafe jawatan
if ($_SESSION['type'] === 'admin' && $_SESSION['jawatan'] === 'cafe') {
    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        // Retrieve and sanitize form inputs
        $itemName = mysqli_real_escape_string($conn, $_POST['item_name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price']); // Convert to float
        $imageName = '';

        // Check if an image file was uploaded
        if ($_FILES['image']['error'] === 0) {
            // Define the directory where the image will be saved
            $uploadDir = 'menu_images/';

            // Generate a unique file name for the image
            $imageFileName = uniqid() . '_' . $_FILES['image']['name'];

            // Define the full path to save the image
            $imagePath = $uploadDir . $imageFileName;

            // Move the uploaded image to the specified directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $imageName = $imageFileName;
            } else {
                echo "<script>alert('Failed to upload image.'); window.location.href = 'add_menu.php';</script>";
                exit;
            }
        }

        // Insert the menu item into the database
        $sql = "INSERT INTO cafe_menu (item_name, description, price, image) VALUES ('$itemName', '$description', $price, '$imageName')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Menu item added successfully!'); window.location.href = 'add_menu.php';</script>";
        } else {
            echo "Error adding menu item: " . $conn->error;
        }
    }
} else {
    echo "Access denied. You do not have permission to view this page.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item</title>
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
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
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="file"] {
            width: 100%;
            margin-top: 5px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
        }
        @media (max-width: 1170px) {
            form {
                max-width: 100%;
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
        <h4 class="mt-3 m-3 text-primary">
            <span class="typing-text">ILPKLS</span>
        </h4>
    </header>
    <h1>Add Menu Item</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" cols="50"></textarea><br>

        <label for="price">Price (RM):</label>
        <input type="number" id="price" name="price" step="0.01" min="0.01" required><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image"><br>

        <input type="submit" name="submit" value="Add Item">
    </form>
    <a href="admin_cafe_orders.php">Back to Cafe Orders</a>
</body>
</html>
