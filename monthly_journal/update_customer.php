<?php
// DB connection
require "../config/Dbconn.php";

// Get customer ID from GET
if (isset($_GET['customer_id'])) {
    $customer_id = intval($_GET['customer_id']);

    $sql = "SELECT * FROM customer WHERE id = $customer_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $customer = $result->fetch_assoc();
    } else {
        echo "Customer not found.";
        exit;
    }
} else {
    echo "Customer ID not provided.";
    exit;
}

// Handle form submission
$update_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    $updateSql = "UPDATE customer SET name='$name', email='$email', phone='$phone' WHERE id=$customer_id";

    if ($conn->query($updateSql)) {
        $update_message = "<div class='alert alert-success'>Customer info updated successfully.</div>";
    } else {
        $update_message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Update Customer Info</h3>

    <?= $update_message ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="mjournal.php" class="btn btn-secondary">Monthly Journal</a>
    </form>
</div>
</body>
</html>
