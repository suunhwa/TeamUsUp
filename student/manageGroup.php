<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Edit Group</title>
    <link rel="stylesheet" href="/css/manageGroup.css"> 
    
</head> 
<?php include "sidebar.php"?> 
<body>
    <div class="container">
        <h2>Manage Group</h2>
        <div class="cgrp-box">
            <form class = "cgrp" method="POST">
                <ul>
                    <div class="left">
                        <?php
                            fillOptions($_POST['offID']);
                        ?> 
                    </div>
                </ul>
            </form>
            <?php 
                $tempGroupMemArr=array();
                if(isset($_POST['grpArray']))
                {
                    //echo $_POST['grpArray'];
                    //print_r(unserialize($_POST['$tempGroupMemArr']));
                    //echo $_POST['$tempGroupMemArr'];
                    $originalArr = json_decode($_POST['grpArray'], true);
                    //echo 'This is decoded';
                    //var_dump($originalArr);
                    //echo 'end of decode'; 
                    $tempGroupMemArr=(array)$originalArr;
                    //print_r($tempGroupMemArr); 
                }
                else
                { 
                    //echo'no array detected';
                    $tempGroupMemArr=array();
                } 
                addTempMember($tempGroupMemArr);
                //print_r($tempGroupMemArr);

                if(isset($_POST['assign']) && $_POST['assign']!=='default')
                {
                    fillGroup($_POST['assign'], $_POST['offID'], $tempGroupMemArr);
                } 
                
                if(isset($_POST['create']))
                {
                    //echo 'im here 1a  ';
                    //echo $_POST['grpName'];
                    if(isset($_POST['grpName']) && isset($_POST['grpArray']))
                    {
                        echo 'im here 2a  ';
                        createGroup($tempGroupMemArr, $_POST['grpName'], $_POST['assign']); 
                    }
                    else if(isset($_POST['grpName']) && !isset($_POST['grpArray']))
                    {
                        echo 'im here 3a  ';
                        createIndividualGroup($_SESSION['userSNo'], $_POST['grpName'], $_POST['assign']);
                    } 
                    else
                    {
                        echo 'Error';
                    }
                }
            ?>
        </div>
    </div>

