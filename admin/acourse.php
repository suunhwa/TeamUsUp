<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Course Management</title>
    <link rel="stylesheet" href="/css/acourse.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

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
        <h2>Course Management</h2>
        <div class="table-box">
            <table>
                <tbody>
                    <tr>
                        <?php displayCourse() ?>
                    </tr>            
                </tbody>
            </table>
            
            <div class="btn-create">
                <input type="button" name="create" id="create" onclick="location.href='a.createcourse.php'" value="Create Course">
            </div>
        </div>
    </div>
    

</body>
</html>
<?php


function displayCourse()
{
    include "../conn.php";
    $sql = "SELECT CourseID, C.Name AS CourseName FROM Course C";
            $result = $conn->query($sql);
            echo '
            <table class="table-box">
            <thead>
                    <tr>
                        <td>Course</td>
                        <td>Edit</td>
                    </tr>
                </thead>
            <tbody>'; 
    if($result->num_rows > 0)
    {
        echo '<table><tr>';
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {   
            echo '<tr><td><form method="POST" action="a.editcourse.php">' .  $row['CourseName'].'</td>
                    <td><input type="hidden" name="courseID" value="' . $row['CourseID'] . '">
                    <input type="submit" name="course" class="btn btn-info" value="Edit Course"></td></form>';  
            


            //<td><button name="edit" value="' . $row['CourseID'] . '" class="btn btn-grp" >Edit Course</button></td>'; 
        }  
        echo "</tbody></table>"; //Close the table in HTML
    }
    else
    {
        echo '<br> No available courses';
    }  
    $conn->close();
}

?>