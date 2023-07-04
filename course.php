<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Course</title>
    <link rel="stylesheet" href="/css/course.css"> 
    
</head> 
    <body>
    <header><?php include "header.php"?></header>
        <section class="margin">
        <div class="container">
            <h2>Available Courses</h2> 
            <div class="table-box">
                <table class="course-table">
                    <thead>
                        <td>Course Name</td>
                        <td>Course Major</td>
                        <td></td>    
                    </thead>
                    <tbody>
                        <?php displayCourse()?>
                    </tbody> 
                </table>
            </div> 
        </div>
            <?php
                if(isset($_POST['courseID']))
                {
                    displaySelCourse($_POST['courseID']);
                }
            ?>
        <div class="container">
            
        </div>
        </section> 
        <footer><?php include "footer.php"?></footer>
    </body>
</html>

<?php
/*
function displayCourse()
{
    include "conn.php";
    $count = 0;
    $sql = "SELECT C.CourseID, C.Name, C.Desc
        FROM Course C
        WHERE C.Status = 'Available'";
    $result = $conn->query($sql);

    if($result->num_rows > 0)
    {
        echo ' <table><tr><tr>
        <td>Offering</td>
        <td>Details</td></tr>';
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {    
            $sql1 = "SELECT CU.UnitID
                FROM Course C, CourseUnit CU
                WHERE C.CourseID = CU.CourseID
                AND CU.CourseID = ".$row['CourseID']."";
                $result1 = $conn->query($sql1);
            
            echo '<tr>
                    <td><form class="enrol" method="POST" action="unit.php">'.$row['Name'].'</td>
                    <td>'.$row['Desc'].'</td><tr>';
                    while($row1 = $result1->fetch_array(MYSQLI_ASSOC))
                    {
                        echo '<td><input type="submit" name="UnitID" class="btn btn-info" value="'. $row1['UnitID'] . '">
                        </td>';
                        $count ++;
                    }
                    echo '</tr>';

        }  
        echo "</tr></table>"; //Close the table in HTML
    }
}
*/

    function displayCourse()
    {
        include "conn.php"; 
        $sql = "SELECT C.CourseID, C.Name, C.Desc
                FROM Course C
                WHERE C.Status = 'Available'";
        $result = $conn->query($sql);

        if($result->num_rows > 0)
        { 
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {    
                echo '<tr>
                        <form method="POST">
                            <td>'.$row['Name'].'</td>
                            <td>'.$row['Desc'].'</td>
                            <td class="btn"><input type="hidden" name="courseID" value="'.$row['CourseID'].'"><input type="submit" name="selCourse" class="btn btn-info" value="Select"></td>
                        </form>
                        </tr>';  

            }   
        }
        $conn->close(); //Make sure to close out the database connection 
    }

    function displaySelCourse($courseID)
    {
        include "conn.php"; 
        $sql = "SELECT C.CourseID, C.Name, C.Desc
                FROM Course C
                WHERE C.CourseID='$courseID'";
        $result = $conn->query($sql);

        if($result->num_rows == 1)
        { 
            $resultsCourse = $result->fetch_array(MYSQLI_ASSOC);
            echo '
            <div class="container">
            <h3>'.$resultsCourse['Name'].'</h3> <h4> &nbsp;'.$resultsCourse['Desc'].'</h4>
            <div class="table-box">
                <table class="course-table">
                    <thead>  
                        <td>Unit ID</td>
                        <td>Unit Name</td>    
                        <td>Description</td>    
                        <td>Credit</td>    
                        <td></td>    
                     </thead>
                     <tbody>';
            $sqlCourseUnits = " SELECT U.UnitID, U.Name AS UnitName, U.Desc AS UnitDesc, U.Credit
                                FROM courseunit CU INNER JOIN unit U ON CU.UnitID=U.UnitID
                                WHERE CU.CourseID = '$courseID' AND U.Status='Available';";

            $resultCourseUnits = $conn->query($sqlCourseUnits);
            if($resultCourseUnits->num_rows > 0)
            { 
                while($row = $resultCourseUnits->fetch_array(MYSQLI_ASSOC))
                {    
                    echo '<tr>
                            <form method="GET" action="unit.php">
                                <td><input type="text" name="unitID" value="'.$row['UnitID'].'" readonly></td>
                                <td>'.$row['UnitName'].'</td>
                                <td>'.$row['UnitDesc'].'</td>
                                <td class="credit">'.$row['Credit'].'</td>
                                <td class="btn"><input type="submit" name="selUnit" class="btn btn-info" value="Select"></td>
                            </form>
                            </tr>';   
                }   
            } 
            echo'    </tbody>
                </table>
            </div>';   
        }
        else
        {
            echo 'Select a Course to display more information.';
        }
        $conn->close(); //Make sure to close out the database connection  
    }













?>