<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Edit Course</title>
    <link rel="stylesheet" href="/css/a.editcourse.css">
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
    <h2>Edit Course</h2>
        <form class="course"method="POST">
        <?php fillDetails($_POST['courseID']);?>  
        <li>
            <?php
            include "../conn.php";
            $result = mysqli_query($conn, "SELECT * FROM Unit");
            ?>
            <label class="details" for="units">Select Units</label><br>
            <select name="Units[]" id="units" size="10" multiple="multiple">
            <?php
            while($row = mysqli_fetch_array($result)) {
            ?>
            <option value="<?=$row["UnitID"];?>"><?=$row["UnitID"];?></option>
            <?php
            }
            ?>
            </select>
            </li>
            <div class="btn-save">
            <input type="submit" name="save" id="save" value="Save">
        </div>
        <div class="btn-delete">
            <input type="submit" name="delete" id="delete"  value="Delete Course">
        </div>
        </form>
    </div>
</body>
</html>

<?php 
function fillDetails($courseID)
{
    include "../conn.php"; 
    $sql = "SELECT C.Name, C.Desc, C.Status
            FROM Course C
            WHERE CourseID = '$courseID'";
    $result = $conn->query($sql);
    if($result->num_rows > 0)
    {
        $row = $result->fetch_array(MYSQLI_ASSOC);

            echo ' 
            <li>
                <label class="details" for="name">Name</label>
                <input type="hidden" name="CourseID" value="'.$courseID.'">
                <input type="text" name="courseName" required value="'.$row['Name'].'"> 
            </li>
        
            <div class="status">
                <li>
                <label class="details" for="status" >Status</label>
                    <label class="switch">';
                    if($row['Status'] == 'Available')
                    {
                        echo '<input type="checkbox" name ="status" checked>';
                    }
                    else{
                        echo '<input type="checkbox" name ="status" >';
                    }
                        echo '<span class="slider"></span>
                    </label>
                </li>
            </div>
            
            <li>
                <label class="details" for="desc">Description</label>
                <textarea name="desc" >'.$row['Desc'].'</textarea>
            </li>
            ';

    }   
}
if (isset($_POST['delete'])) 
    {
        deleteCourse($_POST['CourseID']);      
    }
function deleteCourse($courseID)
{
    include "../conn.php"; 
    $sql1 = "DELETE FROM course
            WHERE CourseID = '$courseID'";
        mysqli_query($conn, $sql1);
        $conn->close();
        echo '<meta http-equiv="refresh" content="0; URL=acourse.php">';
}

if(isset($_POST['save']))
{
    updateCourse($_POST['CourseID']);
}
function updateCourse($courseID)
{
    include "../conn.php"; 
    $status = "Hidden";
    $description = $_POST['desc'];
    $name = $_POST['courseName'];
    if(isset($_POST['status']))
    {
        $status = "Available";
    }

    $sql = "UPDATE COURSE C
            SET c.name = '$name', c.desc = '$description', c.status = '$status'
            WHERE CourseID = '$courseID'";
            mysqli_query($conn, $sql);
    $choices= $_POST['Units'];
    if(isset($choices))
    {
        $sql2 = "DELETE FROM COURSEUNIT
                WHERE CourseID = '$courseID'";
                mysqli_query($conn, $sql2);
        foreach($choices as $value)
        {
        $sql3 = "INSERT INTO COURSEUNIT (COURSEID, UNITID)
                VALUES ('$courseID', '$value')";
                mysqli_query($conn, $sql3);
        }
    }
    else
    {
        $sql2 = "DELETE FROM COURSEUNIT
                WHERE CourseID = '$courseID'";
                mysqli_query($conn, $sql2);
    }
    $conn->close();
    echo '<meta http-equiv="refresh" content="0; URL=acourse.php">';
}
?>