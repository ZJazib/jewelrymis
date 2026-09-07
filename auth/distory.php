<?php
session_start(); // start a new session

// unset all session variables
$_SESSION = array();

// destroy the session
session_destroy();

// redirect the user to the login page
header('Location: signin.php');
exit();
?>