<?php
include 'db.php';

$message = "";

if (isset($_GET["token"])) {
    $token = $_GET["token"];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $update = $conn->query("UPDATE users SET password='$newPassword', reset_token=NULL WHERE reset_token='$token'");

        if ($update) {
            $message = "<p class='success'> Password reset successfully. <a href='index.php'>Login</a></p>";
        } else {
            $message = "<p class='error'> Something went wrong. Please try again.</p>";
        }
    }
} else {
    $message = "<p class='error'> Invalid reset link.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(120deg, #2980b9, #6dd5fa);
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.15);
            width: 350px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #2980b9;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #1f6391;
        }
        .error {
            margin-top: 15px;
            color: red;
            font-size: 14px;
        }
        .success {
            margin-top: 15px;
            color: green;
            font-size: 14px;
        }
        a {
            color: #2980b9;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php if ($message) echo $message; ?>
        
        <?php if (isset($_GET["token"])): ?>
        <form method="POST">
            <input type="password" name="password" placeholder="Enter new password" required>
            <button type="submit">Reset Password</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
