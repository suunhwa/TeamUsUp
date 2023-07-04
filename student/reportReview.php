<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Report Review</title>
    <link rel="stylesheet" href="/css/repReview.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    
</head> 

<body>
<header><?php include "../header.php"?></header>
<section class="margin">
    <div class="container">
        <h2>Report Review</h2>
        <div class="review-box">
        <form class="review" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <ul> 
                <?php reviewDetails($_POST['revID'])?>
                
            </ul>

            <div class="desc">
                <p>Reason for Reporting</p>
                <textarea  name="reportMsg" id="reportMsg" cols="50" rows="10" placeholder="State the reason for reporting this review..."></textarea>
            </div>
            <?php
                if(isset($_POST['submit']) )
                { 
                    if(!empty($_POST['reportMsg']))
                    {  
                        reportReview($_POST['revID'],$_SESSION['fName'],$_SESSION['lName']); 
                    } 
                    else
                    {
                        echo '<input type="text" class="errMsg" name="errorMsg" id="errorMsg" value="Please enter the reason for the reporting this review." readonly>';
                    }
                } 
            ?>

            <div class="btn-submit">
                <!-- NEED PUT VALIDATION WARNING MESSAGES -->
                <input class="submit" name="submit" id="submit" type="submit" value="Submit">
            </div>
        </form>
         
        <script>
            $(document).ready(function() 
            { 
                var star_rating_width = $(".fill-ratings span").width();
                // Sets the container of the ratings to span width
                // thus the percentages in mobile will never be wrong
                $(".star-ratings").width(star_rating_width);
            });
            </script>
        </div> 
    </div>
        </section>
    <footer><?php include "../footer.php"?></footer>
</body>
</html>

<?php  
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
                    <td><textarea rows="2" cols="50" class="rev" name="revComment" id="revComment" wrap="hard" readonly>'.$row['RevComment'].'</textarea></td>
                </tr> 
            </table>
            <input type="hidden" name="revID" value="' . $revId . '">
            ';        
 
        } 
        else
        {
            echo "ERROR: Could not execute " . $sql. " " . mysqli_error($conn);
        } 
        $conn->close(); //Make sure to close out the database connection
    }

    function reportReview($revID, $fName, $lName)
    {  
        include "../conn.php"; 
 
        $sql = "UPDATE review R
                SET R.Display='Reported'
                WHERE R.revID = '$revID'
                ";
         
        if(mysqli_query($conn, $sql))
        {
            //echo "Review reported successfully.";
            $conn->close(); //Make sure to close out the database connection

            createNotification($_SESSION['userID'], htmlspecialchars($_POST['reportMsg']), $revID);
            echo '<meta http-equiv="refresh" content="0; URL=/profile.php?fName=' . $fName . "&lName=" . $lName . '">';
            //header("location: profile.php?fName=" . $fName . "&lName=" . $lName);
        } 
        else
        {
            echo "ERROR: Could not execute " . $sql. " " . mysqli_error($conn);
        }
        $conn->close(); //Make sure to close out the database connection 
    }

    function displayRatings($rating)
    { 
        $rating = $rating * 20;
        echo '
            <div class="star-ratings">
                <div class="fill-ratings" style="width: ' . $rating . '%;">
                    <span>🟊🟊🟊🟊🟊</span>
                </div>
                <div class="empty-ratings">
                    <span>🟊🟊🟊🟊🟊</span>
                </div>
            </div>
 
        ';
    }

    function createNotification($byAccID, $reportMsg, $revID)
    {
        include "../conn.php"; 
        //echo $byAccID. " " . $reportMsg. " " .$revID;
        $sql = "INSERT INTO notification (RequesterID, Action, Message, SubjectID, Status) VALUES (?, ?, ?, ?, ?)";
         
        $stmt = $conn->prepare($sql);
         
        //echo "im here 1";
        $revAction = "Report Review";
        $revStatus = "Pending";

        $stmt->bind_param("issis", $byAccID, $revAction, $reportMsg, $revID, $revStatus); 
        //echo "im here 2";
        
        $stmt->execute();

        echo "New records created successfully";

        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection

    }

?>