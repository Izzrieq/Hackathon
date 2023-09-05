<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
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

        @keyframes typing {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
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
        <h1 class="mt-3 m-3 text-primary">
            <span class="typing-text">ILPKLS</span>
        </h1>
    </header>
</body>
</html>
