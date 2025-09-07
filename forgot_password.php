<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $conn->query("UPDATE users SET reset_token='$token' WHERE email='$email'");

        $resetLink = "http://localhost/ProgrammerSect/reset_password.php?token=$token";

        // 📩 Setup PHPMailer
        $mail = new PHPMailer(true);

        try {
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mjgochaico24@gmail.com';
            $mail->Password   = 'furf iheu lhei pulv';   
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Sender and recipient
            $mail->setFrom('yourgmail@gmail.com', 'Jail Management System');
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Hi, <br><br>Click the link below to reset your password:<br>
                              <a href='$resetLink'>$resetLink</a><br><br>
                              If you didn't request this, please ignore this email.";

            $mail->send();
            $message = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        <?php include 'style.css'; ?>
        .message {
            margin-top: 10px;
            font-size: 14px;
            color: #0074cc;
            background: #e6f2ff;
            padding: 8px;
            border-radius: 8px;
            text-align: center;
        }
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
        <h2>Forgot Password</h2>
        <form method="POST">
            <label>EMAIL</label>
            <div class="input-box">
                <span class="icon">📧</span>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <?php if (!empty($message)): ?>
                <div class="<?php echo strpos($message, 'No account') !== false ? 'error' : 'message'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="login-btn">Send Reset Link</button>

            <div class="signup">
                Remembered your password? <a href="index.php">Login</a>
            </div>
        </form>
    </div>
</body>
</html>
