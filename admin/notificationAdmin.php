<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Requests/Notifications</title>
    <link rel="stylesheet" href="/css/notificationAdmin.css"> 
    
</head> 
<?php include "admin.php"?>
<body>
    
    <div class="container">
        <h2>Pending Requests / Notifications</h2>
        <div class="noti1">
            

                
                <?php  //echo $_SESSION['userID'];
                        //echo $_SESSION['role'];
                        if (isset($_GET["pageNo1"]))
                        {
                            $selectedPage = $_GET["pageNo1"];
                            if ($selectedPage>1)
                            {
                                pendingNotification($_GET["pageNo1"]);  
                            }
                            else
                            {
                                pendingNotification(1);  
                            }
                        }
                        else
                            {
                                pendingNotification(1);  
                            }

                        ?>


        </div>
    </div>
    <div class="container">
        <h3>Completed Requests / Notifications</h3>
        <div class="noti2">
            

                
                <?php  //echo $_SESSION['userID'];
                        //echo $_SESSION['role'];
                    if (isset($_GET["pageNo"]))
                    {
                    $selectedPage = $_GET["pageNo"];
                        if ($selectedPage>1)
                        {
                        completedNotification($_GET["pageNo"]);
                        }
                        else 
                        {
                        completedNotification(1);
                        }
                    }
                    else 
                    {
                        completedNotification(1);
                    }
                    ?>

        </div>
    </div>


</body>
</html>

<?php
function completedNotification($pageNo)
{
    $limit = 3;
    $offset = ($pageNo * $limit) - $limit;
    include "../conn.php";
    $sql = "SELECT N.NotID, A.AccountID, A.FirstName AS ReqfName, A.LastName AS ReqlName, N.RecipientID, N.Action, N.Message, N.ApproverID, N.SubjectID, N.Status
    FROM teamusup.notification N, teamusup.account A 
    WHERE N.RequesterID=A.AccountID 
    AND N.RecipientID IS NULL
    AND N.Status != 'Pending'
    LIMIT $offset, $limit";  

    $result = $conn->query($sql);

    

    echo '<table class="noti-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Action</th>
            <th>Message</th>
            <th>Approver</th>
            <th>Status</th>
            <th>Decision</th>
        </tr>
    </thead>
    <tbody>'; 
if($result->num_rows > 0)
{ 
    //echo "Im here 1"; 
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {  
        $sql1 = "SELECT A.FirstName, A.LastName
        FROM teamusup.account A
        WHERE A.AccountID = '".$row['ApproverID']."'";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch_array(MYSQLI_ASSOC);

        
        
        echo '<tr><form method="POST">
        <input type="hidden" name="NotID" value="'. $row['NotID'] .'">
        <input type="hidden" name="SubjectID" value="'. $row['SubjectID'] .'">
        <td class="name">' . $row['ReqfName'] . ' ' . $row['ReqlName'] . '</td>
        <td>' . $row['Action'] . '</td>
        <td>' . $row['Message'] . '</td>
        <td>' . $row1['FirstName'] . ' ' . $row1['LastName'] . '</td>
        <td>' . $row['Status'] . '</td>
        <td></td></form></tr>' ;
        
        
    }  
    echo '</tbody></table>';
}
else
{ 
    echo '</tbody></table>';
    echo '<p>No Notifications available.</p>';
}  
$conn->close(); //Make sure to close out the database connection
pagination($limit, $pageNo);

}

