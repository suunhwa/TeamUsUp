<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>TeamUsUp - Home</title>
        <link rel="stylesheet" href="css/home.css"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <style>
            html {
            background: linear-gradient(rgba(190, 189, 189, 0.5),rgba(190, 189, 189, 0.5)), url(images/Murdoch-University.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
         }
           
        </style>
    </head> 
    <?php include "header.php"?>
    <?php include "footer.php"?>
     <body>
        <form method="GET" action="result.php">
            <h2>Welcome to TeamUsUp!</h2>
            <div class="form-box" >
                <h3>Search Student by Name</h3>
                <input type="text" class="search-field" placeholder="Type to search..." name="searchTxt" id="searchTxt">
                <input type="submit" class ="search-btn" name="SearchStudent" id="SearchStudent" value="Search">
                <?php 
      /* echo 'im here 2';
        echo 'my role is '. $_SESSION['role'];
        echo 'my student no is -' . $_SESSION['userSNo'] . '-  asda  asdad  ';*/?>
            </div>
        </form> 
    </body>
</html>
