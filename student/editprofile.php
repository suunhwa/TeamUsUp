<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Edit Profile</title>
    <link rel="stylesheet" href="/css/editprofile.css"> 
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<?php include "sidebar.php"?>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form method="POST">
            
            <!--<div class="name-box">
                <div class="fname-box">
                    <span class="details">First Name</span> 
                    <input class="fname" type="text" name="" placeholder="">
                </div>
            
                <div class="lname-box">
                    <span class="details">Last Name</span> 
                    <input class="lname" type="text" name="" placeholder="">
                </div>
            </div>
            <div class="pw-box">
                <span class="details">Change Password</span> 
                <input class="pw" type="text" name="" placeholder="">
            </div>

            <div class="email-box">
                <span class="details">Change Email</span> 
                <input class="email" type="text" name="" placeholder="">
            </div>-->
            <div class="about-box">
                <p>About</p> 
                <textarea cols="50" name='biography' id="biography" rows="10" placeholder="Enter biography..."><?php echo $_POST['userBio']?></textarea>
            </div>
        
            <div class="btn-save">
                <input type="submit" name="save" id="save" value="Submit">
            </div>

            <!--<div class="btn-deactivate">
                 <a class="btn deactivate" href="deactivate.php">Deactivate Account</a>
            </div>-->
        </form>
    </div> 
</body>
</html>


<?php
    if (isset($_POST['save']))
    {
        updateBio();
        //echo 'my student no is -' . $_SESSION['userSNo'] . '- my first name is -' . $_SESSION['fName'] . '- my last name is -' . $_SESSION['lName'] . '-  the new bio is -'. htmlspecialchars($_POST['biography']) . '-  end.';
        
    }

    function updateBio()
    { 
        include "../conn.php"; 

        $newBio = htmlspecialchars($_POST['biography']);
        $sql = 'UPDATE student
                SET Biography="'.$newBio.'"
                WHERE StudNo="'.$_SESSION['userSNo'].'"';
         
        if(mysqli_query($conn, $sql))
        {
            echo "Records updated successfully.";
            $conn->close(); //Make sure to close out the database connection
            echo '<meta http-equiv="refresh" content="0; URL=/profile.php?fName=' . $_SESSION['fName'] . "&lName=" . $_SESSION['lName'] . '">';
        } 
        else
        {
            echo "ERROR: Could not able to execute " . $sql. " " . mysqli_error($conn); 
        }
            $conn->close(); //Make sure to close out the database connection
    }

?>