function pendingNotification($pageNo)
{
    $limit = 3;
    $offset = ($pageNo * $limit) - $limit;
    include "../conn.php";
    $sql = "SELECT N.NotID, A.AccountID, A.FirstName AS ReqfName, A.LastName AS ReqlName, N.RecipientID, N.Action, N.Message, N.ApproverID, N.SubjectID, N.Status
    FROM teamusup.notification N, teamusup.account A 
    WHERE N.RequesterID=A.AccountID 
    AND N.RecipientID IS NULL
    AND N.Status = 'Pending'
    LIMIT $offset, $limit";  

    $result = $conn->query($sql);

    

    echo '<table class="noti-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Action</th>
            <th>Message</th>
            <th>Approver</th>
            <th>Status</th>
            <th>Decision</th>
        </tr>
    </thead>
    <tbody>'; 
if($result->num_rows > 0)
{ 
    //echo "Im here 1"; 
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {  
        $sql1 = "SELECT A.FirstName, A.LastName
        FROM teamusup.account A
        WHERE A.AccountID = '".$row['ApproverID']."'";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch_array(MYSQLI_ASSOC);

        
            echo '<tr><form method="POST">
            <input type="hidden" name="NotID" value="'. $row['NotID'] .'">
            <input type="hidden" name="SubjectID" value="'. $row['SubjectID'] .'">
            <td class="name">' . $row['ReqfName'] . ' ' . $row['ReqlName'] . '</td>
            <td>' . $row['Action'] . '</td>
            <td>' . $row['Message'] . '</td>
            <td>' . $row1['FirstName'] . ' ' . $row1['LastName'] . '</td>
            <td>' . $row['Status'] . '</td>
            <td><div class="btn-approve">
            <input type="submit" name="approve" id="approve" value="Approve">
            </div>
            <div class="btn-reject">
            <input type="submit" name="reject" id="reject" value="Reject">
            </div></td></form></tr>' ;
        
        
        
    }  
    echo '</tbody></table>';
}
else
{ 
    echo '</tbody></table>';
    echo '<p>No Notifications available.</p>';
}  
$conn->close(); //Make sure to close out the database connection
pagination($limit, $pageNo);

}

function pagination( $limit, $currPageNo)
    {
        $maxPages = 2;
        $adjacentPages = 1;
        include "../conn.php";
        $sql = "SELECT
                COUNT(NotID) AS NoOfResults
                FROM teamusup.notification N, teamusup.account A 
                WHERE N.RequesterID=A.AccountID 
                AND N.RecipientID IS NULL";
        
        $result = $conn->query($sql);
        $noOfRecords = $result->fetch_array(MYSQLI_ASSOC);
        echo '<div class="paging">'; 
        if ($noOfRecords['NoOfResults'] > 0)
        {
            $noOfPage = ceil($noOfRecords['NoOfResults'] / $limit); 
            //echo 'im heeeeeeeeeeeerrrrrrrrrrrrrrrrreeeeeeeeeeeeeeeeeeeeeeee ' . $noOfPage;
            if ($noOfPage == 1)
            {
                echo "<a href='notificationAdmin.php?pageNoBy=1&pageNo=1' class='num on'>1</a>";
            }
            else if($noOfPage > 1)
            { 
                if($noOfPage > $maxPages)
                {
                    if($currPageNo != 1)
                    { 
                        echo"<a href='notificationAdmin.php?pageNoBy=1&pageNo=1' class='btn'><<</a>";
                        echo"<a href='notificationAdmin.php?pageNoBy=1&pageNo=" . $currPageNo-1 . "' class='btn'>Prev</a>";
                    } 
                    for($counter = $currPageNo-$adjacentPages+1; $counter < $currPageNo+$adjacentPages; $counter++)
                    {

                        echo "<a href='notificationAdmin.php?pageNoBy=1&pageNo=$counter' class='num'>$counter</a>";
                    }
                    if($currPageNo != $noOfPage)
                    { 
                        echo"<a href='notificationAdmin.php?pageNoBy=1&pageNo=" . $currPageNo+1 . "' class='btn'>Next</a>"; 
                        echo"<a href='notificationAdmin.php?pageNoBy=1&pageNo=$noOfPage' class='btn'>>></a>";
                    }

                }
                else
                {
                    for($counter = 1; $counter < $noOfPage + 1; $counter++)
                    {
                        echo "<a href='notificationAdmin.php?pageNoBy=1&pageNo=$counter' class='num'>$counter</a>";
                    }
                } 
            } 
        }      
        echo '</div>'; 
        $conn->close(); //Make sure to close out the database connection
    }

