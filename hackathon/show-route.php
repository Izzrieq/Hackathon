<?php
    include "db/connection.php";
    include "assets/header.php";

    // Get the selected location's details
    if (isset($_GET['id'])) {
        $locationId = $_GET['id'];
        $query = "SELECT names, link FROM places WHERE id = $locationId";
        $result = mysqli_query($conn, $query);
        $location = mysqli_fetch_assoc($result);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Route</title>
</head>
    <style>
        body {
            width: 80vh; /* Set the width as a percentage of the viewport width */
            height: 80vh; /* Set the height as a percentage of the viewport height */
            margin: auto; /* Center the content horizontally */
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
        }
        /* Media query for smaller screens */
        @media (max-width: 768px) {
            body{
                width: 50vh;
                height: 80vh;
            }
            iframe {
                width: 330px;
                height: 400px;
            }
        }
    </style>
<body>
    <h2 class="my-3"><?php echo $location['names']; ?></h2>
    <iframe
        width="520"
        height="300"
        frameborder="0"
        style="border:0"
        src="<?php echo $location['link']; ?>"
        allowfullscreen
    ></iframe>
    <button class="mt-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" onclick="history.back()">Go Back</button>
</body>
</html>
