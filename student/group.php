<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Manage Groups</title>
    <link rel="stylesheet" href="/css/group.css"> 
    
</head> 
<?php include "sidebar.php"?>
<?php  
    if( (date('m')=='01') || (date('m')=='02') || (date('m')=='03') || (date('m')=='04') )
    {
        $curSem = "TJA" . date('y');
    }
    else if ( (date('m')=='05') || (date('m')=='06') || (date('m')=='07') || (date('m')=='08') )
    {
        $curSem = "TMA" . date('y');
    }
    else
    {
        $curSem = "TSA" . date('y');
    }


?>
<body>
    <div class="container">
        <h2>Manage Groups</h2>
            <div class="current">
                <p>Current Semester Units</p>
                <!--<input type="button" name="cunit" class="btn btn-info" value="ICT111">
                <input type="button" name="cunit" class="btn btn-info" value="ICT222">
                <input type="button" name="cunit" class="btn btn-info" value="ICT333">
                <input type="button" name="cunit" class="btn btn-info" value="ICT444">-->
                <?php
                    displayCurrUnit($_SESSION['userSNo'], $curSem); 
                ?>
            </div>
            <div class="current">
                <p>Past Semester Units</p> 
                <?php
                    displayPastUnit($_SESSION['userSNo'], $curSem); 
                ?>
            </div>
            <!--
            <div class="btn-create">
                <input type="button" name="create" id="create" onclick="location.href='creategroup.php'" value="Create Group">
            </div>
            <div class="btn-edit">
                <input type="button" name="edit" id="edit" onclick="location.href='editgroup.php'" value="Edit Groups">
            </div>
            -->
    </div> 
</body>
</html>

<?php  
    function displayCurrUnit($studNo, $curSem)
    { 
        include "../conn.php";
        $count = 1;
 
        $sql = "SELECT U.UnitID, O.OffID
                FROM student S
                INNER JOIN (enrolment E INNER JOIN (offering O INNER JOIN unit U ON U.UnitID = O.UnitID) ON E.OffID = O.OffID)
                ON S.StudNo = E.StudNo
                WHERE S.StudNo = '". $studNo ."' AND O.Semester = '" . $curSem . "'";
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        {
            //echo "Im Review here 1"; 
            echo '
            <form method="POST" action="manageGroup.php"><table><tr>'; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {    
                if($count > 4)
                {
                    echo '</tr><tr>';
                    $count = 1;
                }
                echo '<td>' .  
                     '<input type="hidden" name="offID" value="' . $row['OffID'] . '">' .
                     '<input type="submit" name="unit" class="btn btn-info" value="'. $row['UnitID'] . '">' . 
                    '</td>';  
                $count += 1;
            }  
            echo "</tr></table></form>"; //Close the table in HTML
        }
        else
        {
            //echo "Im Review here 2"; 
            echo '<br> Not Enrolled in any unit this semester';
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    function displayPastUnit($studNo, $curSem)
    { 
        include "../conn.php";
        $count = 1;
        $sql = "SELECT U.UnitID, O.OffID
                FROM student S
                INNER JOIN (enrolment E INNER JOIN (offering O INNER JOIN unit U ON U.UnitID = O.UnitID) ON E.OffID = O.OffID)
                ON S.StudNo = E.StudNo
                WHERE S.StudNo = '". $studNo ."' AND O.Semester <> '" . $curSem . "'";
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        { 
            //echo "Im Review here 1"; 
            echo "<table><tr>"; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {    
                if($count > 4)
                {
                    echo '</tr><tr>';
                    $count = 1;
                }
                echo '<td><form method="POST" action="manageGroup.php">' .  
                     '<input type="hidden" name="offID" value="' . $row['OffID'] . '">' .
                     '<input type="submit" name="unit" class="btn btn-info" value="'. $row['UnitID'] . '">' . 
                    '</form></td>';  
                $count += 1;
            }  
            echo "</tr></table>"; //Close the table in HTML
        }
        else
        {
            //echo "Im Review here 2"; 
            echo '<br> Not Enrolled in any unit this semester';
        }  
        $conn->close(); //Make sure to close out the database connection
    }
?>