if (isset($_POST['approve'])) 
{
    echo $_SESSION['userID'];
    approveRequest($_POST['NotID'], $_POST['SubjectID'],$_GET["pageNo"],$_SESSION['userID']);
}

if (isset($_POST['reject'])) 
{
    echo $_SESSION['userID'];
    rejectRequest($_POST['NotID'], $_POST['SubjectID'],$_GET["pageNo"],$_SESSION['userID']);
}

function approveRequest($NotID, $SubjectID, $currPageNo, $approver)
{
    include "../conn.php";
    if(strlen($SubjectID > 0))
    {
        $sql = "UPDATE teamusup.notification N
            SET N.Status = 'Approved', N.ApproverID = '$approver'
            WHERE NotID = '$NotID'";
            mysqli_query($conn, $sql);

        $sql1 = "DELETE 
        FROM Review
        WHERE RevID = '$SubjectID'";
        mysqli_query($conn, $sql1);

        $sql2 = "SELECT N.RequesterID 
        FROM teamusup.notification N
        WHERE NotID = '$NotID'
        ";
        $result = $conn->query($sql2);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $reqID = $row['RequesterID'];

        $sql3 = "INSERT INTO teamusup.notification
        (RequesterID, RecipientID, Action, Message, SubjectID, Status)
          Values ('$approver', '$reqID', 'Approve Deletion', 'Your Review Deletion Request Has Been Approved', '$SubjectID', 'NA');";
        mysqli_query($conn, $sql3);

    }
    else
    {
        $sql = "UPDATE teamusup.notification N
            SET N.Status = 'Approved', N.ApproverID = '$approver'
            WHERE NotID = '$NotID'";
            mysqli_query($conn, $sql);

        $sql1 = "UPDATE teamusup.notification N, teamusup.account A
            SET A.status = 'Deactivated'
            WHERE N.RequesterID = A.AccountID
            AND N.NotID = '$NotID'";
            mysqli_query($conn, $sql1);
    }
    $conn->close();
    echo '<meta http-equiv="refresh" content="0; URL=notificationAdmin.php?pageNoBy=1&pageNo='.$currPageNo.'">';



}

function rejectRequest($NotID, $SubjectID,$currPageNo,$approver)
{
    include "../conn.php";
    if(strlen($SubjectID > 0))
    {
        $sql = "UPDATE teamusup.notification N
            SET N.Status = 'Rejected', N.ApproverID = '$approver'
            WHERE NotID = '$NotID'";

        mysqli_query($conn, $sql);
            $sql1 = "UPDATE 
            Review SET Display = 'Hidden'
            WHERE RevID = '$SubjectID'";
            mysqli_query($conn, $sql1);

            $sql2 = "SELECT N.RequesterID 
            FROM teamusup.notification N
            WHERE NotID = '$NotID'
            ";
            $result = $conn->query($sql2);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $reqID = $row['RequesterID'];
    
            $sql3 = "INSERT INTO teamusup.notification
            (RequesterID, RecipientID, Action, Message, SubjectID, Status)
              Values ('$approver', '$reqID', 'Reject Deletion', 'Your Review Deletion Request Has Been Rejected', '$SubjectID', 'NA');";
            mysqli_query($conn, $sql3);
    }
    else
    {
        $sql = "UPDATE teamusup.notification N
        SET N.Status = 'Rejected', N.ApproverID = '$approver'
        WHERE NotID = '$NotID'";
        mysqli_query($conn, $sql);

        $sql2 = "SELECT N.RequesterID 
            FROM teamusup.notification N
            WHERE NotID = '$NotID'
            ";
            $result = $conn->query($sql2);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $reqID = $row['RequesterID'];
    
            $sql3 = "INSERT INTO teamusup.notification
            (RequesterID, RecipientID, Action, Message, Status)
              Values ('$approver', '$reqID', 'Reject Deactivation', 'Your Account Deactivation Request Has Been Rejected', 'NA');";
            mysqli_query($conn, $sql3);
    }
    
        
    $conn->close();
    echo '<meta http-equiv="refresh" content="0; URL=notificationAdmin.php?pageNoBy=1&pageNo='.$currPageNo.'">';
}


?>