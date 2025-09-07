<?php
session_start();
require 'db.php';

$errorMessage = "";

// These help toggle forms and prefill after signup
$prefilledUsername = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';
$showSignUp = isset($_GET['show_signup']) ? true : false;

// Handle LOGIN form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hashedPassword, $role);
        if ($stmt->fetch()) {
            if ($role !== 'user') {
                $errorMessage = "Access denied. Please use the correct login page.";
            } elseif (password_verify($password, $hashedPassword)) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                header("Location: user_home.php");
                exit();
            } else {
                $errorMessage = "Invalid username or password.";
            }
        } else {
            $errorMessage = "Invalid username or password.";
        }
        $stmt->close();
    } else {
        $errorMessage = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Visitor Login | Jail Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        .error {
            color: #ff4d4d;
            background: #ffe6e6;
            padding: 8px;
            border-radius: 10px;
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- LOGIN FORM -->
        <div class="form-container <?php echo $showSignUp ? '' : 'active'; ?>" id="loginForm">
            <h1>Sign In</h1>
            <p class="welcome-text">Welcome to Jail Management System</p>

            <form action="user_login.php" method="POST" autocomplete="off">
                <input type="hidden" name="login" value="1">

                <label>USERNAME</label>
                <div class="input-box">
                    <i class='bx bx-user'></i>
                    <input type="text" name="username" id="loginUsername" 
                           placeholder="Enter Username" required>
                </div>

                <label>PASSWORD</label>
                <div class="input-box">
                    <i class='bx bx-lock-alt'></i>
                    <input type="password" name="password" id="loginPassword" placeholder="Enter Password" required>
                </div>

                <?php if (!empty($errorMessage)): ?>
                <div class="error"><?php echo $errorMessage; ?></div>
                <?php endif; ?>

                <div class="terms-container">
                    <label>
                        <input type="checkbox" required>
                        By logging in, you accept our <a href="terms.php">Terms & Conditions</a>
                    </label>
                </div>

                <button type="submit" class="login-btn">Sign In</button>
            </form>

            <div class="forgot-password">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>

            <div class="signup">
                Don’t have an account? <a href="#" id="showSignup">Sign Up</a>
            </div>

            <div class="home">
                <a href="index.php" class="home-link"><i class="fa-solid fa-house"></i></a>
            </div>
        </div>

        <!-- SIGNUP FORM -->
        <div class="form-container <?php echo $showSignUp ? 'active' : ''; ?>" id="signupForm">
            <h2>Create Account</h2>
            <form action="signup.php" method="POST" autocomplete="off">

                <label>Full Name</label>
                <div class="input-box">
                    <i class='bx bx-user'></i>
                    <input type="text" name="fullname" placeholder="Enter Fullname" required>
                </div>

                <label>Username</label>
                <div class="input-box">
                    <i class='bx bx-user'></i>
                    <input type="text" name="username" placeholder="Enter Username" 
                           value="<?php echo $prefilledUsername; ?>" required>
                </div>

                <label>Email</label>
                <div class="input-box">
                    <i class='bx bx-envelope'></i>
                    <input type="email" name="email" placeholder="Enter Email" required>
                </div>

                <label>Password</label>
                <div class="input-box">
                    <i class='bx bx-lock-alt'></i>
                    <input type="password" name="password" id="signupPassword1" placeholder="Enter Password" required>
                </div>

                <label>Re-enter Password</label>
                <div class="input-box">
                    <i class='bx bx-lock-alt'></i>
                    <input type="password" name="password2" id="signupPassword2" placeholder="Re-enter Password" required>
                </div>

                <button type="submit" class="login-btn">Sign Up</button>
            </form>

            <div class="signup">
                Already have an account? <a href="#" id="showLogin">Sign In</a>
            </div>
        </div>

    </div>

    <script>
        const loginForm = document.getElementById("loginForm");
        const signupForm = document.getElementById("signupForm");
        const showSignupLink = document.getElementById("showSignup");
        const showLoginLink = document.getElementById("showLogin");

        if (showSignupLink) {
            showSignupLink.addEventListener("click", function(e) {
                e.preventDefault();
                loginForm.classList.remove("active");
                signupForm.classList.add("active");
            });
        }

        if (showLoginLink) {
            showLoginLink.addEventListener("click", function(e) {
                e.preventDefault();
                signupForm.classList.remove("active");
                loginForm.classList.add("active");
            });
        }

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
    </script>
</body>
</html>
