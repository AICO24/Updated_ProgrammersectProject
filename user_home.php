<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: user_login.php");
    exit();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Home | Jail Management System</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        /* Basic horizontal navbar styles */
        nav {
            background-color: #0074cc;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }
        nav ul {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
        }
        nav ul li {
            flex: 1;
        }
        nav ul li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        nav ul li a:hover {
            background-color: #005ea6;
        }
        /* Optional: active link style */
        nav ul li a.active {
            background-color: #004080;
        }
        /* Container padding */
        .container {
            padding: 20px;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>

    <nav>
        <ul>
            <li><a href="user_home.php" class="active">Home</a></li>
            <li><a href="new_visit.php">New Visit</a></li>
            <li><a href="visit_status.php">Visit Status</a></li>
            <li><a href="about_us.php">About Us</a></li>
            <li><a href="news.php">News</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($username) ?></h1>
        <p>This is the user homepage.</p>
        <p><a href="logout.php">Logout</a></p>
    </div>

</body>
</html>
