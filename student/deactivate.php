<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Deactivate Account</title>
    <link rel="stylesheet" href="/css/deactivate.css"> 
    
</head> 
<?php include "sidebar.php"?>

<body>
    <div class="container">
        <h2>Deactivate Account</h2>

        <h3>Are you sure you want to deactivate your account?</h3>

        <form method="POST">        
            <div class="desc">
                    <p>Reason for Deactivation</p>
                    <textarea  name="deactMsg" id="deactMsg" cols="50" rows="10" placeholder="State the reason for requesting the deactivation of the account..."></textarea>
            </div> 
            <?php 
                if(isset($_POST['deactivate']))
                {
                    if(!empty($_POST['deactMsg']))
                    {
                        createNotification($_SESSION['userID'],$_POST['deactMsg']);
                    }
                    else
                    {
                        echo '<input type="text" class="errMsg" name="errorMsg" id="errorMsg" value="Please enter the reason for the deactivation of your account." readonly>';
                    }
                }
            ?> 
            <div class="btn">
                <div class="btn-deactivate">
                    <input type="submit" name="deactivate" id="deactivate" value="Deactivate">
                </div>
                <div class="btn-cancel">
                    <input type="submit" name="cancel" id="cancel" value="Cancel">
                </div>
            </div>
        </form>
    </div>
</body>
</html>

<?php
    if(isset($_POST['cancel']))
    {
        echo '<meta http-equiv="refresh" content="0; URL=/profile.php?fName=' . $_SESSION['fName'] . "&lName=" . $_SESSION['lName'] . '">';
    }

    function createNotification($byAccID, $deactMsg)
    {
        //create deactivation notification
        include "../conn.php";  
        $sql = "INSERT INTO notification (RequesterID, Action, Message, Status)
                VALUES (?, ?, ?, ?)";
         
        $stmt = $conn->prepare($sql);
         
        //echo "im here 1";
        $deactAction = "Deactivate Account";
        $deactStatus = "Pending";

        $stmt->bind_param("isss", $byAccID, $deactAction, $deactMsg, $deactStatus); 
        //echo "im here 2";
        
        $stmt->execute();

        echo "New records created successfully";

        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection
        
        echo '<meta http-equiv="refresh" content="0; URL=/student/notificationStudent.php">';
    }

?>