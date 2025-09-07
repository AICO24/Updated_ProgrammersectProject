<?php
session_start();
include "db.php";

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {

        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                $errorMessage = "Invalid username or password";
            }
        } else {
            $errorMessage = "Invalid username or password";
        }

        $stmt->close();
    } else {
        $errorMessage = "Please fill in all fields";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        <?php include 'style.css'; ?>
        .error {
            color: #ff4d4d;
            background-color: #ffe6e6;
            padding: 8px;
            border-radius: 10px;
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-shadow: inset 2px 2px 4px #d1d1d1, inset -2px -2px 4px #ffffff;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container active" id="loginForm">
        <h1>LOGIN</h1>
        <p class="welcome-text">Welcome to Jail Management System</p>

        <form action="login.php" method="POST" autocomplete="off">
            <label>USERNAME</label>
            <div class="input-box">
                <i class='bx bx-user'></i>
                <input type="text" name="username" id="loginUsername" 
                       placeholder="Enter Username" 
                       value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" 
                       required>
            </div>

            <label>PASSWORD</label>
            <div class="input-box">
                <i class='bx bx-lock-alt'></i>
                <input type="password" name="password" id="loginPassword" placeholder="Enter Password" required>
                <i class="fa-regular fa-eye toggle-password" onclick="togglePassword('loginPassword', this)"></i>
            </div>

            <div class="error" style="min-height:20px;">
                <?php echo !empty($errorMessage) ? $errorMessage : ''; ?>
            </div>

            <div class="forgot-password">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">LOGIN</button>
        </form>

        <div class="signup">
            Don't have an account? 
            <a href="signup.php<?php echo isset($username) ? '?username=' . urlencode($username) : ''; ?>">Sign Up</a>
        </div>
    </div>
</div>

    <script>
        const loginForm = document.getElementById("loginForm");
        const showLoginLink = document.getElementById("showLogin");

        showSignupLink.addEventListener("click", function(e) {
            e.preventDefault();
            loginForm.classList.remove("active");
            signupForm.classList.add("active");
        });

        showLoginLink.addEventListener("click", function(e) {
            e.preventDefault();
            signupForm.classList.remove("active");
            loginForm.classList.add("active");
        });
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        <?php if ($showSignUp): ?>
            loginForm.classList.remove("active");
            signupForm.classList.add("active");
        <?php endif; ?>
</body>
</html>