</body>
</html> 
<?php
    function addTempMember(&$tempGroupMemArr)
    {
        if(isset($_POST['addMember']))
        {
            if(isset($_POST['slctStud']) && $_POST['slctStud']!=='default')
            {
                if(count($tempGroupMemArr)==0)
                {
                    $tempGroupMemArr[] = $_SESSION['userSNo'];
                }
                //echo 'imehere';
                //print_r($tempGroupMemArr);
                $tempGroupMemArr[] = $_POST['slctStud'];
                //echo 'imehere';
                //print_r($tempGroupMemArr);
            }
        }
    }
    
    if(isset($_POST['cancel']))
    { 
        echo '<meta http-equiv="refresh" content="0; URL=group.php">';
    } 
    if(isset($_POST['delete']))
    { 
        deleteGroup($_POST['groupID'], $_POST['groupName']);
    } 
 
    function fillOptions($offID)
    {  
        include "../conn.php"; 

        $count = 0;
        //This sql is to find out the group(s) that both the logged in user and student profile are both in.
        $sql = "SELECT Ass.AssID, U.Name AS UnitName, O.Semester, O.Class, Ass.Name AS AssName, Ass.Desc AS AssDesc
                FROM unit U INNER JOIN (offering O INNER JOIN assignment Ass 
                ON O.OffID=Ass.OffID) ON U.UnitID=O.UnitID
                WHERE O.OffID='$offID'";
         
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        { 
            //echo "Im Review here 1";  
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                if($count == 0)
                {
                    echo ' 
                        <li>
                            <label class="reg" for="unitName">Unit</label>
                            <input type="hidden" name="offID" value="' . $offID . '">
                            <input type="text" id="semester" value="'.$row['UnitName'].'"> 
                        </li>
                        <li>
                            <label class="reg" for="semester">Semester</label>
                            <input type="text" id="semester" value="'.$row['Semester'].'"> 
                        </li>
                        <li>
                            <label class="reg" for="class">Class</label>
                            <input type="text" id="class" value="'.$row['Class'].'"> 
                        </li> 
                        <li>
                            <label class="reg" for="assign">Assignment</label>
                            <select name="assign" id="assign" onchange="this.form.submit()">
                            <option value="default" default>--No selection--</option>'; 
                } 
                if(isset($_POST['assign']) && ($_POST['assign'] == $row['AssID']))
                {
                    echo '<option value="'.$row['AssID'].'" selected="selected">'.$row['AssName'].'</option>  ';   
                }
                else
                { 
                    echo '<option value="'.$row['AssID'].'">'.$row['AssName'].'</option>  ';   
                }

                $count += 1;
            }  
            echo '           
                            </select>
                        </li>            '; //Close the table in HTML
                        
        }
        else
        {
            //echo "Im Review here 2"; 
            noAssignment($offID);
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    function noAssignment($offID)
    {
        include "../conn.php"; 

        $count = 0; 
        $sql = "SELECT U.Name AS UnitName, O.Semester, O.Class
                FROM unit U INNER JOIN offering O ON U.UnitID=O.UnitID
                WHERE O.OffID='$offID'";
         
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows ==1)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
 
            //echo "Im here 1";  
            echo ' 
            <li>
                <label class="reg" for="unitName">Unit</label>
                <input type="hidden" name="offID" value="' . $offID . '">
                <input type="text" id="semester" value="'.$row['UnitName'].'"> 
            </li>
            <li>
                <label class="reg" for="semester">Semester</label>
                <input type="text" id="semester" value="'.$row['Semester'].'"> 
            </li>
            <li>
                <label class="reg" for="class">Class</label>
                <input type="text" id="class" value="'.$row['Class'].'"> 
            </li>
            <li>
                <label class="reg" for="class">This Unit has no assignments</label> 
            </li>';            
        } 
        $conn->close(); 
    }

    function fillGroup($assID, $offID, &$tempGroupMemArr)
    {
        echo' 
        <form class = "cgrp" method="POST">'; 
        include "../conn.php";   
        $sql = "SELECT GR.GroupID 
                FROM teamusup.assignment Ass INNER JOIN (teamusup.group GR INNER JOIN groupmember GM ON GR.GroupID=GM.GroupID) ON Ass.AssID=GR.AssID 
                WHERE GM.StudNo = '" . $_SESSION['userSNo'] . "' AND Ass.AssID='" . $assID . "'";
        
        $result = $conn->query($sql);
        if($result->num_rows ==1)
        { 
            $row = $result->fetch_array(MYSQLI_ASSOC);
            haveGroup($row['GroupID']);
        } 
        else if($result->num_rows ==0)
        {
            noGroup($assID, $tempGroupMemArr, $offID);
        }
        else
        { 
            echo 'User is in multiple groups for the same assignment';  
        }

        $conn->close(); 

        echo '<input type="hidden" name="offID" value="' . $offID . '">';
        echo'</form>';  
        //echo 'assign is : ' . $_POST['assign'] ; 
    }

    function haveGroup($groupID)
    {
        include "../conn.php"; 

        $count = 0;
        //This sql is to find out the group(s) that both the logged in user and student profile are both in.
        $sql = "SELECT GR.Name AS GroupName, S.StudNo, A.FirstName, A.LastName, GM.Role
                FROM teamusup.group GR INNER JOIN (groupmember GM INNER JOIN (student S INNER JOIN teamusup.account A ON S.AccountID=A.AccountID) ON GM.StudNo=S.StudNo) ON GR.GroupID=GM.GroupID
                WHERE GR.GroupID='$groupID'
                ORDER BY FIELD(GM.Role, 'Leader') DESC";
         
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        { 
            echo '
                <ul><div class="mem">';
            //echo "Im Review here 1";  
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {    
                if($count==0)
                {
                    echo '
                    <li>
                        <label class="reg" for="mem">Group Name</label>
                        <input type="text" name="groupName" id="mem" value="'. $row['GroupName'] . '" readonly> 
                    </li>';
                    $count += 1;
                    //first row is always the group leader due to order by
                    $grpLeaderSNo = $row['StudNo']; 
                }
                echo '
                    <li>
                        <label class="reg" for="mem">Group ' . $row['Role'] . '</label>
                        <input type="text" id="mem" value="'. $row['FirstName'] . ' ' . $row['LastName'] . '">'; 

                if($row['Role'] !== 'Leader')
                {
                    if ($grpLeaderSNo==$_SESSION['userSNo'])
                    {
                        /*echo'
                            <input type="hidden" name="groupID" value="' . $groupID . '">
                            <div class="btn-remove">
                                <input type="submit" name="remMem" id="remMem" value="Remove">
                            </div> 
                        ';*/
                    }
                }
                echo'</li>
                ';
            }    
            echo '</div></ul> ';
            if ($grpLeaderSNo==$_SESSION['userSNo'])
            {
                echo' 
                    <input type="hidden" name="groupID" id="groupID" value="'.$groupID.'">
                    <div class="btn-delete"> 
                        <input type="submit" name="delete" id="delete" value="Delete Group">
                    </div>
                    <div class="btn-cancel">
                        <input type="submit" name="cancel" id="cancel" value="Cancel">
                    </div> 
                ';
            }            
        }
        else
        {
            //echo "Im Review here 2";  
            echo 'Error';
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    function noGroup($assID, &$tempGroupMemArr, $offID)
    {
        echo ' 
            <ul>
                <div class="mem">
                    <input type="hidden" name="assID" value="' . $assID . '">
                    <li>
                        <label class="reg" for="mem">Group Name</label>
                        <input type="text" name="grpName" id="grpName" placeholder="Enter your group Name.."> 
                    </li>
                    <li>
                        <label class="reg" for="mem">Group Leader</label>
                        <input type="text" name="grpLeader" id="grpLeader" value="'. $_SESSION['fName'] . ' ' . $_SESSION['lName'] .'" readonly> 
                    </li>';
        availableMembers($assID, $tempGroupMemArr, $offID);

        echo    '</div> 
            </ul> 
            <div class="btn-cancel">
                <input type="submit" name="cancel" id="cancel" value="Cancel">
            </div>
            <div class="btn-create">
                <input type="submit" name="create" id="create" value="Create Group">
            </div>  ';
    }

    function availableMembers($assID, &$tempGroupMemArr, $offID)
    {
        include "../conn.php"; 
        $sql = "SELECT TTA.AssName, TTA.AssID, TTA.OffID, TTA.UnitID, TTA.Semester, TTA.StudNo, TTA.FirstName, TTA.LastName
                FROM 
                (SELECT Ass.Name AS AssName, Ass.AssID, O.OffID, O.UnitID, O.Semester, S.StudNo, A.FirstName, A.LastName
                FROM assignment Ass INNER JOIN (offering O INNER JOIN (enrolment E INNER JOIN (student S INNER JOIN teamusup.account A 
                ON S.AccountID=A.AccountID) ON E.StudNo=S.StudNo) ON O.OffID=E.OffID) ON Ass.OffID=O.OffID WHERE AssID='$assID') AS TTA
                WHERE TTA.StudNo NOT IN
                (SELECT GM.StudNo
                FROM assignment Ass INNER JOIN (teamusup.group GR INNER JOIN groupmember GM ON GR.GroupID=GM.GroupID) ON Ass.AssID=GR.AssID 
                WHERE Ass.AssID='$assID') ";

        $result = $conn->query($sql); 
        if($result->num_rows > 0)
        {  
            echo'<li>
                    <label class="addStud" for="add">Add Member</label>
                    <select name="slctStud" id="slctStud">
                        <option value="default" default>--No selection--</option>';
                        //echo 'IM here  1';
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {    
                //echo 'IM here  2';
                if($row['StudNo']!==$_SESSION['userSNo'])
                { 
                    
                    //echo 'IM here  3';
                    if (count($tempGroupMemArr)>0)
                    {
                        //echo count($tempGroupMemArr);
                        $added=0;
                        for($i = 0; $i < count($tempGroupMemArr); $i++)
                        {
                            //echo 'IM here  4';
                            if($tempGroupMemArr[$i]==$row['StudNo'])
                            {
                                //echo 'IM here  5';
                                $added += 1;
                                break;
                            }
                        }
                        //echo 'IM here  6';
                        if($added==0)
                        {
                            echo "<option value='". $row['StudNo'] . "'>". $row['FirstName'] . " " . $row['LastName'] . "</option>"; 
                            //echo 'IM here  7';
                        }  
                    }
                    else
                    {
                        echo "<option value='". $row['StudNo'] . "'>". $row['FirstName'] . " " . $row['LastName'] . "</option>"; 
                        //echo 'IM here  8';
                    }
                    
                }
            }   
            echo'</select>
                    <div class="btn-add">
                        <input type="submit" name="addMember" id="addMember" value="Add">
                    </div>
                </li>';
            selectedMembers($tempGroupMemArr);

            if(count($tempGroupMemArr)>0)
            {

                //print_r($tempGroupMemArr) ;
                //echo json_encode($tempGroupMemArr); 
                //$tempGroupMemArr = array_values($tempGroupMemArr);
                $tempGrpMem=json_encode($tempGroupMemArr); 
                echo '<input type=\'hidden\' name=\'grpArray\' value=\''.$tempGrpMem.'\'>'; 
            }
            
            echo '<input type="hidden" name="assign" value="' . $assID . '">';
            echo '<input type="hidden" name="offID" value="' . $offID . '">';
        }
        else
        {
            //echo "Im Review here 2"; 
            echo 'Error';
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    function selectedMembers(&$tempGroupMemArr)
    {
        include "../conn.php"; 
        $sql = "SELECT A.FirstName, A.LastName, S.StudNo
                FROM teamusup.account A INNER JOIN student S ON A.AccountID=S.AccountID";
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        { 
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                for($i = 1; $i < count($tempGroupMemArr); $i++)
                {
                    if($tempGroupMemArr[$i]==$row['StudNo'])
                    {
                        echo'<li>
                                <label class="reg" for="mem">Group Member</label>
                                <input type="text" id="mem" value="'. $row['FirstName'] . ' ' . $row['LastName'] . '" readonly>
                            </li>';  
                    }
                } 
            }  
        }
        else
        {
            //echo "Im Review here 2"; 
            echo '<br> Not Enrolled in any unit this semester';
        }  
        $conn->close(); //Make sure to close out the database connection 
    }

    function createGroup(&$grpMembers, $grpName, $assID)
    {
        include "../conn.php";
  
        $sql = "INSERT INTO teamusup.group (group.AssID, group.Name) VALUES (?, ?)";  
        $stmt = $conn->prepare($sql);
          
        $stmt->bind_param("is", $assID, $grpName); 
        //echo 'im here 1x  ';
        //echo $sql;
        $stmt->execute(); 
        //echo "New records created successfully";
        //echo 'im here 2x  ';
        $newGroupID = $stmt->insert_id; 
        //echo 'im here 3x  ';
        //echo $newGroupID;
        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection  
        echo 'there are : ' . count($grpMembers) . ' members.';
        for($i = 0; $i < count($grpMembers); $i++)
        {
            if($grpMembers[$i] == $_SESSION['userSNo'])
            {
                $role = 'Leader';
                $roleDesc = 'Leader of the group';
            }
            else
            {
                $role = 'Member'; 
                $roleDesc = 'A Member of the group';
            }
            createGrpMembers($newGroupID, $grpMembers[$i], $role, $roleDesc, $grpName);
        } 
        echo '<meta http-equiv="refresh" content="0; URL=group.php">';
    }    

    function createIndividualGroup($userSNo, $grpName, $assID)
    {
        include "../conn.php";
  
        $sql = "INSERT INTO teamusup.group (group.AssID, group.Name) VALUES (?, ?)";  
        $stmt = $conn->prepare($sql);
          
        $stmt->bind_param("is", $assID, $grpName); 
        //echo 'im here 1x  ';
        //echo $sql;
        $stmt->execute(); 
        //echo "New records created successfully";
        //echo 'im here 2x  ';
        $newGroupID = $stmt->insert_id; 
        //echo 'im here 3x  ';
        //echo $newGroupID;
        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection  
 
        $role = 'Leader';
        $roleDesc = 'Leader of the group';
            
        createGrpMembers($newGroupID, $userSNo, $role, $roleDesc, $grpName);
        echo '<meta http-equiv="refresh" content="0; URL=group.php">';
        
    }    

    function createGrpMembers($newGroupID, $studNo, $role, $roleDesc, $grpName)
    {
        //echo "the newly created group id is: " . $newGroupID . "  ."; 
        include "../conn.php";
  
        $sql = "INSERT INTO teamusup.groupmember (groupmember.GroupID, groupmember.StudNo, groupmember.Role, groupmember.RoleDesc) VALUES (?, ?, ?, ?)";  
        $stmt = $conn->prepare($sql);
          
        $stmt->bind_param("iiss", $newGroupID, $studNo, $role, $roleDesc); 
        //echo 'im here 1x  ';
        //echo $sql;
        $stmt->execute(); 
        //echo "New records created successfully";
        //echo 'im here 2x  ';  
        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection  

        $studID = getStudAccID($studNo);
        

        if($_SESSION['userID']==$studID)
        {
            $notAction = "Created Group";
            $notMsg = "You have been created the group: " . $grpName . ", by " . $_SESSION['fName'] . " " . $_SESSION['lName'] . ".";
        }
        else
        {
            $notAction = "Added to Group";
            $notMsg = "You have been added to the group: " . $grpName . ", by " . $_SESSION['fName'] . " " . $_SESSION['lName'] . "."; 
        }
        $notStatus = "NA";

        createNotification($_SESSION['userID'], $studID, $notAction, $notMsg, $notStatus, $newGroupID);
    }

    function deleteGroup($groupID, $groupName)
    {
        include "../conn.php";
  
        $notAction = "Delete from Group";
        $notMsg = "The group: " . $groupName . ", that you are in has been deleted by " . $_SESSION['fName'] . " " . $_SESSION['lName'] . ".";
        $notStatus = "NA";

        $sqlGrpMembers = "SELECT GR.StudNo FROM teamusup.groupmember GR WHERE GR.GroupID='".$groupID."'"; 
         
        $resultGrpMembers = $conn->query($sqlGrpMembers); 
        if($resultGrpMembers->num_rows > 0)
        {  
            while($rowGrpMembers = $resultGrpMembers->fetch_array(MYSQLI_ASSOC))
            {    
                $studID = getStudAccID($rowGrpMembers['StudNo']);
                createNotification($_SESSION['userID'], $studID, $notAction, $notMsg, $notStatus, $groupID); 
            }   
        } 
        

        $sql = "DELETE FROM teamusup.group WHERE GroupID=?";  
        $stmt = $conn->prepare($sql);
          
        $stmt->bind_param("i", $groupID); 
        //echo 'im here 1x  ';
        //echo $sql;
        $stmt->execute(); 
        //echo "New records created successfully";
        //echo 'im here 2x  ';  
        $stmt->close(); 
        $conn->close(); 

       
        echo '<meta http-equiv="refresh" content="0; URL=group.php">';
    }

    
    function createNotification($byStudID, $toStudID, $notAction, $notMsg, $notStatus, $subjID)
    {
        include "../conn.php"; 

        $sql = "INSERT INTO notification (RequesterID, RecipientID, Action, Message, SubjectID, Status)
                VALUES (?, ?, ?, ?, ?, ?)";
         
        $stmt = $conn->prepare($sql);
          
        $stmt->bind_param("iissis", $byStudID, $toStudID, $notAction, $notMsg, $subjID, $notStatus); 
        
        $stmt->execute();

        echo "New records created successfully";

        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection

    }

    function getStudAccID($studNo)
    { 
        include "../conn.php"; 
        
        $sql = "SELECT S.AccountID From student S WHERE S.StudNo='".$studNo."'";
        
        $result = $conn->query($sql);
        
        if($result->num_rows == 1)
        {
            $searchResult =  $result->fetch_array(MYSQLI_ASSOC);
            $accID = $searchResult['AccountID'];
            $conn->close(); //Make sure to close out the database connection
            return $accID;
        } 
        else
        {
            echo "ERROR: Could not able to execute " . $sql. " " . mysqli_error($conn);
            return 0;
        } 
        $conn->close(); //Make sure to close out the database connection
    }
?>