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
    // Check if the form for updating availability is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateAvailability'])) {
        $itemId = $_POST['item_id'];
        $newAvailability = $_POST['availability'];

        // Update the availability status in the database
        $updateSql = "UPDATE cafe_menu SET is_available = '$newAvailability' WHERE id = $itemId";
        if ($conn->query($updateSql) === TRUE) {
            echo "<script>alert('Availability updated successfully.');</script>";
        } else {
            echo "Error updating availability: " . $conn->error;
        }
    }

    // Query to retrieve all menu items
    $sql = "SELECT * FROM cafe_menu";
    $result = $conn->query($sql);

    if ($result === false) {
        echo "Query error: " . $conn->error;
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Menu</title>
</head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            margin-bottom: 50px;
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

        table {
            width: 100%;
            max-width: 1170px;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        img {
            max-width: 100px;
            height: auto;
        }

        select {
            padding: 6px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
        }
        /* Media query for screens with a maximum width of 1170px */
        @media (max-width: 1170px) {
            table {
                font-size: 14px; /* Decrease font size for smaller screens */
                padding: 5px;
            }
            th{
                padding: 10px; 
            }
            td{
                padding: 5px;
                font-size: 10px; 
            }
            .navbar {
            width: 600px;
            }
            h1 {
            width: 600px;
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
        <h2 class="mt-3 m-3 text-primary">
            <span class="typing-text">ILPKLS</span>
        </h2>
    </header>
    <h1>View Menu</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Price (RM)</th>
                <th>Image</th>
                <th>Availability</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>RM <?php echo number_format($row['price'], 2); ?></td>
                    <td><img src="menu_images/<?php echo $row['image']; ?>" alt="<?php echo $row['item_name']; ?>" width="100"></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                            <select name="availability">
                                <option value="1" <?php if ($row['is_available'] == 1) echo 'selected'; ?>>Available</option>
                                <option value="0" <?php if ($row['is_available'] == 0) echo 'selected'; ?>>Not Available</option>
                            </select>
                            <button type="submit" name="updateAvailability">Update</button>
                        </form>
                    </td>
                    <td><a href="edit_menu.php?id=<?php echo $row['id']; ?>">Edit</a></td>
                    <td><a href="delete_menu.php?id=<?php echo $row['id']; ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="admin_cafe_orders.php">Back to Cafe Orders</a>
</body>
</html>
<?php
} else {
    echo "Access denied. You do not have permission to view this page.";
}
?>
