<?php
require "../config/Dbconn.php"; // Ensure this file initializes $conn properly

// Check if the `jbm` parameter is set
if (isset($_GET['jbm'])) {
    $search = trim($_GET['jbm']); // Trim whitespace from the search query

    // If the search query is empty, fetch all rows
    if (empty($search)) {
        $query = "SELECT * FROM customer";
    } else {
        // If a search query is provided, search across relevant columns
        $search = htmlspecialchars($search); // Sanitize input
        $query = "SELECT * FROM customer WHERE 
                  role LIKE '%$search%' OR 
                  name LIKE '%$search%' OR 
                  nic LIKE '%$search%' OR 
                  loc LIKE '%$search%'";
    }

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo '<table class="table text-start align-middle table-bordered table-hover mb-0">';
        echo '<thead><tr class="text-white bg-primary text-center">';
        echo '<th>Role</th><th>Name</th><th>NIC</th><th>Phone</th><th>Email</th><th>Location</th><th colspan="2">Action</th>';
        echo '</tr></thead><tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr class="text-white bg-dark text-center">';
            echo '<td>' . $row['role'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>' . $row['nic'] . '</td>';
            echo '<td>' . $row['phone'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['loc'] . '</td>';
            echo '<td><a class="btn btn-sm btn-primary" href="cus_deb.php?t=' . $row['id'] . '"><i class="far fa-plus"></i> Debit (بردگی</a></td>';
            echo '<td><a class="btn btn-sm btn-success" href="cus_cre.php?t=' . $row['id'] . '"><i class="far fa-plus"></i>  Credit (رسیدگی</a></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p class="text-dark text-center">No matching records found.</p>';
    }
}
?>
