<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Unit Enrolment</title>
    <link rel="stylesheet" href="/css/enrol.css"> 
    
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
          <h2>Unit Enrolment Management</h2>
        <div class="enrol">
      
            <!--
            <h1>Unit Enrolment Management</h1>
            <form class="enrol" method="POST">
                <div class="current">
                    <p>Current Semester Units</p>
                    <input type="button" name="cunit"class="btn btn-info" value="ICT111">
                    <input type="button" name="cunit"class="btn btn-info" value="ICT222">
                    <input type="button" name="cunit"class="btn btn-info" value="ICT333">
                    <label class="enr" for="addunit"></label>
                    <select id="addunit">
                        <option selected>Add Units...</option>
                        <option value="db">ICT285</option>
                        <option value="cos">ICT287</option>  
                        <option value="ea">ICT301</option>
                        <option value="itp">ICT302</option>
                        <option value="sa">ICT373</option> 
                    </select>
                </div>
                <div class="past">
                    <p>Past Semester(s) Units</p>
                    <input type="button" name="cunit"class="btn btn-info" value="ICT444">
                    <input type="button" name="cunit"class="btn btn-info" value="ICT555">
                    <input type="button" name="cunit"class="btn btn-info" value="ICT666">
                    <input type="button" name="cunit"class="btn btn-info" value="ICT777">
                    <input type="button" name="cunit"class="btn btn-info" value="ICT888">
                    <input type="button" name="cunit"class="btn btn-info" value="ICT999">
                </div>
            </form>
            -->
            
                <div class="current">
                    <p>Current Semester Units</p>
                    <?php
                        displayCurrUnit($_SESSION['userSNo'], $curSem); 
                    ?> 
                </div>
                <div class="past">
                    <p>Past Semester(s) Units</p>
                    <?php
                        displayPastUnit($_SESSION['userSNo'], $curSem); 
                    ?>
                </div> 
            <div class="details">
                <label class="enr" for="addunit">Available Units to Enrol</label>
                <form method="POST">
                    <select name="addunit" id="addunit" onchange="this.form.submit()">
                        <option value="default" default>--No selection--</option>
                        <?php 
                            fillList($_SESSION['userSNo']);
                        ?> 
                    </select>
                </form>
                <?php 
                if(isset($_POST['addunit']))
                {
                    displayOffDetails($_POST['addunit']);
                }
                if(isset($_POST['selectEnrolledUnit']))
                {
                    displaySelectedUnit($_POST['selectOffID'], $_SESSION['userSNo']);
                }
                ?>                                                
            </div> 
        </div> 
    </div> 
