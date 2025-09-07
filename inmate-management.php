<?php
session_start();
require 'db.php';

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION["username"];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $alias = mysqli_real_escape_string($conn, $_POST['alias']);
    $age = (int)$_POST['age'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $birthdate = $_POST['birthdate'];
    $height = mysqli_real_escape_string($conn, $_POST['height']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $eye_color = mysqli_real_escape_string($conn, $_POST['eye_color']);
    $crime = mysqli_real_escape_string($conn, $_POST['crime']);
    $cell_block = mysqli_real_escape_string($conn, $_POST['cell_block']);
    $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status']);
    $language = mysqli_real_escape_string($conn, $_POST['language']);
    $citizenship = mysqli_real_escape_string($conn, $_POST['citizenship']);
    $religion = mysqli_real_escape_string($conn, $_POST['religion']);

    // handle photo upload
    $photo = $_FILES['photo']['name'];
    $medical = $_FILES['medical']['name'];
    if (!is_dir("uploads")) {
        mkdir("uploads");
    }
    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/$photo");
    move_uploaded_file($_FILES['medical']['tmp_name'], "uploads/$medical");

    // Insert inmate
    $sql = "INSERT INTO inmates 
        (fullname, alias, age, address, birthdate, height, weight, eye_color, crime, cell_block, marital_status, language, citizenship, religion, photo, medical)
        VALUES ('$fullname', '$alias', $age, '$address', '$birthdate', '$height', '$weight', '$eye_color', '$crime', '$cell_block', '$marital_status', '$language', '$citizenship', '$religion', '$photo', '$medical')";

    if ($conn->query($sql)) {
        $inmate_id = $conn->insert_id;

        // Emergency Contact
        $ename = mysqli_real_escape_string($conn, $_POST['emergency_name']);
        $erel = mysqli_real_escape_string($conn, $_POST['emergency_relationship']);
        $eaddr = mysqli_real_escape_string($conn, $_POST['emergency_address']);
        $econtact = mysqli_real_escape_string($conn, $_POST['emergency_contact']);
        $conn->query("INSERT INTO inmate_emergency_contacts (inmate_id, name, relationship, address, contact_number) VALUES ($inmate_id, '$ename', '$erel', '$eaddr', '$econtact')");

        // Family Background
        $mother = mysqli_real_escape_string($conn, $_POST['mother']);
        $father = mysqli_real_escape_string($conn, $_POST['father']);
        $siblings = mysqli_real_escape_string($conn, $_POST['siblings']);
        $conn->query("INSERT INTO inmate_family (inmate_id, mother, father, siblings) VALUES ($inmate_id, '$mother', '$father', '$siblings')");

        // Education
        $primary = mysqli_real_escape_string($conn, $_POST['primary_ed']);
        $secondary = mysqli_real_escape_string($conn, $_POST['secondary_ed']);
        $tertiary = mysqli_real_escape_string($conn, $_POST['tertiary_ed']);
        $conn->query("INSERT INTO inmate_education (inmate_id, primary_ed, secondary_ed, tertiary_ed) VALUES ($inmate_id, '$primary', '$secondary', '$tertiary')");

        $success = "✅ Inmate added successfully!";
    } else {
        $error = "❌ Error: " . $conn->error;
    }
}

// Fetch inmates for display
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
if (!empty($search)) {
    $inmates = $conn->query("SELECT * FROM inmates 
                             WHERE fullname LIKE '%$search%' 
                                OR alias LIKE '%$search%' 
                                OR crime LIKE '%$search%' 
                             ORDER BY fullname ASC");
} else {
    $inmates = $conn->query("SELECT * FROM inmates ORDER BY fullname ASC");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inmate Management</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <div class="dashboard" id="dashboard" style="display:block;">
        <?php include 'sidebar.php'; ?>
        <main class="main-panel">
            <header class="topbar">
                <span><?php echo htmlspecialchars($username); ?></span>
            </header>

            <section class="content-panel">
                <h2>Inmate Management</h2>

                <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
                <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

                <!-- Inmate Form -->
                <form method="POST" enctype="multipart/form-data" class="inmate-form">
            <!-- Button -->
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addInmateModal">+ Add Inmate</button>

            <!-- Modal -->
            <div class="modal fade" id="addInmateModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" class="inmate-form">
                    <div class="modal-header">
                    <h5 class="modal-title">Add Inmate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">

                    <!-- Basic Information -->
                    <h6 class="text-primary fw-bold">Basic Information</h6>
                    <div class="col-md-6"><input type="text" name="fullname" class="form-control" placeholder="Full Name" required></div>
                    <div class="col-md-6"><input type="text" name="alias" class="form-control" placeholder="Alias"></div>
                    <div class="col-md-3"><input type="number" name="age" class="form-control" placeholder="Age" required></div>
                    <div class="col-md-9"><input type="text" name="address" class="form-control" placeholder="Address"></div>
                    <div class="col-md-4"><input type="date" name="birthdate" class="form-control"></div>
                    <div class="col-md-4"><input type="text" name="height" class="form-control" placeholder="Height"></div>
                    <div class="col-md-4"><input type="text" name="weight" class="form-control" placeholder="Weight"></div>
                    <div class="col-md-4"><input type="text" name="eye_color" class="form-control" placeholder="Eye Color"></div>
                    <div class="col-md-8"><input type="text" name="crime" class="form-control" placeholder="Crime Committed"></div>
                    <div class="col-md-6"><input type="text" name="cell_block" class="form-control" placeholder="Assign Cell Block"></div>
                    <div class="col-md-6"><input type="file" name="photo" class="form-control" accept="image/*"></div>
                    <div class="col-md-6"><input type="file" name="medical" class="form-control"></div>
                    <div class="col-md-6"><input type="text" name="marital_status" class="form-control" placeholder="Marital Status"></div>
                    <div class="col-md-4"><input type="text" name="language" class="form-control" placeholder="Language"></div>
                    <div class="col-md-4"><input type="text" name="citizenship" class="form-control" placeholder="Citizenship"></div>
                    <div class="col-md-4"><input type="text" name="religion" class="form-control" placeholder="Religion"></div>

                    <!-- Family Background -->
                    <h6 class="text-primary fw-bold mt-3">Family Background</h6>
                    <div class="col-md-4"><input type="text" name="mother" class="form-control" placeholder="Mother"></div>
                    <div class="col-md-4"><input type="text" name="father" class="form-control" placeholder="Father"></div>
                    <div class="col-md-4"><input type="text" name="siblings" class="form-control" placeholder="Siblings"></div>

                    <!-- Education -->
                    <h6 class="text-primary fw-bold mt-3">Education</h6>
                    <div class="col-md-4"><input type="text" name="primary_ed" class="form-control" placeholder="Primary"></div>
                    <div class="col-md-4"><input type="text" name="secondary_ed" class="form-control" placeholder="Secondary"></div>
                    <div class="col-md-4"><input type="text" name="tertiary_ed" class="form-control" placeholder="Tertiary"></div>

                    <!-- Emergency -->
                    <h6 class="text-primary fw-bold mt-3">Emergency Contact</h6>
                    <div class="col-md-3"><input type="text" name="emergency_name" class="form-control" placeholder="Name" required></div>
                    <div class="col-md-3"><input type="text" name="emergency_relationship" class="form-control" placeholder="Relationship"></div>
                    <div class="col-md-3"><input type="text" name="emergency_address" class="form-control" placeholder="Address"></div>
                    <div class="col-md-3"><input type="text" name="emergency_contact" class="form-control" placeholder="Contact Number"></div>

                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            <?php
            $inmates = $conn->query("SELECT * FROM inmates ORDER BY fullname ASC");

            // pull all rows into an array
            $rows = [];
            if ($inmates) {
                while ($r = $inmates->fetch_assoc()) { $rows[] = $r; }
            }

            // detect likely primary key field
            $idField = null;
            if (!empty($rows)) {
                foreach (['id','inmate_id','inmates_id','inmateID','inmateId'] as $c) {
                    if (array_key_exists($c, $rows[0])) { $idField = $c; break; }
                }
            }
            ?>

            <?php if (!empty($rows)): ?>


        <form method="GET" action="inmate-management.php" style="display: flex; align-items: center; gap: 5px; max-width: 400px; margin-top: 10px;">
            <input type="text" name="search" class="form-control" placeholder="Search inmate..." style="flex: 1;">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th>Photo</th>
                    <th>Full Name</th>
                    <th>Alias</th>
                    <th>Age</th>
                    <th>Cell Block</th>
                    <th>Crime</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                <tr>
                    <td>
                        <?php if (!empty($row['photo'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" width="80" height="80" style="border-radius:5px;">
                        <?php else: ?>
                            No photo
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['alias']); ?></td>
                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                    <td><?php echo htmlspecialchars($row['cell_block']); ?></td>
                    <td><?php echo htmlspecialchars($row['crime']); ?></td>
                    <td>
                        <?php if ($idField): ?>
                            <a href="view_inmate.php?id=<?php echo urlencode($row[$idField]); ?>">View</a> |
                            <a href="delete_inmate.php?id=<?php echo urlencode($row[$idField]); ?>"
                            onclick="return confirm('Are you sure?')">Delete</a>
                        <?php else: ?>
                            <em>No ID column found</em>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
                <p>No inmates found.</p>
            <?php endif; ?>


                </table>
            </section>
        </main>
    </div>
</body>
</html>
