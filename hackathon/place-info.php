<?php
    include "db/connection.php";

   // ... Your database connection code ...

$query = "SELECT id, img, names, link FROM places";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location App</title>
</head>
<body>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="location-card">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['img']); ?>" alt="<?php echo $row['names']; ?>">
            <h3><?php echo $row['names']; ?></h3>
            <a href="show-route.php?id=<?php echo $row['id']; ?>">Get Location</a>
        </div>
    <?php } ?>
</body>
</html>

