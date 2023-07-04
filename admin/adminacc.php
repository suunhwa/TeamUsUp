<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Create Adiminstrator Profile</title>
    <link rel="stylesheet" href="/css/adminacc.css">
</head>
<?php include "admin.php";
    if ($_SESSION['role'] == 'Admin')
    {

    }
    else
    {
        echo '<meta http-equiv="refresh" content="0; URL=/home.php">';  
    } 
?>
<body>
    <div class="container">
        <h2>Create New Administrator Account</h2>
        <form method = "POST" class="acc">            
            <div class="input-box">
                        <span class="details">First Name</span>
                        <input type="text" id="fname" placeholder="Enter your first name" name = "firstName" value="<?php echo $firstName; ?>" required>
                    </div>

                    <div class="input-box">
                        <span class="details">Last Name</span>
                        <input type="text" placeholder="Enter your last name" name = "lastName" value="<?php echo $lastName; ?>" required>
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
            <div class="btn-save">
                <input type="submit" name="save" id="save" value="Save">
            </div>
        </form>
    </div>
    
</body>
</html>

<?php

if (isset($_POST['save'])) 
    {
        createAdmin();      
    }

    function createAdmin()
    {
        include "../conn.php"; 
        $fname = $_POST['firstName'];
        $lname = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $password = md5($password);//encrypt the password before saving in the database
        $sql = "INSERT INTO account (firstName , lastName, dob, password, gender, role, email) 
                VALUES('$fname', '$lname','$dob','$password', '$gender', 'Admin', '$email')";
        mysqli_query($conn, $sql);
        $conn->close();
    }
    ?>

