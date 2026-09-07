<?php
require "../config/Dbconn.php";

// Validate and fetch ID from GET request
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID provided.");
}

$id = intval($_GET['id']);
$cid = intval($_GET['cid']);

// Fetch record details
$sql = "SELECT * FROM statement WHERE se_id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $record = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$record) {
        die("Record not found.");
    }
} else {
    die("Error preparing statement: " . mysqli_error($conn));
}

// Initialize error message
$errorMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $date = filter_input(INPUT_POST, 'se_date', FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_POST, 'se_type', FILTER_SANITIZE_STRING);
    $tbill = filter_input(INPUT_POST, 'se_tbill', FILTER_VALIDATE_FLOAT);
    $tpurity = filter_input(INPUT_POST, 'se_tpurity', FILTER_VALIDATE_FLOAT);
    $description = $_POST['description'];  // Allow HTML content here
    $ref = $_POST['ref'];

    // Validate required fields
    if ($date && $type && $tbill !== false && $tpurity !== false) {
        // Ensure proper escaping of HTML content to prevent SQL injection
        $description = mysqli_real_escape_string($conn, $description);
        
        // Update query to store HTML description
        $updateSql = "UPDATE statement SET se_date = ?, se_type = ?, se_tbill = ?, se_tpurity = ?, dis = ?, ref = ? WHERE se_id = ?";
        if ($updateStmt = mysqli_prepare($conn, $updateSql)) {
            mysqli_stmt_bind_param($updateStmt, "ssdsssi", $date, $type, $tbill, $tpurity, $description, $ref, $id);
            if (mysqli_stmt_execute($updateStmt)) {
                // Redirect after successful update
                header("Location: ./mjournal.php?id=$cid");
                exit();
            } else {
                $errorMessage = "Error updating record: " . mysqli_error($conn);
            }
            mysqli_stmt_close($updateStmt);
        } else {
            $errorMessage = "Error preparing update statement: " . mysqli_error($conn);
        }
    } else {
        $errorMessage = "Invalid input data. Please check your entries.";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="ckeditor/ckeditor.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Edit Statement Record</h1>

    <!-- Display error message if exists -->
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>

    <!-- Edit Form -->
    <form action="" method="POST">
        <div class="mb-3">
            <label for="se_date" class="form-label">Date</label>
            <input type="date" name="se_date" id="se_date" class="form-control"
                   value="<?= htmlspecialchars($record['se_date']) ?>" required>
        </div>
        
 <div class="mb-3">
            <label for="ref" class="form-label">Reference</label>
            <input type="text" name="ref" id="ref" class="form-control"
                   value="<?= htmlspecialchars($record['ref']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="se_type" class="form-label">Type</label>
            <input type="text" name="se_type" id="se_type" class="form-control"
                   value="<?= htmlspecialchars($record['se_type']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="se_tbill" class="form-label">Total Bill</label>
            <input type="number" step="0.01" name="se_tbill" id="se_tbill" class="form-control"
                   value="<?= htmlspecialchars($record['se_tbill']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="se_tpurity" class="form-label">Total Purity</label>
            <input type="number" step="0.001" name="se_tpurity" id="se_tpurity" class="form-control"
                   value="<?= htmlspecialchars($record['se_tpurity']) ?>" required>
        </div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
   <textarea name="description" id="description" class="form-control" rows="4"><?= $record['dis'] ?></textarea>
</div>


        <button type="submit" class="btn btn-primary">Update Record</button>
        <a href="./mjournal.php?id=<?=$cid?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
