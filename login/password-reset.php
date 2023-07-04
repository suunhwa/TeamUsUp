<?php
//initialise session
session_start();

//include config.php
require_once "config.php";

//check if user is logged in, if not redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

//define variable and initialise with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

//process form data when submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //validate new password
    if(empty(trim($_POST["new password"]))){
        $new_password_err = "Please enter new password.";
    } elseif(strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have at least 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }

    //check confirm password
    if(empty(trim($_POST["confirm password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match";
        }
    }

    //check for any input errors before updating the database
    if(empty($new_passord_err) && empty($confrim_password_err)){
        //update statement
        $sql = "UPDATE account SET password = ? WHERE id = ?";
    }
}