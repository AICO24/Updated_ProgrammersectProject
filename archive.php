<?php
session_start();
require 'db.php';

$result = $conn->query("SELECT * FROM archive_records ORDER BY deleted_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Archive Records</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
    <h2>Archived Records</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Module</th>
            <th>Original Record ID</th>
            <th>Data</th>
            <th>Deleted By</th>
            <th>Deleted At</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['archive_id']; ?></td>
            <td><?php echo htmlspecialchars($row['module_name']); ?></td>
            <td><?php echo $row['record_id']; ?></td>
            <td><pre><?php echo htmlspecialchars($row['data']); ?></pre></td>
            <td><?php echo htmlspecialchars($row['deleted_by']); ?></td>
            <td><?php echo $row['deleted_at']; ?></td>
        </tr>
        <?php endwhile; ?>

        <a href="dashboard.php">← Back to Dashboard</a>
    </table>
</body>
</html>
