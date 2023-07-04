<?php

    //include config
    require_once "config.php";

    //define variables and initialise with empty values
    $studentID = $password = $confirm_pasword = "";
    $studentID_err = $password_err = $confirm_password_err = "";


    //process data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //*check username
        if(empty(trim($POST["studentID"]))){
            $username_err = "Please enter username.";
        } else{
            //prepare select statement
            $sql = "SELECT studentID FROM student WHERE studentID = ?";

            if ($stmt = $mysqli->prepare($sql)){
                //bind variables to the prepared statement as para
                $stmt->bind_param("s", $param_studentID);

                //set para
                $param_studentID = trim($_POST["studentID"]);

                //attempt to execute prepared statement
                if($stmt->execute()){
                    //store result
                    $stmt->store_result();

                    if($stmt->num_rows == 1){
                        $studentID_err = "This student ID has alreaday been used.";
                    } else{
                        $studentID = trim($_POST["studentID"]);
                    }
                } else{
                    echo "Oops! Something went wrong, please try again later.";
                }

                //close statement
                $stmt->close();
            }
        }

        //*check password
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter a password.";
        } elseif(strlen(trim($_POST["password"])) < 6) {
            $password_err = "Password must have at least 6 characters.";
        } else{
            $password = trim($_POST["password"]);
        }

        //*check confirm password
        if(empty(trim($_POST["confirm password"]))){
            $confirm_password_err = "Please confirm your password.";
        } else{
            $confirm_password = trim($_POST["confrim password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Passwords did not match. Please re-enter password.";
            }
        }

        //*check for input errors before inserting into database
        if(empty($studentID_err) && empty($password_err) && empty($confirm_password_err)){

            //insert new data into database
            $sql = "INSERT INTO student (studentID, password) VALUES (?, ?)";

            if($stmt = $mysqli->prepare($sql)){
                //bind variables to the preapared statement as para
                $stmt->bind_param("ss", $param_studentID, $param_password);

                //set parameters
                $param_studentID = $studentID;
                $param_password = $password_hash ($password, PASSWORD_DEFAULT); //creates password hash

                //attempt to execute prepared statement
                if($stmt->execute()){
                    //redirect to login page
                    header("location: login.php");
                } else{
                    echo "Oops! Something went wrong, please try again later.";
                } 

                //close statement
                $stmt->close();
            }
        }

        //close connection
        $mysqli->close();
    }
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <title>

</head>
</html>