</body>
</html>
<?php

    if(isset($_POST['cancel']))
    { 
        echo '<meta http-equiv="refresh" content="0; URL=enrol.php">';
    } 
    if(isset($_POST['enrol']))
    { 
        enrolInUnit($_POST['enrolOffID'], $_POST['status'], $_POST['grade']);
        echo '<meta http-equiv="refresh" content="0; URL=enrol.php">';
    } 
    if(isset($_POST['edit']))
    { 
        editEnrolledUnit($_POST['enrolOffID'], $_POST['status'], $_POST['grade']);
        echo '<meta http-equiv="refresh" content="0; URL=enrol.php">';
    } 
    if(isset($_POST['delete']))
    { 
        deleteEnrolledUnit($_POST['enrolOffID']);
        echo '<meta http-equiv="refresh" content="0; URL=enrol.php">';
    } 

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
            echo ' <table><tr>'; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {    
                if($count > 4)
                {
                    echo '</tr><tr>';
                    $count = 1;
                }
                echo '<td><form class="enrol" method="POST" action="enrol.php">' .  
                     '<input type="hidden" name="selectOffID" value="' . $row['OffID'] . '">' .
                     '<input type="submit" name="selectEnrolledUnit" class="btn btn-info" value="'. $row['UnitID'] . '">' . 
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
            echo '
                    <table><tr>'; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {    
                if($count > 4)
                {
                    echo '</tr><tr>';
                    $count = 1;
                }
                echo '<td><form class="enrol" method="POST" action="enrol.php">' .  
                     '<input type="hidden" name="selectOffID" value="' . $row['OffID'] . '">' .
                     '<input type="submit" name="selectEnrolledUnit" class="btn btn-info" value="'. $row['UnitID'] . '">' . 
                    '</form></td>';  
                $count += 1;
            }  
            echo "</tr></table>"; //Close the table in HTML
        }
        else
        {
            //echo "Im Review here 2"; 
            echo '<br> Not Enrolled in any unit in the past semesters';
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    function fillList($studNo)
    {
        include "../conn.php";
        $studCourse = getStudCourse($studNo);
        $sql="  SELECT NotEnrl.CourseName, NotEnrl.UnitID, NotEnrl.UnitName, NotEnrl.OffID, NotEnrl.Semester, NotEnrl.Class
                FROM
                (SELECT C.Name AS CourseName, U.UnitID, U.Name AS UnitName, O.Semester, O.OffID, O.UnitID AS OffUnitID, O.Class
                FROM course C INNER JOIN (courseunit CU INNER JOIN (unit U INNER JOIN offering O 
                ON U.UnitID=O.UnitID) ON CU.UnitID=U.UnitID) ON C.CourseID=CU.CourseID WHERE C.CourseID='$studCourse') AS NotEnrl 
                WHERE NotEnrl.OffUnitID NOT IN
                (SELECT O.UnitID
                FROM teamusup.course C INNER JOIN (teamusup.courseunit CU INNER JOIN (unit U INNER JOIN (offering O INNER JOIN enrolment E 
                ON O.OffID=E.OffID) ON U.UnitID=O.UnitID) ON CU.UnitID=U.UnitID) ON C.CourseID=CU.CourseID 
                WHERE E.StudNo='$studNo' && C.CourseID='$studCourse')";
    
        $result = $conn->query($sql); 
        if($result->num_rows > 0)
        {  
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {    
                echo '<option value="'. $row['OffID'] . '">'. $row['UnitID'] . ' ' . $row['UnitName'] . ' ' . $row['Semester'] . ' ' . $row['Class'] ."</option>"; 
            }   
        }
        else
        {
            //echo "Im Review here 2"; 
            echo 'error filling list';
        }  
        $conn->close(); //Make sure to close out the database connection
 
    }

    function getStudCourse($studNo)
    {
        include "../conn.php";
        $sql="  SELECT S.CourseID FROM student S WHERE S.StudNo='$studNo'";
        
        $result = $conn->query($sql); 
        if($result->num_rows == 1)
        {  
            $searchResult =  $result->fetch_array(MYSQLI_ASSOC);
            $studCourse= $searchResult['CourseID']; 
        }
        else
        {
            //echo "Im Review here 2"; 
            echo 'error';
        }  
        $conn->close(); //Make sure to close out the database connection
        return $studCourse;
    }

    function displayOffDetails($OffID)
    { 
        //echo $_POST['addunit'];
        //echo $OffID;
        include "../conn.php";
        $sql ="SELECT O.OffID, U.UnitID, U.Name AS UName, U.Desc, U.Credit, O.Semester, O.Class
        FROM teamusup.offering O INNER JOIN unit U ON O.UnitID=U.UnitID
        WHERE O.OffID='$OffID'";
        $result = $conn->query($sql); 
        if($result->num_rows == 1)
        {  
            $searchResult =  $result->fetch_array(MYSQLI_ASSOC); 
            echo ' 
                    <form method="POST"> 
                    <ul>
                    <li>
                        <label class="reg" for="unitID">' . $searchResult['UnitID'] . '</label>
                    </li>
                    <li>
                        <input type="hidden" name="enrolOffID" value="' . $searchResult['OffID'] . '" readonly>
                        <input type="text" id="uName" value="'.$searchResult['UName'].'" readonly> 
                    </li>
                    <li>
                        <label class="reg" for="desc">Desc</label>
                        <input type="text" id="desc" value="'.$searchResult['Desc'].'" readonly> 
                    </li>
                    <li>
                        <label class="reg" for="cred">Credit</label>
                        <input type="text" id="cred" value="'.$searchResult['Credit'].'" readonly> 
                    </li>
                    <li>
                        <label class="reg" for="sem">Semester</label>
                        <input type="text" id="sem" value="'.$searchResult['Semester'].'" readonly> 
                    </li>
                    <li>
                        <label class="reg" for="class">Class</label>
                        <input type="text" id="class" value="'.$searchResult['Class'].'" readonly> 
                    </li>
                    <li>
                        <label class="reg" for="status">Status</label>
                        <select name="status" id="status"> 
                                <option value="COMPLETED">COMPLETED</option>
                                <option value="DISCONTIN">DISCONTIN</option>  
                                <option value="DUPLICATE">DUPLICATE</option>
                                <option value="ENROLLED">ENROLLED</option>
                                <option value="INVALID">INVALID</option> 
                                <option value="UNCONFIRMED">UNCONFIRMED</option> 
                        </select>
                    </li>
                    <li>
                        <label class="reg" for="grade">Grade</label>
                        <select name="grade" id="grade"> 
                            <option value="HD">HD</option>
                            <option value="ASD">ASD</option>  
                            <option value="D">D</option>
                            <option value="ASC">ASC</option>
                            <option value="C">C</option> 
                            <option value="ASP">ASP</option>
                            <option value="P">P</option>
                            <option value="DNS">DNS</option> 
                            <option value="AS">AS</option>
                            <option value="N">N</option>
                            <option value="Q">Q</option> 
                            <option value="SA">SA</option>
                            <option value="SX">SX</option>
                            <option value="A">A</option> 
                            <option value="W0">W0</option>
                            <option value="WD">WD</option>
                            <option value="NS">NS</option> 
                            <option value="G">G</option> 
                    </select>
                    </li>
                    </ul>
                        <div class="btn-enrol">
                            <input type="submit" name="enrol" id="enrol" value="Enrol">
                        </div> 
                        <div class="btn-cancel1">
                            <input type="submit" name="cancel1" id="cancel1" value="Cancel">
                        </div>
                    </form>
                    ';
        }    
        else
        {
            //echo "Im Review here 2"; 
            echo 'error ';
            //echo $_POST['addunit'];
        }  
        $conn->close();
    }

    function enrolInUnit($offID,$stat,$grade)
    {
        include "../conn.php";
  
        $sql = "INSERT INTO teamusup.enrolment (enrolment.StudNo, enrolment.OffID, enrolment.Status, enrolment.Grade) VALUES (?, ?, ?, ?)";  
        $stmt = $conn->prepare($sql);
        $studNo = $_SESSION['userSNo'];        
        
          
        $stmt->bind_param("iiss", $studNo, $offID, $stat, $grade);  
        $stmt->execute();  
        $newGroupID = $stmt->insert_id;  
        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection   
    }

    function displaySelectedUnit($selectedOffID, $studNo)
    {
        include "../conn.php";
        $sql =" SELECT O.OffID, U.UnitID, U.Name AS UName, U.Desc, U.Credit, O.Semester, O.Class, E.Grade, E.Status
                FROM enrolment E INNER JOIN (teamusup.offering O INNER JOIN unit U ON O.UnitID=U.UnitID) ON E.OffID=O.OffID
                WHERE O.OffID='$selectedOffID' AND E.StudNo='$studNo'";
        $result = $conn->query($sql); 
        if($result->num_rows == 1)
        {  
            $searchResult =  $result->fetch_array(MYSQLI_ASSOC); 
            echo ' 
                    <form method="POST"> 
                    <ul>
                    <li>
                        <label class="reg" for="unitID">' . $searchResult['UnitID'] . '</label>
                    </li>
                    <li>
                        <input type="hidden" name="enrolOffID" value="' . $searchResult['OffID'] . '" readonly>
                        <input type="text" id="uName" value="'.$searchResult['UName'].'" readonly> 
                    </li>
                    <li>
                        <label class="reg" for="desc">Desc</label>
                        <input type="text" id="desc" value="'.$searchResult['Desc'].'" readonly> 
                    </li>
                    <li>
                        <label class="reg" for="cred">Credit</label>
                        <input type="text" id="cred" value="'.$searchResult['Credit'].'" readonly> 
                    </li>
                    <li>
                        <label class="reg" for="sem">Semester</label>
                        <input type="text" id="sem" value="'.$searchResult['Semester'].'" readonly> 
                    </li>
                    <li>
                        <label class="reg" for="class">Class</label>
                        <input type="text" id="class" value="'.$searchResult['Class'].'" readonly> 
                    </li>
                    <li>
                        <label class="reg" for="status">Status</label>
                        <select name="status" id="status"> 
                                <option value="COMPLETED" '; if($searchResult['Status']== 'COMPLETED'){echo 'selected="selected"';} echo ' >COMPLETED</option>
                                <option value="DISCONTIN" '; if($searchResult['Status']== 'DISCONTIN'){echo 'selected="selected"';} echo ' >DISCONTIN</option>  
                                <option value="DUPLICATE" '; if($searchResult['Status']== 'DUPLICATE'){echo 'selected="selected"';} echo ' >DUPLICATE</option>
                                <option value="ENROLLED" '; if($searchResult['Status']== 'ENROLLED'){echo 'selected="selected"';} echo ' >ENROLLED</option>
                                <option value="INVALID" '; if($searchResult['Status']== 'INVALID'){echo 'selected="selected"';} echo ' >INVALID</option> 
                                <option value="UNCONFIRMED" '; if($searchResult['Status']== 'UNCONFIRMED'){echo 'selected="selected"';} echo ' >UNCONFIRMED</option> 
                        </select>
                    </li>
                    <li>
                        <label class="reg" for="grade">Grade</label>
                        <select name="grade" id="grade"> 
                            <option value="HD" '; if($searchResult['Grade']== 'HD'){echo 'selected="selected"';} echo ' >HD</option>
                            <option value="ASD" '; if($searchResult['Grade']== 'ASD'){echo 'selected="selected"';} echo ' >ASD</option>  
                            <option value="D" '; if($searchResult['Grade']== 'D'){echo 'selected="selected"';} echo ' >D</option>
                            <option value="ASC" '; if($searchResult['Grade']== 'ASC'){echo 'selected="selected"';} echo ' >ASC</option>
                            <option value="C" '; if($searchResult['Grade']== 'C'){echo 'selected="selected"';} echo ' >C</option> 
                            <option value="ASP" '; if($searchResult['Grade']== 'ASP'){echo 'selected="selected"';} echo ' >ASP</option>
                            <option value="P" '; if($searchResult['Grade']== 'P'){echo 'selected="selected"';} echo ' >P</option>
                            <option value="DNS" '; if($searchResult['Grade']== 'DNS'){echo 'selected="selected"';} echo ' >DNS</option> 
                            <option value="AS" '; if($searchResult['Grade']== 'AS'){echo 'selected="selected"';} echo ' >AS</option>
                            <option value="N" '; if($searchResult['Grade']== 'N'){echo 'selected="selected"';} echo ' >N</option>
                            <option value="Q" '; if($searchResult['Grade']== 'Q'){echo 'selected="selected"';} echo ' >Q</option> 
                            <option value="SA" '; if($searchResult['Grade']== 'SA'){echo 'selected="selected"';} echo ' >SA</option>
                            <option value="SX" '; if($searchResult['Grade']== 'SX'){echo 'selected="selected"';} echo ' >SX</option>
                            <option value="A" '; if($searchResult['Grade']== 'A'){echo 'selected="selected"';} echo ' >A</option> 
                            <option value="W0" '; if($searchResult['Grade']== 'W0'){echo 'selected="selected"';} echo ' >W0</option>
                            <option value="WD" '; if($searchResult['Grade']== 'WD'){echo 'selected="selected"';} echo ' >WD</option>
                            <option value="NS" '; if($searchResult['Grade']== 'NS'){echo 'selected="selected"';} echo ' >NS</option> 
                            <option value="G" '; if($searchResult['Grade']== 'G'){echo 'selected="selected"';} echo ' >G</option> 
                    </select>
                    </li>
                    </ul>
                        <div class="btn-delete">
                            <input type="submit" name="delete" id="delete" value="Delete Unit">
                        </div>
                        <div class="btn-edit">
                            <input type="submit" name="edit" id="edit" value="Enrol">
                        </div> 
                        <div class="btn-cancel">
                            <input type="submit" name="cancel" id="cancel" value="Cancel">
                        </div>
                    </form>
                    ';
        }    
        else
        {
            //echo "Im Review here 2"; 
            echo 'error ';
            //echo $_POST['addunit'];
        }  
        $conn->close();
    }

    function editEnrolledUnit($offID,$stat,$grade)
    {
        include "../conn.php";
  
        $sql = "    UPDATE enrolment E
                    SET E.Status=?, E.Grade=?
                    WHERE E.OffID=? AND E.StudNo=?";  
        $stmt = $conn->prepare($sql);
        $studNo = $_SESSION['userSNo'];        
        
          
        $stmt->bind_param("ssii", $stat, $grade, $offID, $studNo);  
        $stmt->execute();  
        $newGroupID = $stmt->insert_id;  
        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection   
    }

    function deleteEnrolledUnit($offID)
    {
        include "../conn.php";
  
        $sql = "    DELETE FROM enrolment E 
                    WHERE E.OffID=? AND E.StudNo=?";  
        $stmt = $conn->prepare($sql);
        $studNo = $_SESSION['userSNo'];       
        $stmt->bind_param("ii", $offID, $studNo);  
        $stmt->execute();  
        $newGroupID = $stmt->insert_id;  
        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection   
    }
?>