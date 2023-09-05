<?php
    include "db/connection.php";
    include "assets/header.php";

    session_start();
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<style>
     /* Body Styles */
     body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Header Styles */
        .header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        /* Container Styles */
        .container-fluid {
            padding: 20px;
        }

        /* Card Styles */
        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Form Styles */
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        /* Heading Styles */
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        a.logout-button {
        text-decoration: none;
        background-color: transparent;
        color: black;
        padding: 5px 10px;
        border: 1px solid blue;
        transition: background-color 0.3s;
    }
    a.logout-button:hover {
        background-color: blue;
        color: white; /* Change text color on hover */
    }
</style>

<body>
    <h2 class="px-6 mb-0 mt-2 text-primary text-2xl">WELCOME, <?php echo strtoupper($_SESSION['nama']); ?>!<br>
        <h5 class="px-7 text-secondary"><?php echo ($_SESSION['jawatan'])?></h5>
    </h2>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-slate-200  d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-globe fa-3x text-black"></i>
                    <div class="ms-3">
                        <p class="mb-2 text-black"><b>Total Pelajar</b> </p>

                        <?php
                                    $dash_totallcid_query = "SELECT * FROM users WHERE jawatan = 'pelajar'";
                                    $dash_totallcid_query_run = mysqli_query($conn, $dash_totallcid_query);

                                    if($category_total = mysqli_num_rows($dash_totallcid_query_run))
                                    {
                                        echo '<h6 class="mb-0 text-black">  '.$category_total.'</h6>';
                                    }else{
                                        echo '<h6 class="mb-0">No Data...</h6>';
                                    }
                                
                                ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body bg-slate-300 text-black p-3">
                <h3 class="card-title" style="font-size: 30px;">Add Memo</h3>
                <form action="add_memo.php" method="POST">
                    <div class="mb-3">
                        <label for="memo_date" class="form-label">Memo Date</label>
                        <input type="date" class="form-control text-black" id="memo_date" name="memo_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="memo_title" class="form-label">Memo Title</label>
                        <input type="text" class="form-control text-black" id="memo_title" name="memo_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="memo_description" class="form-label">Memo Description</label>
                        <textarea class="form-control text-black" id="memo_description" name="memo_description" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Memo</button>
                </form>
            </div>
        </div>
    </div>
    <h2>Senarai Pelajar Tidak Hadir ke Surau</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th style="border: 1px solid #000;">ID</th>
            <th style="border: 1px solid #000;">Name</th>
            <th style="border: 1px solid #000;">Course</th>
            <th style="border: 1px solid #000;">Semester</th>
            <th style="border: 1px solid #000;">Level</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // Fetch all users of type "student" who are not in the "attendance_record" table
        $query = "SELECT users.nama, users.course, users.semester, users.level
        FROM users
        WHERE users.jawatan IN ('pelajar', 'mpp')
        AND nama NOT IN (SELECT nama FROM attendance_records)
        ORDER BY users.semester, users.level";
        $result = mysqli_query($conn, $query);
        $bil = 1;
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
        while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td style="border: 1px solid #000;"><?php echo $bil; ?></td>
            <td style="border: 1px solid #000;"><?php echo $row['nama']; ?></td>
            <td style="border: 1px solid #000;"><?php echo $row['course']; ?></td>
            <td style="border: 1px solid #000;"><?php echo $row['semester']; ?></td>
            <td style="border: 1px solid #000;"><?php echo $row['level']; ?></td>
        </tr>
        <?php 
        $bil++;
    } ?>
    </tbody>
</table>
<a href="logout.php" class="logout-button">Logout</a>
    
</body>

</html>