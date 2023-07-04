<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Edit Review</title>
    <link rel="stylesheet" href="/css/editReview.css"> 
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head> 
<?php include "../header.php"?>
<?php include "../footer.php"?>
<body>
    <div class="container">
        <h2>Edit Review</h2>
        <div class="review-box">
            <form class="review" method="POST">
                <ul>
                    <?php reviewDetails($_POST['revID'])?> 
                </ul>
            </form>
            

            <script>
                const btn = document.querySelector("button");
                const widget = document.querySelector(".star-widget");
                btn.onclick = ()=>{
                    widget.style.display = "none";
                    post.style.display = "block";
                    return false;
                }
            </script>
        </div> 
    </div>
            
</body>
</html>

<?php 
    if(isset($_POST['edit']) )
    { 
        if(!empty($_POST['newRating']))
        {  
            editReview($_POST['revID'], $_POST['fName'], $_POST['lName'], $_POST['newRating'], htmlspecialchars($_POST['revComment'])); 
        } 
    }
    if(isset($_POST['cancel']))
    { 
        echo '<meta http-equiv="refresh" content="0; URL=/profile.php?fName=' . $_POST['fName'] . "&lName=" . $_POST['lName'] . '">';
    } 

    function reviewDetails($revId)
    { 
        include "../conn.php"; 
 
        $sql = "SELECT ByStud.AccountID AS ByStudID, ByStud.FirstName AS ByStudFName, ByStud.LastName AS ByStudLName, ByStud.StudNo AS ByStudSNo,
                ToStud.AccountID AS ToStudID, ToStud.FirstName AS ToStudFName, ToStud.LastName AS ToStudLName, ToStud.StudNo AS ToStudSNo,
                R.Comment AS RevComment, R.Rating AS RevRating, U.Name AS UnitName, Ass.Name AS AssName, G.Name AS RevGroup
                FROM 
                (SELECT A.AccountID, A.FirstName, A.LastName, S.StudNo
                FROM review R 
                INNER JOIN (student S INNER JOIN account A ON S.AccountID = A.AccountID) ON R.ByStudNo = S.StudNo
                WHERE R.RevID = '$revId'
                ) AS ByStud,
                (SELECT A.AccountID, A.FirstName, A.LastName, S.StudNo
                FROM review R 
                INNER JOIN (student S INNER JOIN account A ON S.AccountID = A.AccountID) ON R.ToStudNo = S.StudNo
                WHERE R.RevID = '$revId'
                ) AS ToStud,
                (review R 
                INNER JOIN (teamusup.group G INNER JOIN (assignment Ass INNER JOIN (offering Offer INNER JOIN unit U 
                ON Offer.UnitID = U.UnitID) ON Ass.OffID = Offer.OffID) ON G.AssID = Ass.AssID) ON R.GroupID = G.GroupID)
                WHERE R.RevID = '$revId'
                ";
        
        $result = $conn->query($sql);
        
        if($result->num_rows == 1)
        {
            $row =  $result->fetch_array(MYSQLI_ASSOC); 

            echo' 
            <table>
                <tr>
                    <td><label class="rev" for="revTo">Review To: </label></td>
                    <input type="hidden" name="fName" value="' . $row['ToStudFName'] . '">
                    <input type="hidden" name="lName" value="' . $row['ToStudLName'] . '">
                    <td><input class="rev" type="text" name="revName" id="revName" value="'.$row['ToStudFName']. ' ' .$row['ToStudLName'].'" readonly> </td> 
                </tr>
                <tr>
                    <td><label class="rev" for="revBy">Review By: </label></td>
                    <td><input class="rev" type="text" name="revByfName" id="revByfName" value="'.$row['ByStudFName']. ' ' .$row['ByStudLName'].'" readonly> </td> 
                </tr> 
                <tr>
                    <td><label class="rev">Unit: </label> </td>
                    <td><input class="rev" type="text" name="unitName" id="unitName" value="'.$row['UnitName'].'" readonly> </td>
                </tr>
                <tr>
                    <td><label class="rev">Assignment Name: </label> </td>
                    <td><input class="rev" type="text" name="assName" id="assName" value="'.$row['AssName'].'" readonly> </td>
                </tr>
                <tr>
                    <td><label class="rev">Group Name: </label> </td>
                    <td><input class="rev" type="text" name="groupName" id="groupName" value="'.$row['RevGroup'].'" readonly> </td>
                </tr>
                <tr>
                    <td><label class="rev">Rating: </label> </td> 
                    <td>
                ';
                displayRatings($row['RevRating']);
 
                echo'
                    </td>
                </tr>
                <tr>
                    <td><label class="rev">Comment: </label> </td> 
                    <td><textarea rows="2" cols="50" class="rev" name="revComment" id="revComment" wrap="hard" >'.$row['RevComment'].'</textarea></td>
                </tr> 
            </table>
            <input type="hidden" name="revID" value="' . $revId . '">
            <div class="btn-submit"> 
                <input class="submit" name="edit" id="edit" type="submit" value="Edit Review">
            </div>
            <div class="btn-cancel">
                <input type="submit" name="cancel" id="cancel" value="Cancel">
            </div> 
            ';        
            
 
        } 
        else
        {
            echo "ERROR: Could not execute " . $sql. " " . mysqli_error($conn);
        } 
        $conn->close(); //Make sure to close out the database connection
    }
 
    function displayRatings($rating)
    {
        //echo $rating;
        echo '  <div class="rating">';
        echo '      <input type="radio" name="newRating" id="star1" value="5"'; if($rating==5.0){echo"checked";} echo' ><label for ="star1"></label>';
        echo '      <input type="radio" name="newRating" id="star2" value="4"'; if($rating==4.0){echo"checked";} echo' ><label for ="star2"></label>';
        echo '      <input type="radio" name="newRating" id="star3" value="3"'; if($rating==3.0){echo"checked";} echo' ><label for ="star3"></label>';
        echo '      <input type="radio" name="newRating" id="star4" value="2"'; if($rating==2.0){echo"checked";} echo' ><label for ="star4"></label>';
        echo '      <input type="radio" name="newRating" id="star5" value="1"'; if($rating==1.0){echo"checked";} echo' ><label for ="star5"></label>';
        echo '  </div> ';
    } 

    function editReview($revID, $fName, $lName, $rating, $comment)
    {  
        include "../conn.php"; 
 
        $sql = "UPDATE review R
                SET R.Display='Pending', R.Rating='$rating', R.Comment='$comment'
                WHERE R.revID = '$revID'
                ";
         
        if(mysqli_query($conn, $sql))
        { 
            $conn->close();  
            
            $profileID = getProfileID($fName, $lName);  
            createNotification($_SESSION['userID'], $profileID, $revID);
            echo '<meta http-equiv="refresh" content="0; URL=/profile.php?fName=' . $fName . "&lName=" . $lName . '">'; 
        } 
        else
        {
            echo "ERROR: Could not execute " . $sql. " " . mysqli_error($conn);
        }
        $conn->close(); //Make sure to close out the database connection 
    }
 
    function getProfileID($fName, $lName)
    { 
        include "../conn.php"; 
 
        $sql = "SELECT A.AccountID 
                From account A 
                WHERE A.FirstName='" . $fName . "' AND A.LastName='" . $lName . "'";
        
        $result = $conn->query($sql);
        
        if($result->num_rows == 1)
        {
            $searchResult =  $result->fetch_array(MYSQLI_ASSOC);
            $profileID = $searchResult['AccountID'];
        } 
        else
        {
            echo "ERROR: Could not able to execute " . $sql. " " . mysqli_error($conn);
        } 
        $conn->close(); //Make sure to close out the database connection
        return $profileID;
    }

    function createNotification($byStudID, $toStudID, $revID)
    {
        include "../conn.php"; 

        $sql = "INSERT INTO notification (RequesterID, RecipientID, Action, Message, SubjectID, Status)
                VALUES (?, ?, ?, ?, ?, ?)";
         
        $stmt = $conn->prepare($sql);
        
        $notAction = "Edit Review";
        $notMsg = $_SESSION['fName'] . " " .$_SESSION['lName'] . " has editted a review about you, and is now pending";
        $notStatus = "NA";


        $stmt->bind_param("iissis", $byStudID, $toStudID, $notAction, $notMsg, $revID, $notStatus); 
        
        $stmt->execute();

        echo "New records created successfully";

        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection

    }

?>