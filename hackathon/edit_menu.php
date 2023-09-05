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
    // Check if an item ID is provided in the URL
    if (isset($_GET['id'])) {
        $item_id = $_GET['id'];

        // Query to retrieve the menu item based on the provided item ID
        $sql = "SELECT * FROM cafe_menu WHERE id = $item_id";
        $result = $conn->query($sql);

        if ($result === false) {
            echo "Query error: " . $conn->error;
            exit;
        }

        // Check if the query returned any rows
        if ($result->num_rows == 1) {
            $menuItem = $result->fetch_assoc();
        } else {
            echo "Menu item not found.";
            exit;
        }
    } else {
        echo "Item ID not provided.";
        exit;
    }

    // Check if the form was submitted for updating the menu item
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_menu'])) {
        // Retrieve form data
        $newItemName = $_POST['new_item_name'];
        $newDescription = $_POST['new_description'];
        $newPrice = $_POST['new_price'];
        $newAvailability = isset($_POST['new_availability']) ? 1 : 0; // Checkbox value

        // Check if a new image file was uploaded
        if ($_FILES['new_image']['error'] == 0) {
            $imageFileName = $_FILES['new_image']['name'];
            $imageFileType = pathinfo($imageFileName, PATHINFO_EXTENSION);

            // Define the target directory and file path
            $targetDirectory = "menu_images/";
            $targetFilePath = $targetDirectory . "menu_" . $item_id . "." . $imageFileType;

            // Move the uploaded file to the target location
            if (move_uploaded_file($_FILES['new_image']['tmp_name'], $targetFilePath)) {
                // Update the menu item in the database with the new image path
                $updateSql = "UPDATE cafe_menu SET
                    item_name = '$newItemName',
                    description = '$newDescription',
                    price = $newPrice,
                    is_available = $newAvailability,
                    image = '$targetFilePath'
                    WHERE id = $item_id";

                if ($conn->query($updateSql) === TRUE) {
                    echo "<script>alert('Menu item updated successfully!');</script>";
                    // Redirect to view menu page after updating
                    echo "<script>window.location.href = 'view_menu.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Error updating the menu item: " . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('Error uploading the image file.');</script>";
            }
        } else {
            // Update the menu item in the database without changing the image
            $updateSql = "UPDATE cafe_menu SET
                item_name = '$newItemName',
                description = '$newDescription',
                price = $newPrice,
                is_available = $newAvailability
                WHERE id = $item_id";

            if ($conn->query($updateSql) === TRUE) {
                echo "<script>alert('Menu item updated successfully!');</script>";
                // Redirect to view menu page after updating
                echo "<script>window.location.href = 'view_menu.php';</script>";
                exit;
            } else {
                echo "<script>alert('Error updating the menu item: " . $conn->error . "');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item</title>
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
            margin-top: 20px;
            font-size: 32px;
        }

        .container {
            max-width: 600px;
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
        input[type="number"],
        textarea,
        input[type="checkbox"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="checkbox"] {
            width: auto;
        }

        input[type="submit"] {
            background-color: green;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 18px;
            text-decoration: none;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 1170px) {
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
</head>
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
        <h2 class="mt-3 m-3 text-primary">
            <span class="typing-text">ILPKLS</span>
        </h2>
    </header>
    <h1>Edit Menu Item</h1>
    <div class="container">
        <form method="POST" action="edit_menu.php?id=<?php echo $item_id; ?>" enctype="multipart/form-data">
            <label for="new_item_name">Item Name:</label>
            <input type="text" id="new_item_name" name="new_item_name" value="<?php echo $menuItem['item_name']; ?>"><br>

            <label for="new_description">Description:</label>
            <textarea id="new_description" name="new_description"><?php echo $menuItem['description']; ?></textarea><br>

            <label for="new_price">Price (RM):</label>
            <input type="number" id="new_price" name="new_price" step="0.01" value="<?php echo $menuItem['price']; ?>"><br>

            <label for="new_availability">Available:</label>
            <input type="checkbox" id="new_availability" name="new_availability" <?php if ($menuItem['is_available']) echo "checked"; ?>><br>

            <label for="new_image">New Image:</label>
            <input type="file" id="new_image" name="new_image"><br>

            <input type="submit" name="update_menu" value="Update Menu Item">
        </form>
        <a class="back-link" href="view_menu.php">Back to View Menu</a>
    </div>
</body>
</html>

<?php
} else {
    echo "Access denied. You do not have permission to view this page.";
}
?>
