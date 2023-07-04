<?php
//initialise the session
session_start();

//check if the user is already logged in, if yes then redirect to profile page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: profile.php");
    exit;
}

//include config file
require_once "config.php";

//define variables and initialise with empty values 
$studentID = $password = "";
$studentID_err = $password_err = "";

//processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    //check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter password.";
    } else{
        $password = trim($_POST["password"]);
    }

    //check credentials
    if(empty($username_err) && empty($password_err)){
        //select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = $mysqli->prepare($sql)){
            //bind variables to the prepared statement as para
            $stmt->bind_param("s", $param_username);

            //set para
            $param_username = $username;

            //attempt to exexcute select statment
            if($stmt->execute()){
                //store results
                $stmt->store_result();

                //check if username exists, if yes verify password
                if($stmt->num_rows == 1){
                    //bind result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                }
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            //correct password (new session)
                            session_start();

                            //store data in session variable
                            $_SESSION["logged in"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            //redirect to profile page
                            header("location: profile.php");
                        } else{
                            //error msg if password is incorrect
                            $password_err = "The password your entered is not valid. Please try again.";
                        }
                    }
                } else{
                    //error msg id username is incorrect
                    $username_err = "No account found with entered username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        
        //close statement
        $stmt->close();
    }
    //close connection
    $mysqli->close();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>TeamUsUp</title>
        <link rel="stylesheet" href="login.css">
        <script src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
             html {
               
            background: linear-gradient(rgba(190, 189, 189, 0.5),rgba(190, 189, 189, 0.5)), url(Murdoch-University.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
         }
           
        </style>
    </head>
    <body>
        <header class="header">
            <div class="logo-box">
                <img src="./Murdoch_port_Redbox-300x300.png" alt="logo" class="logo">
            </div>
            <div class="text-box">
                <p><a href="#">TeamUsUp</a></p>
            </div>

        </header>

        <div class ="login-box">
        <section class="login-form">
            <h1><img src="./murdoch black logo.png" alt="black" class="black"></h1>
            <FORM action="login.php" method = "post">
                <div class="int-area">
                    <input type="text" name="email" id="id"
                    autocomplete="off" required>
                    <label for="id">STUDENT EMAIL</label>
                </div>
                <div class="int-area">
                    <input type="password" name="pw" id="pw"
                    autocomplete="off" required>
                    <label for="pw">PASSWORD</label>
                </div>
                <div class="btn-area">
                    <button id="btn"
                    type="submit">LOGIN</button>
                </div>
            </FORM>
            <div class="caption">
                <a href="">Forgot Passord?</a>
            </div>
        </section>
        </div>
        

        <script>
            let id = $('#id');
            let pw = $('#pw');
            let btn = $('#btn');

            $(btn).on('click', function () {
                if($(id).val() == "") {
                    $(id).next('label').addClass('warning');
                    setTimeout(function() {
                        $('label').removeClass('warning');
                    },1500);
                }
                else if($(pw).val() == "") {
                    $(pw).next('label').addClass('warning');
                    setTimeout(function() {
                        $('label').removeClass('warning');
                    },1500);
                }
            });
        </script>
    
        
         
    <footer class="footer">
        <div class="footer-box">

                <p>Murdoch University &copy; FTGroup08 TeamUsUp  </p>
  
            
        </div>
    </footer>          
       
    </body>
</html>