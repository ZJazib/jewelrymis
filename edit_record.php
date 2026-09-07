<?php
require "config/Dbconn.php";

// Validate and fetch ID from GET request
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID provided.");
}

$id = intval($_GET['id']);

// Fetch record details



// Initialize error message
$errorMessage = "";

// Handle form submission
if (isset($_POST['submit'])) {
    // Sanitize and validate input
    $date = $_POST[ 'se_date']; 
    $type = $_POST[ 'se_type']; 
    $tbill = $_POST[ 'se_tbill']; 
    $tpurity = $_POST[ 'se_tpurity']; 
    $description = $_POST['description'];  // Allow HTML content here


    // Validate required fields
  
        
        // Update query to store HTML description
        $updateSql = "UPDATE customer SET name = $date, nic = $type, phone = $tbill, email = $tpurity, loc = $description WHERE id = $id";
    $updateStmt = mysqli_query($conn, $updateSql);
  
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
    <h1 class="text-center">Edit Customer Or Supplier Record</h1>

    <!-- Display error message if exists -->
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>
<?php 
   $sql = "SELECT * FROM `customer` where id = $id";
    $res = mysqli_query($conn, $sql);
          if (mysqli_num_rows($res) > 0) {
    $record = mysqli_fetch_assoc($res);

}
?>
    <!-- Edit Form -->
    <form action="" method="POST">
        <div class="mb-3">
            <label for="se_date" class="form-label">Name</label>
            <input type="text" name="se_date" id="se_date" class="form-control"
                   value="<?= htmlspecialchars($record['name']) ?>" required>
        </div>
        

        <div class="mb-3">
            <label for="se_type" class="form-label">NIC</label>
            <input type="text" name="se_type" id="se_type" class="form-control"
                   value="<?= htmlspecialchars($record['nic']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="se_tbill" class="form-label">Phone</label>
            <input type="number" step="0.01" name="se_tbill" id="se_tbill" class="form-control"
                   value="<?= htmlspecialchars($record['phone']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="se_tpurity" class="form-label">email</label>
            <input type="text" step="0.001" name="se_tpurity" id="se_tpurity" class="form-control"
                   value="<?= htmlspecialchars($record['email']) ?>" required>
        </div>
<div class="mb-3">
    <label for="description" class="form-label">Address</label>
   <textarea name="description" id="description" class="form-control" rows="4"><?= $record['loc'] ?></textarea>
</div>


        <input type="submit" name="submit" class="btn btn-primary" value ="Update Record">
        <a href="cus_supplier_list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
