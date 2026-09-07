<?php
    $conn = mysqli_connect("localhost", "hmajewellery_user", getenv("DB_PASSWORD") ?: "", "hmajewellery_dba");
   // check if the connection was successful
if (!$conn) {
    die(mysqli_connect_error());
}
?>