<?php
//initialise session
session_start();

//unset all the session variables
$_SESSION = array();

//destroy session
session_destroy();

//redirect to login page
header("location: login.php");
exit;
?>
