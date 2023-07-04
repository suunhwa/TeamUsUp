<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  </head>
  <?php
    // Initialize the session
    if (!isset($_SESSION))
    {
        session_start(); 
    }
    
    // Check if the user is logged in, if not then 
    if(!isset($_SESSION["loginStatus"]) || $_SESSION["loginStatus"] !== true)
    {
        //echo 'User is not logged in';
        $_SESSION['loginStatus'] = false;
		$_SESSION['fName'] = null;
		$_SESSION['lName'] = null;
		$_SESSION['userID'] = null;
        $_SESSION['role'] = null;
		$_SESSION['userSNo'] = null;
    }
    ?>
  <body>
  <header>
  <div class="logo-box">
    <img src="/images/Murdoch_port_Redbox-300x300.png" alt="logo" class="logo">
    <h1 class="logo">TeamUsUp</h1>
  </div>
  <input type="checkbox" id="nav-toggle" class="nav-toggle">
    <nav>
        <div class="nav-items">
        <ul>
            <li><a href="/home.php">Home</a></li>
            <li><a href="/about.php">About Us</a></li>
            <?php // Check if the user is logged in, if yes create profile list item to link to user's profile
            if(($_SESSION["loginStatus"] == true) && ($_SESSION["role"] == 'Student'))
            {
                echo '<li><a href="/profile.php?fName=' . $_SESSION['fName'] . '&lName=' . $_SESSION['lName']  . '">' . $_SESSION['fName'] . ' ' . $_SESSION['lName'] . '</a></li>';
            }
            ?> 
             
            <li><a href="/course.php">Course</a></li>
            <li><a href="/unit.php">Unit</a></li>
            <?php // Check if the user is logged in, if yes create profile list item to link to user's profile
            if(($_SESSION["loginStatus"] == true) && ($_SESSION["role"] == 'Student'))
            {
                echo '<li><a href="/student/notificationStudent.php">Account</a></li>';
            }
            else if(($_SESSION["loginStatus"] == true) && ($_SESSION["role"] == 'Admin'))
            { 
                echo '<li><a href="/admin/notificationAdmin.php">Admin Account</a></li>';
            }
            ?> 
            </ul> 
        </div>
        
        <form class="search" method="GET" action="/result.php"> 
            <input type="text" class="search-data" placeholder="Type to search..." name="searchTxt" id="searchTxt">
            <input type="submit" class ="inputSearch" name="SearchStudent" id="SearchStudent" values="Search">
        </form>
 
            <?php // Check if the user is logged in, if true display logout button. if false display login button
            if($_SESSION["loginStatus"] == true)
            {
                echo '<form action="" method="POST">
                        <input  class="login" type="submit" name="logout" value="Logout"> 
                    </form>';
            }
            else
            {
                echo '<a class="login" href="/login.php"><i class="fas fa-user-circle"></i>&nbsp;Login</a>';
            }
            ?>    
        </nav>
        <label for="nav-toggle" class="nav-toggle-label">
            <span></span>
        </label>
    </header>

  </body>
</html>

<?php
    if (isset($_POST['logout']))
    {
        // Initialize the session
        session_start();
        
        // Unset all of the session variables
        $_SESSION = array();
        
        // Destroy the session.
        session_destroy();
        
        // Redirect to login page
        header("location: /home.php");
        exit;
    } 
?>