<?php
session_start();
require 'db.php';

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$errors = [];
$success = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']==='add') {
    $inmate_number = trim($_POST['inmate_number'] ?? '');
    $fullname      = trim($_POST['fullname'] ?? '');
    $age           = (int)($_POST['age'] ?? 0);
    $gender        = trim($_POST['gender'] ?? '');
    $case_type     = trim($_POST['case_type'] ?? '');
    $status        = trim($_POST['status'] ?? 'Active');
    $full_details  = trim($_POST['full_details'] ?? '');

    if ($inmate_number === '' || $fullname === '' || $age <= 0 || $gender === '' || $case_type === '') {
        $errors[] = "Please fill all required fields correctly.";
    } else {
        $stmt = $conn->prepare("INSERT INTO bookings (inmate_number, fullname, age, gender, case_type, status, full_details) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("ssissss", $inmate_number, $fullname, $age, $gender, $case_type, $status, $full_details);
        if (!$stmt->execute()) {
            if ($conn->errno == 1062) {
                $errors[] = "Inmate number already exists.";
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        } else {
            $success = "Booking added.";
        }
        $stmt->close();
    }
}


$editRow = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $editRow = $res->fetch_assoc();
    $stmt->close();
    if (!$editRow) $errors[] = "Record to edit not found.";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']==='update') {
    $id            = (int)($_POST['id'] ?? 0);
    $inmate_number = trim($_POST['inmate_number'] ?? '');
    $fullname      = trim($_POST['fullname'] ?? '');
    $age           = (int)($_POST['age'] ?? 0);
    $gender        = trim($_POST['gender'] ?? '');
    $case_type     = trim($_POST['case_type'] ?? '');
    $status        = trim($_POST['status'] ?? 'Active');
    $full_details  = trim($_POST['full_details'] ?? '');

    if ($id <= 0 || $inmate_number === '' || $fullname === '' || $age <= 0 || $gender === '' || $case_type === '') {
        $errors[] = "Please fill all required fields correctly.";
    } else {
        $stmt = $conn->prepare("UPDATE bookings SET inmate_number=?, fullname=?, age=?, gender=?, case_type=?, status=?, full_details=? WHERE id=?");
        $stmt->bind_param("ssissssi", $inmate_number, $fullname, $age, $gender, $case_type, $status, $full_details, $id);
        if (!$stmt->execute()) {
            if ($conn->errno == 1062) {
                $errors[] = "Inmate number already exists.";
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        } else {
            $success = "Booking updated.";
            header("Location: booking.php");
            exit();
        }
        $stmt->close();
    }
}


    if (isset($_GET['delete'])) {
        $del_id = (int)$_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id=?");
        $stmt->bind_param("i", $del_id);
        $stmt->execute();
        $stmt->close();
        header("Location: booking.php");
        exit();
    }

        $rows = [];
        $res = $conn->query("SELECT * FROM bookings ORDER BY booked_at DESC");
        if ($res) {
            while ($r = $res->fetch_assoc()) $rows[] = $r;
        }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking | Jail Management System</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        .content-wrap { 
            width: 100%; 
            max-width: 1100px; 
        }
        .panel {
            background:#fff; 
            padding:16px;
            border-radius:10px; 
            box-shadow:0 2px 6px rgba(0,0,0,.1); 
            margin-bottom:16px; 
        }
        .panel h3 {
            margin-bottom:10px; 
        }
        form .grid { 
            display:grid;
            grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
            gap:12px; 
        }
        input, select, textarea {
            width:100%; 
            padding:10px; 
            border:1px solid #ddd; 
            border-radius:8px; 
        }
        textarea {
            min-height:80px; 
        }
        .btn-row { 
            display:flex;
            gap:8px; 
            flex-wrap:wrap; 
            margin-top:10px; 
        }
        .btn {
            padding:10px 14px;
            border:none;
            border-radius:8px; 
            cursor:pointer; 
        }
        .btn.primary { 
            background:#0074cc; 
            color:#fff; 
        }
        .btn.secondary { 
            background:#f0f0f0; 
        }
        .table { 
            width:100%; 
            border-collapse:collapse; 
            background:#fff; 
            border-radius:10px; 
            overflow:hidden; 
        }
        .table th, .table td { 
            padding:10px; 
            border-bottom:1px solid #eee; 
            text-align:center; 
        }
        .table th { 
            background:#0074cc; 
            color:#fff; 
            position:sticky; 
            top:0; 
        }
        .msg { 
            margin-bottom:12px;
            padding:10px; 
            border-radius:8px; 
        }
        .msg.ok { 
            background:#e6f7ee; 
            color:#0f7a3a; 
        }
        .msg.err { 
            background:#fdecec; 
            color:#b82121; }
        .sidebar li a { 
            display:flex; 
            align-items:center; 
            gap:10px; color:inherit; 
            text-decoration:none; 
            width:100%; 
        }
    </style>

</head>
<body>
    <div class="dashboard" id="dashboard" style="display:block;">
        <?php include 'sidebar.php'; ?>
    <main class="main-panel">
        <div class="content-wrap">
            <h2 class="analytics-title">Booking</h2>

            <?php if ($success): ?><div class="msg ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php foreach ($errors as $e): ?><div class="msg err"><?= htmlspecialchars($e) ?></div><?php endforeach; ?>

            <div class="panel">
                <h3 style="color: #0074cc"><i class="fa-solid fa-plus"></i> Add New Booking</h3>
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="action" value="add">
                    <div class="grid">
                        <input type="text"   name="inmate_number" placeholder="Inmate Number" required>
                        <input type="text"   name="fullname"      placeholder="Full Name" required>
                        <input type="number" name="age"           placeholder="Age" min="1" required>
                        <select name="gender" required>
                            <option value="">Gender</option>
                            <option>Male</option><option>Female</option><option>Other</option>
                        </select>
                        <input type="text"   name="case_type"     placeholder="Case" required>
                        <select name="status" required>
                            <option>Active</option><option>Released</option>
                        </select>
                    </div>
                    <div class="grid" style="grid-template-columns:1fr;">
                        <textarea name="full_details" placeholder="Full details (optional)"></textarea>
                    </div>
                    <div class="btn-row">
                        <button class="btn primary" type="submit">Add Booking</button>
                        <button class="btn secondary" type="reset">Clear</button>
                    </div>
                </form>
            </div>

            <?php if ($editRow): ?>
            <div class="panel" id="editPanel">
                <h3><i class="fa-solid fa-pen"></i> Edit Booking #<?= (int)$editRow['id'] ?></h3>
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= (int)$editRow['id'] ?>">
                    <div class="grid">
                        <input type="text"   name="inmate_number" value="<?= htmlspecialchars($editRow['inmate_number']) ?>" placeholder="Inmate Number" required>
                        <input type="text"   name="fullname"      value="<?= htmlspecialchars($editRow['fullname']) ?>" placeholder="Full Name" required>
                        <input type="number" name="age"           value="<?= (int)$editRow['age'] ?>" placeholder="Age" min="1" required>
                        <select name="gender" required>
                            <?php
                            $genders = ['Male','Female','Other'];
                            foreach ($genders as $g) {
                                $sel = ($editRow['gender']===$g) ? 'selected' : '';
                                echo "<option $sel>$g</option>";
                            }
                            ?>
                        </select>
                        <input type="text"   name="case_type"     value="<?= htmlspecialchars($editRow['case_type']) ?>" placeholder="Case" required>
                        <select name="status" required>
                            <?php
                            $statuses = ['Active','Released'];
                            foreach ($statuses as $s) {
                                $sel = ($editRow['status']===$s) ? 'selected' : '';
                                echo "<option $sel>$s</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="grid" style="grid-template-columns:1fr;">
                        <textarea name="full_details" placeholder="Full details (optional)"><?= htmlspecialchars($editRow['full_details'] ?? '') ?></textarea>
                    </div>
                    <div class="btn-row">
                        <button class="btn primary" type="submit">Save Changes</button>
                        <a class="btn secondary" href="booking.php">Cancel</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <div class="panel">
                <h3 style="color: #0074cc"><i class="fa-solid fa-table"></i> Bookings</h3>
                <div style="overflow:auto;">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Inmate #</th>
                            <th>Full Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Case</th>
                            <th>Status</th>
                            <th>Booked At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php if (!$rows): ?>
                            <tr><td colspan="9">No records yet.</td></tr>
                        <?php else: foreach ($rows as $r): ?>

                            <tr>
                                <td><?= (int)$r['id'] ?></td>
                                <td><?= htmlspecialchars($r['inmate_number']) ?></td>
                                <td><?= htmlspecialchars($r['fullname']) ?></td>
                                <td><?= (int)$r['age'] ?></td>
                                <td><?= htmlspecialchars($r['gender']) ?></td>
                                <td><?= htmlspecialchars($r['case_type']) ?></td>
                                <td><?= htmlspecialchars($r['status']) ?></td>
                                <td><?= htmlspecialchars($r['booked_at']) ?></td>

                                <td>
                                    <a href="booking.php?edit=<?= (int)$r['id'] ?>">✏️ Edit</a>
                                    &nbsp;|&nbsp;
                                    <a href="booking.php?delete=<?= (int)$r['id'] ?>" onclick="return confirm('Delete this booking?')">🗑️ Delete</a>
                                </td>

                            </tr>
                            <?php if (!empty($r['full_details'])): ?>
                            <tr>
                                <td colspan="9" style="text-align:left;background:#fafafa;">
                                    <strong>Details:</strong> <?= nl2br(htmlspecialchars($r['full_details'])) ?>
                                </td>
                            </tr>

                            <?php endif; ?>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
