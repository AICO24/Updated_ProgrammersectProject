<?php
session_start();
require 'db.php';

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // ✅ change this to your actual PK column (check in phpMyAdmin)
    $pk = "inmate_id";

    // Get record before delete
    $res = $conn->query("SELECT * FROM inmates WHERE $pk = $id");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $jsonData = $conn->real_escape_string(json_encode($row));
        $username = $conn->real_escape_string($_SESSION["username"]);

        // Save to archive
        $conn->query("INSERT INTO archive_records (module_name, record_id, data, deleted_by)
                      VALUES ('Inmate Management', $id, '$jsonData', '$username')");

        // Delete record
        $conn->query("DELETE FROM inmates WHERE $pk = $id");
    }
}

header("Location: inmate-management.php?msg=Archived");
exit();
?>
