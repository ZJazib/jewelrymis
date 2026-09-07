<?php
require "config/Dbconn.php";
include "header.php";
$res = mysqli_query($conn,"SELECT * FROM gold_jewelry ORDER BY id DESC");
?>

<h2 class="mb-4">All Jewelry Stock</h2>
<table class="table table-bordered table-striped">
<tr class="table-dark">
    <th>ID</th><th>Name</th><th>Serial</th><th>Karat</th><th>Weight</th><th>Status</th><th>Category</th><th>Image</th>
</tr>
<?php while($row = mysqli_fetch_assoc($res)){ ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['jewelry_name'] ?></td>
    <td><?= $row['serial_number'] ?></td>
    <td><?= $row['karat'] ?></td>
    <td><?= $row['weight'] ?></td>
    <td><?= $row['status'] ?></td>
    <td><?= $row['category'] ?></td>
    <td><?php if($row['image']) echo "<img src='uploads/".$row['image']."' width='50'>"; ?></td>
</tr>
<?php } ?>
</table>

<?php include "footer.php"; ?>
