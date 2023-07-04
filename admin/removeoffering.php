<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Offering Management</title>
    <link rel="stylesheet" href="/css/removeoffering.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<?php include "admin.php";
    if ($_SESSION['role'] == 'Admin')
    {

    }
    else
    {
        echo '<meta http-equiv="refresh" content="0; URL=home.php">';  
    } 
?>
<body>
    <div class="container">
        <h2>Remove Offering</h2>
        <form>
            <li>
                <p>Current Semester</p>
                <select id="offering">
                    <option value="default" default>--No Selection--</option>
                    <option value="o1">TJA</option>
                    <option value="o2">TMA</option>
                    <option value="o3">TSA</option>                
                </select>
                <select id="year">
                    <option value="default" default>--No Selection--</option>
                    <option value="y1">2021</option>
                    <option value="y2">2022</option>
                    <option value="y3">2023</option>                
                </select>
            </li>
            <div class="unit-box">
                        <div class="box">
                            <div class="details">
                            <span><input type="text" class="uname" value="ICT111A"></span>
                        </div>
                        <div class="close-icon"><i class="fas fa-times"></i></div>
                        </div>
                        <div class="box">
                            <div class="details">
                            <span><input type="text" class="uname" value="ICT222A"></span>
                        </div>
                        <div class="close-icon"><i class="fas fa-times"></i></div>
                        </div>
                        <div class="box">
                            <div class="details">
                            <span><input type="text" class="uname" value="ICT333A"></span>
                        </div>
                        <div class="close-icon"><i class="fas fa-times"></i></div>
                        </div>
                        <div class="box">
                            <div class="details">
                            <span><input type="text" class="uname" value="ICT444A"></span>
                        </div>
                        <div class="close-icon"><i class="fas fa-times"></i></div>
                        </div>
                        <div class="box">
                            <div class="details">
                            <span><input type="text" class="uname" value="ICT555A"></span>
                        </div>
                        <div class="close-icon"><i class="fas fa-times"></i></div>
                        </div>
                        <div class="box">
                            <div class="details">
                            <span><input type="text" class="uname" value="ICT666A"></span>
                        </div>
                        <div class="close-icon"><i class="fas fa-times"></i></div>
                        </div>
                           
            </div>

            

 </div>
            <div class="btn-save">
                <input type="submit" name="save" id="save" value="Save">
            </div>
        </form>
    </div>
    

</body>
</html>