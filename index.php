<?php
    session_start();
    if (isset($_SESSION["username"])) {
        header("Location: dashboard.php"); 
        exit();
    }

    $prefilledUsername = isset($_GET['signup_username']) ? htmlspecialchars($_GET['signup_username']) : '';
    $showSignUp = isset($_GET['show_signup']) ? true : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome | Jail Management System</title>

    <!-- Import Poppins font with 400, 600, 900 weights -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap" rel="stylesheet" />

    <style>
        /* Blurred background container */
        #blurred-bg {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('Background.webp') no-repeat center center/cover;
            filter: blur(1px);
            opacity: 0.75; /* 75% opacity */
            z-index: -1; /* behind everything */
        }

        /* Overlay a semi-transparent gradient on top of blurred bg */
        #blurred-bg::after {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(rgba(0, 115, 200, 0.5), rgba(0, 115, 200, 0.5));
            pointer-events: none;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
            text-align: center;
            position: relative;
            background: none;
        }

        nav {
            background-color: rgba(0, 116, 204, 0.85);
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }

        nav ul {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        nav ul li:first-child {
            margin-right: auto;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 6px 12px;
            border-radius: 6px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        nav ul li a:hover {
            background-color: #005ea6;
        }

        nav ul li a img {
            height: 40px;
            width: auto;
            vertical-align: middle;
        }

        /* "WELCOME TO" heading */
        #welcome-text {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem; /* Bigger font size */
            margin: 10rem 0 0.3rem 0;
            text-shadow: 0 0 10px rgba(0,0,0,0.7);
            font-weight: 900; /* bold */
            position: relative;
            z-index: 1;
        }

        /* Typewriter effect container */
        #typing-title {
            font-family: 'Poppins', sans-serif;
            font-size: 5rem; /* Bigger font size */
            margin: 0 0 2rem 0;
            text-shadow: -2px 2px 2px rgba(0, 0, 0, 0.5);
            font-weight: 600; /* semi-bold */
            min-height: 4.5rem;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        /* Center buttons below titles */
        .btn-group {
            display: flex;
            gap: 2rem;
            justify-content: center;
            margin-top: 2rem; /* Space between typing effect and buttons */
            margin-bottom: 3rem;
            position: absolute; /* Position relative to the parent container */
            top: 60%; /* Position buttons below the typewriter text */
            left: 50%; /* Center horizontally */
            transform: translateX(-50%); /* Adjust for exact centering */
            z-index: 1; /* Ensure buttons are on top */
        }

        /* Style for both buttons */
        a.button {
            background-color: #fff;
            padding: 1rem 2.5rem; /* slightly wider for better click area */
            border-radius: 8px;
            color: black;
            font-weight: bold;
            text-decoration: none;
            font-size: 1.2rem;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); /* stronger drop shadow */
            transition: background-color 0.3s, box-shadow 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Hover effect with shadow */
        a.button:hover {
            background-color: #e9efe6;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }

    </style>
</head>
<body>

    <!-- Blurred background div -->
    <div id="blurred-bg"></div>

    <!-- Navigation bar -->
    <nav>
        <ul>
            <li>
                <a href="index.php">
                    <img src="PNP_logo (2).png" alt="PNP Logo" />
                </a>
            </li>
            <li><a href="index.php">Home</a></li>
            <li><a href="new_visit.php">New Visit</a></li>
            <li><a href="visit_status.php">Visit Status</a></li>
            <li><a href="about_us.php">About Us</a></li>
            <li><a href="news.php">News</a></li>
        </ul>
    </nav>

    <!-- Fixed "Welcome to" text -->
    <h1 id="welcome-text">WELCOME TO</h1>

    <!-- Typewriter effect for "Jail Management System" -->
    <h1 id="typing-title"></h1>

    <!-- Button Group (Center-Aligned Below Text) -->
    <div class="btn-group">
        <a href="user_login.php" class="button">USER</a>
        <a href="admin_login.php" class="button">ADMIN</a>
    </div>

    <script>
        const fullText = "Jail Management System";
        const typingTitle = document.getElementById("typing-title");

        let index = 0;
        let isDeleting = false;

        function type() {
            if (!isDeleting) {
                if (index < fullText.length) {
                    index++;
                    updateText();
                    setTimeout(type, 150);
                } else {
                    setTimeout(() => {
                        isDeleting = true;
                        setTimeout(type, 100);
                    }, 1500);
                }
            } else {
                if (index > 0) {
                    index--;
                    updateText();
                    setTimeout(type, 50);
                } else {
                    isDeleting = false;
                    setTimeout(type, 500);
                }
            }
        }

        function updateText() {
            typingTitle.textContent = fullText.substring(0, index);
        }

        window.onload = type;
    </script>

</body>
</html>
