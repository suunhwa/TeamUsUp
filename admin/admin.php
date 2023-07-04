<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Administrator Dashboard</title>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<?php include "../header.php"?>
<?php include "../footer.php"?>
<?php 
    if ($_SESSION['role'] == 'Admin')
    {
        //echo 'im here 1';
        //echo $_SESSION['role'];
        //echo $_SESSION['userID'];
    }
    else
    {
        //echo 'im here 2';
        //echo 'my role is '. $_SESSION['role'];
        //echo '<meta http-equiv="refresh" content="0; URL=/home.php">';  
    } 
?>
<body>
<div class="container">
    <div class="sidebar">
        <h5>Administrator<br> Dashboard</h5>
        <a href="adminacc.php"><i class="fas fa-user-circle"></i><span>New Profile</span></a>
        <a href="notificationAdmin.php?pageNoBy=1&pageNo=1"><i class="fas fa-bell"></i><span>Requests /  Notifications</span></a>
        <a href="offering.php"><i class="fas fa-archive"></i><span>Offering Management</span></a>
        <a href="acourse.php"><i class="fas fa-list-ul"></i><span>Course Management</span></a>
        <a href="aunit.php"><i class="fas fa-paperclip"></i><span>Unit Management</span></a>
        <a href="assignment.php"><i class="fas fa-book-open"></i><span>Assignment Management</span></a>
        
        
    </div>
</div>
    
</body>
</html>