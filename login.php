<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>TeamUsUp - Login</title>
        <link rel="stylesheet" href="css/login.css" >  
        <link rel="stylesheet" href="css/header.css" >
        <link rel="stylesheet" href="css/footer.css" >
        <script src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
            section {
            background: linear-gradient(rgba(190, 189, 189, 0.5),rgba(190, 189, 189, 0.5)), url(images/Murdoch-University.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
      
         }
        </style>

       <!--<link rel="stylesheet" href="./media.css" media="(min-width:320px) and (max-width:1024px)">-->
    </head>

    <body>
    <header><?php include "header.php"?></header>
       <section>
        <div class ="login-box">
        <div class="login-form">
            <img src="images/murdoch black logo.png" alt="black" class="black">
            <FORM action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="int-area">
                    <input type="text" name="email" id="email"
                    autocomplete="off" required>
                    <label for="email">Email</label>
                </div>
                <div class="int-area">
                    <input type="password" name="pw" id="pw"
                    autocomplete="off" required>
                    <label for="pw">PASSWORD</label>
                </div>
                <div class="btn-area">
                    <!-- NEED PUT VALIDATION WARNING MESSAGES -->
                    <button id="btn"
                    type="submit">LOGIN</button>
                </div>
            </FORM>
          

            <div class="register-caption">
                <p>Not Registered?&nbsp;<a href="register.php">Register</a></p>
                </div>

            <div class="info-caption">
                <h2>Murdoch username in the form</h2>
                <h3>student_number@student.murdoch.edu.au</h3>
                <h4>or staff_number@admin.murdoch.edu.au</h4>
            </div>
        </div>
        </div>
         <!--
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
HEAD
        -->
        
         
         
    
        </section>
        <?php include "footer.php"?>
    </body>
</html>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        loginUser($_POST['email'], $_POST['pw']);
    }

    function loginUser($email, $pw)
    {
        include "conn.php";
        $pw = md5($pw);
        $sql = "SELECT A.AccountID, A.FirstName, A.LastName, A.Role, A.Status
                FROM account A 
                WHERE A.Email='". $email ."' AND A.Password='". $pw ."'"; //You don't need a ; like you do in SQL
        
        

        //echo $sql;
        $result = $conn->query($sql);
        //echo "Query has been executed";  
        if($result->num_rows == 1)
        {  
            echo "Im here 1 "; 
            $searchResult =  $result->fetch_array(MYSQLI_ASSOC);  
            if (!isset($_SESSION))
            {
                session_start(); 
            }

            if($searchResult['Role']=='Student' && $searchResult['Status']=='Active')
            {   
                //if account is a student
                $sqlStudent = "SELECT S.StudNo 
                            FROM teamusup.account A
                            INNER JOIN student S ON A.AccountID=S.AccountID
                            WHERE A.AccountID ='" . $searchResult['AccountID'] . "'";
                $studResult = $conn->query($sqlStudent);            
                if($studResult->num_rows == 1)
                {   
                    $searchResultStud =  $studResult->fetch_array(MYSQLI_ASSOC);  
                    $_SESSION['loginStatus'] = true;
                    $_SESSION['fName'] = $searchResult['FirstName'];  
                    $_SESSION['lName'] = $searchResult['LastName'];  
                    $_SESSION['userID'] = $searchResult['AccountID'];  
                    $_SESSION['role'] = $searchResult['Role'];
                    $_SESSION['userSNo'] = $searchResultStud['StudNo'];    

                    $conn->close(); //Make sure to close out the database connection 
                    //header("location: profile.php?fName=".$_SESSION['fName']."&lName=".$_SESSION['lName']);
                    echo '<meta http-equiv="refresh" content="0; URL=profile.php?fName='.$_SESSION['fName']. '&lName=' . $_SESSION['lName'] . '">';  
                    exit();
                } 
            }
            else if($searchResult['Role']=='Admin')
            {
                 
                $_SESSION['loginStatus'] = true;
                $_SESSION['fName'] = $searchResult['FirstName'];  
                $_SESSION['lName'] = $searchResult['LastName'];  
                $_SESSION['userID'] = $searchResult['AccountID'];  
                $_SESSION['role'] = $searchResult['Role'];   
                $conn->close();  
                echo '<meta http-equiv="refresh" content="0; URL=/admin/notificationAdmin.php?pageNoBy=1&pageNo=1">';  
                exit();
            }

            
        }
        else
        {
            //echo "Im here 2"; 
            $searchResult = "0 Results"; 
        }
        $conn->close(); //Make sure to close out the database connection 
    } 
?>