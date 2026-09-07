<?php
    require "../config/Dbconn.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete query
    $sql = "DELETE FROM statement WHERE se_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Record deleted successfully!";
        header("Location: index.php"); // Redirect back to list page
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "No record ID provided!";
    exit();
}
?>
