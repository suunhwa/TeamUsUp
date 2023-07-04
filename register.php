<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <title>TeamUsUp - Registration</title>
        <link rel="stylesheet" href="css/register.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/footer.css">
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
    </head>
    
   <body>
   <header><?php include "header.php"?></header>
        <section class="margin">
        <div class="box">
        <div class="container">
            <img src="images/murdoch-logo.png" alt="black" class="black">
            <div class="title"><p>Register Here</p></div>
            <form method="post" action="register.php">
            <?php include('errors.php'); ?>
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">First Name</span>
                        <input type="text" placeholder="Enter your first name" name = "firstName" value="<?php echo $firstName; ?>" required>
                    </div>

                    <div class="input-box">
                        <span class="details">Last Name</span>
                        <input type="text" placeholder="Enter your last name" name = "lastName" value="<?php echo $lastName; ?>" required>
                    </div>

                    <div class="input-box">
                        <span class="details">StudentID</span>
                        <input type="text" placeholder="Enter your StudentID" name = "studentID" value="<?php echo $studentID; ?>" required>
                    </div>

                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" placeholder="Enter your password" name = "password" required>
                    </div>

                    <div class="input-box">
                        <span class="details">Email</span>
                        <input type="email" placeholder="Enter your email" name = "email" value="<?php echo $email; ?>"required>
                    </div>

                    <div class="input-box">
                        <span class="details">Date of Birth</span>
                        <input type="date" placeholder="Enter your date of birth" name = "dob" value="<?php echo $dob; ?>"required>
                    </div>


                    <div class="input-box">
                        <span class="details">Course</span>

                        <select name="course" id="course">
                            <option value="default" default>--No selection--</option>
                            <?php 
                                fillCourseOptions();
                            ?>
                        </select>       
                        <!--<input type="text" placeholder="Enter your last name" name = "lastName" value="<?php echo $lastName; ?>" required>-->
                    </div>
                    <div class="input-box">
                        <span class="details">Enrolment Date</span>
                        <input type="date" placeholder="Enter your enrolment date" name = "enrolDate" value="<?php echo $enrolDate; ?>"required>
                    </div>

                </div>
                <div class="gender-details">
                    <input type="radio" name="gender" id="dot-1" value="M" checked>
                    <input type="radio" name="gender" id="dot-2" value="F">
                    <span class="gender-title">Gender</span>
                    <div class="category">
                        <label for="dot-1">
                            <span class="dot one"></span>
                            <span class="gender">Male</span>
                        </label>
                        <label for="dot-2">
                            <span class="dot two"></span>
                            <span class="gender">Female</span>
                        </label>
                    </div>
                </div> 
                <div class="study-details">
                    <input type="radio" name="study" id="dot-3" value="FT" checked>
                    <input type="radio" name="study" id="dot-4" value="PT">
                    <span class="study-title">Mode of Study</span>
                    <div class="category">
                        <label for="dot-3">
                            <span class="dot three"></span>
                            <span class="study">Full Time</span>
                        </label>
                        <label for="dot-4">
                            <span class="dot four"></span>
                            <span class="study">Part Time</span>
                        </label>
                    </div>
                </div> 
                <div class="button">
                    <input type="submit" value="Register" name = "Register">
                </div>
            </form>
        </div>
        </div>
    </section>
    <footer><?php include "footer.php"?></footer>
    </body>
</html>


<?php
    function fillCourseOptions()
    { 
        include "conn.php";

        // Check connection
        if ($conn->connect_error) 
        {
            die("Connection failed: " . $conn->connect_error);
        }
        //echo "Connected successfully";

        $sql = "SELECT C.CourseID, C.Name
                FROM course C
                WHERE Status='Available'
                ";
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        {
            //echo "Im course here 1"; 
            echo "<table>"; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                echo "<option value='". $row['CourseID'] . "'>" . 
                $row['Name'] . "</option>"; 
            }  
            echo "</table>"; //Close the table in HTML
        }
        else
        {
            //echo "Im course here 2"; 
            echo 'Error';
        }  
        $conn->close(); //Make sure to close out the database connection
    }
?>