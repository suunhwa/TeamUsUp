<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Student Dashboard</title>
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<?php include "../header.php"?>
<?php include "../footer.php"?>
<body>

    <input type="checkbox" id="menu">
    <nav>
        <label for="menu" class="menu-bar">
            <i class="fa fa-bars"></i>
    </nav>
    <div class="sidebar">
        <h5>Student <br>Dashboard</h5>
        <a href="notificationStudent.php"><i class="fas fa-bell"></i><span>Notifications</span></a>
        <a href="requestStudent.php"><i class="fas fa-envelope-open-text"></i><span>Requests</span></a>
        <a href="group.php"><i class="fas fa-users"></i><span>Manage Groups</span></a>
        <a href="enrol.php"><i class="fas fa-book-open"></i><span>Enrol in Units</span></a>
        <a href="deactivate.php"><i class="fas fa-user-lock"></i><span>Deactivate Account</span></a>
    </div>

</body>
</html>