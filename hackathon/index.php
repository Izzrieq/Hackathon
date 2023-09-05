<?php
    include "db/connection.php";
    include "assets/header.php";

$query = "SELECT id, img, names, link FROM places";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ILPKLS</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"> -->

        <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Create a flex container for navigation and memo */
        .container {
            display: flex;
            justify-content: space-between; /* Space between navigation and memo */
            gap: 10px;
            margin-top: 20px;
        }

        /* Style for the navigation section */
        .navigation {
            flex: 1; /* Allow navigation to grow and take available space */
            background-color: #f0f0f0;
            border: 1px solid #ccc;
        }

        .location-card {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: center;
            margin-bottom: 10px;
            justify-content:  center;
        }

        /* Style for the memo sidebar */
        .sidebar {
            flex: 1; /* Allow sidebar to grow and take available space */
            background-color: #f0f0f0;
            padding: 10px;
            border: 1px solid #ccc;
        }
         /* Media query for smaller screens */
         @media (max-width: 768px) {
            img {
                width: full;
                height: auto;
            }

            .navigation, .sidebar {
                width: 100%; /* Full width for both sections on smaller screens */
            }
        }
    </style>
</head>

<body class="bg-slate-300">
    <h1 class="text-center text-black text-2xl my-3">Welcome New Student!</h1>
    <a href="login.php" class="ml-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">Login</a>

    <!-- Parent container for navigation and memo -->
    <div class="container">
        <!-- Navigation Section -->
        <div class="navigation">
    <h2 class="text-center text-black
     text-xl">Navigation</h2>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="location-card">
            <h3><?php echo $row['names']; ?></h3>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['img']); ?>"
                alt="<?php echo $row['names']; ?>" height="auto" width="full">
            <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="show-route.php?id=<?php echo $row['id']; ?>">Get Location</a>
        
        </div>
    <?php } ?>
    </div> 

        <!-- Memo Sidebar -->
        <div class="sidebar">
            <h2 class="text-center text-black text-xl">Memo Student Baharu</h2>
            <?php
                $query = "SELECT memo_date, memo_title, memo_description FROM memos";
                $result = mysqli_query($conn, $query);
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $memoDate = $row['memo_date'];
                    $memoTitle = $row['memo_title'];
                    $memoDescription = $row['memo_description'];
                
                    // Display memo data as needed
                    echo "<strong>Date:</strong> $memoDate<br>";
                    echo "<strong>Title:</strong> $memoTitle<br>";
                    echo "<strong>Description:</strong> $memoDescription<br><br>";
                }
            ?>
        </div>
    </div>
</body>
</html>
