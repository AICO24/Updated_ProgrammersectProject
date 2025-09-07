<?php
session_start();
require 'db.php';

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("No inmate selected.");
}

$id = (int)$_GET['id'];

// detect which column exists
$col = "id";
$hasId = $conn->query("SHOW COLUMNS FROM inmates LIKE 'id'")->num_rows > 0;
if (!$hasId) { $col = "inmate_id"; }

$sql = "SELECT * FROM inmates WHERE $col = $id LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Inmate not found.");
}

$inmate = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Inmate</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <main class="main-panel">
        <header class="topbar">
            <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span>
        </header>

        <section class="content-panel">
            <h2>Inmate Profile: <?php echo htmlspecialchars($inmate['fullname']); ?></h2>
            <div style="display:flex; gap:20px;">
                <div>
                    <?php if (!empty($inmate['photo'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($inmate['photo']); ?>" width="150" height="150" style="border-radius:5px;">
                    <?php else: ?>
                        <p style="color: black">No photo available</p>
                    <?php endif; ?>
                </div>
                <div style="color: black;">
                    <p><strong>Alias:</strong> <?php echo htmlspecialchars($inmate['alias']); ?></p>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($inmate['age']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($inmate['address']); ?></p>
                    <p><strong>Birth Date:</strong> <?php echo htmlspecialchars($inmate['birthdate']); ?></p>
                    <p><strong>Height:</strong> <?php echo htmlspecialchars($inmate['height']); ?></p>
                    <p><strong>Weight:</strong> <?php echo htmlspecialchars($inmate['weight']); ?></p>
                    <p><strong>Eye Color:</strong> <?php echo htmlspecialchars($inmate['eye_color']); ?></p>
                    <p><strong>Crime:</strong> <?php echo htmlspecialchars($inmate['crime']); ?></p>
                    <p><strong>Cell Block:</strong> <?php echo htmlspecialchars($inmate['cell_block']); ?></p>
                    <p><strong>Marital Status:</strong> <?php echo htmlspecialchars($inmate['marital_status']); ?></p>
                    <p><strong>Language:</strong> <?php echo htmlspecialchars($inmate['language']); ?></p>
                    <p><strong>Citizenship:</strong> <?php echo htmlspecialchars($inmate['citizenship']); ?></p>
                    <p><strong>Religion:</strong> <?php echo htmlspecialchars($inmate['religion']); ?></p>
                </div>
            </div>
            <br>
            <a href="inmate-management.php">← Back to Inmate Management</a>
        </section>
    </main>
</body>
</html>
