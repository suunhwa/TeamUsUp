<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Add Review</title>
    <link rel="stylesheet" href="/css/review.css"> 
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head> 
<?php include "../header.php"?>
<?php include "../footer.php"?>
<body>
    <div class="container">
        <h2>Add Review for <strong> <?php echo $_POST['fName'] . ' ' . $_POST['lName']?> </strong></h2>
        <div class="review-box">
        <form class="review" method="POST">
            <ul>
                <!--<li>
                    <label class="reg" for="course">Course</label>
                    <select id="course">
                        <option value="default" default>--No selection--</option>
                        <option value="bis">Business Information Systems</option>
                        <option value="cs">Computer Science</option>
                        <option value="csf">Cyber Security and Forensics</option>
                        <option value="et">Engineering Technology</option>
                        <option value="f">Finance</option>
                    </select>
                </li>
                <li>
                    <label class="reg" for="unit">Unit</label>
                    <select id="unit">
                        <option value="default" default>--No selection--</option>
                        <option value="db">ICT285-Databases</option>
                        <option value="cos">ICT287-Computer Security</option>  
                        <option value="ea">ICT301-Enterprise Architectures</option>
                        <option value="itp">ICT302-IT Professional Practice Project</option>
                        <option value="sa">ICT373-Software Architectures</option>        
                    </select>                   
                </li>
                <li>
                    <label class="reg" for="assign">Assignment</label>
                    <select id="assign">
                        <option value="default" default>--No selection--</option>
                        <option value="as1">Assignment 1</option>
                        <option value="as2">Assignment 2</option>
                        <option value="as2">Assignment 3</option>                
                    </select>
                </li>
                <li>
                    <label class="reg" for="Comment">Comment</label>
                    <input type="text" name="comment" id="comment"> 
                </li>-->
                <li>
                    <label class="rev" for="revTo">Review To: </label>
                    <input class="rev" type="text" name="revTofName" id="revTofName" value="<?php echo $_POST['fName'];?>" readonly> 
                    <input class="rev" type="text" name="revTolName" id="revTolName" value="<?php echo $_POST['lName'];?>" readonly> 
                </li>
                <li>
                    <label class="rev" for="revBy">Review By: </label>
                    <input class="rev" type="text" name="revByfName" id="revByfName" value="<?php echo $_SESSION['fName'];?>" readonly> 
                    <input class="rev" type="text" name="revBylName" id="revBylName" value="<?php echo $_SESSION['lName'];?>" readonly> 
                </li>
                <li>
                    <label class="reg" for="group">Group</label>
                    <select name="group" id="group">
                        <option value="default" default>--No selection--</option>
                        <?php 
                            fillOptions($_POST['fName'], $_POST['lName'], $_SESSION['userSNo']);
                        ?>
                    </select>                   
                </li>
            </ul>

            <div class="rating"> 
                <input type="radio" name="rating" id="star1" value="5"><label for ="star1"></label>
                <input type="radio" name="rating" id="star2" value="4"><label for ="star2"></label>
                <input type="radio" name="rating" id="star3" value="3"><label for ="star3"></label>
                <input type="radio" name="rating" id="star4" value="2"><label for ="star4"></label>
                <input type="radio" name="rating" id="star5" value="1"><label for ="star5"></label>
            </div>

            <div class="desc">
                <p>Description</p>
                <textarea  name="comment" id="comment" cols="50" rows="10" placeholder="Briefly explain your experience..."></textarea>
            </div>


            <div class="btn-submit">
                <!-- NEED PUT VALIDATION WARNING MESSAGES -->
                <input class="submit" name="submit" id="submit" type="submit" value="Submit">
            </div>
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
    if(isset($_POST['submit']) )
    {
        $selected = $_POST['group'];
        if(!empty($_POST['rating']))
        { 
            //If a rating has been selected
            submitReview($_POST['revTofName'], $_POST['revTolName'], $selected, $_POST['comment'], $_POST['rating']);
            //echo 'Group ID: ' . $selected . ' | Comment: ' . $_POST['comment'] . ' | Rating: ' . $_POST['rate'] ;  
            //echo getProfileStudNo($_POST['revTofName'], $_POST['revTolName']); 
        } 
    }

    function fillOptions($fName, $lName, $userSNo)
    {
        $profileStudNo = getProfileStudNo($fName, $lName);
 
        include "../conn.php";
 
 
        //This sql is to find out the group(s) that both the logged in user and student profile are both in.
        $sql = "SELECT tProfile.GroupID, tProfile.Semester, tProfile.UnitID, tProfile.UnitName, tProfile.GroupName, tProfile.AssName
                FROM 
                (SELECT Ofr.Semester, G.GroupID,  U.UnitID, U.Name AS UnitName, G.Name AS GroupName, Ass.Name AS AssName 
                FROM student S 
                INNER JOIN (groupmember GM INNER JOIN (teamusup.group G INNER JOIN (assignment Ass INNER JOIN (offering Ofr INNER JOIN unit U 
                ON Ofr.UnitID=U.UnitID) ON Ass.OffID=Ofr.OffID) ON G.AssID=Ass.AssID) ON GM.GroupID=G.GroupID) ON S.StudNo=GM.StudNo
                WHERE S.StudNo='" . $profileStudNo . "' ) tProfile
                INNER JOIN
                (SELECT S.StudNo, G.GroupID
                FROM student S 
                INNER JOIN (groupmember GM INNER JOIN teamusup.group G 
                ON GM.GroupID=G.GroupID) ON S.StudNo=GM.StudNo 
                WHERE S.StudNo='" . $userSNo . "' ) tUser
                ON tProfile.GroupID = tUser.GroupID";
         
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        {
            //echo "Im Review here 1"; 
            echo "<table>"; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                //This sql is to find out whether any review has been created by the logged in user about the student in the profile for the group that they are both in. 
                $sqlInner = "SELECT * 
                                FROM review R INNER JOIN teamusup.group G ON R.GroupID=G.GroupID 
                                WHERE G.GroupID='". $row['GroupID'] . "'
                                AND R.ByStudNo='" . $userSNo . "' AND R.ToStudNo='" . $profileStudNo . "' ";
                $innerResult = $conn->query($sqlInner);
                if($innerResult->num_rows == 0)
                {
                    echo "<option value='". $row['GroupID'] . "'>" . 
                    $row['Semester'] . " " . $row['UnitID'] . " " . $row['UnitName'] . " " . $row['GroupName'] . " " . $row['AssName'] . " " . "</option>"; 
                } 
            }  
            echo "</table>"; //Close the table in HTML
        }
        else
        {
            //echo "Im Review here 2"; 
            echo 'Error';
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    function submitReview($fName, $lName, $groupID, $comment, $rating)
    {
        $profileStudNo = getProfileStudNo($fName, $lName);  
        include "../conn.php"; 
 
        $sql = "INSERT INTO review (ByStudNo, ToStudNo, Comment, Rating, GroupID )
                VALUES ('" . 
                $_SESSION['userSNo'] . "', '".  
                $profileStudNo . "', '". 
                $comment . "', '". 
                $rating. "', '" . 
                $groupID . "')";
         
        if(mysqli_query($conn, $sql))
        {
            echo "Records inserted successfully.";

            //get the newly created review id
            $revID = getReviewID( $_SESSION['userSNo'], $profileStudNo, $groupID);
            //get the newly account id of the profile that was just reviewed
            $profileID = getProfileID($fName, $lName);  
            //create the notification
            createNotification($_SESSION['userID'], $profileID, $revID);

            $conn->close(); //Make sure to close out the database connection
            echo '<meta http-equiv="refresh" content="0; URL=/profile.php?fName=' . $fName . "&lName=" . $lName . '">';
        } 
        else
        {
            echo "ERROR: Could not able to execute " . $sql. " " . mysqli_error($conn);
        }
        $conn->close(); //Make sure to close out the database connection
 
    }

    function getProfileStudNo($fName, $lName)
    { 
        include "../conn.php"; 
 
        $sql = "SELECT S.StudNo From account A 
                INNER JOIN student S ON A.AccountID=S.AccountID 
                WHERE A.FirstName='" . $fName . "' AND A.LastName='" . $lName . "'";
        
        $result = $conn->query($sql);
        
        if($result->num_rows == 1)
        {
            $searchResult =  $result->fetch_array(MYSQLI_ASSOC);
            $studNo = $searchResult['StudNo'];
        } 
        else
        {
            echo "ERROR: Could not able to execute " . $sql. " " . mysqli_error($conn);
        } 
        $conn->close(); //Make sure to close out the database connection
        return $studNo;
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
        
        $notAction = "Gave Review";
        $notMsg = $_SESSION['fName'] . " " .$_SESSION['lName'] . " has written a review to you, and is now pending";
        $notStatus = "NA";


        $stmt->bind_param("iissis", $byStudID, $toStudID, $notAction, $notMsg, $revID, $notStatus); 
        
        $stmt->execute();

        echo "New records created successfully";

        $stmt->close(); 
        $conn->close(); //Make sure to close out the database connection

    }

    function getReviewID($byStudNo,$toStudNo,$groupID)
    {
        include "../conn.php"; 
 
        $sql = "SELECT RevID From review
                WHERE ByStudNo='$byStudNo', ToStudNo='$toStudNo', GroupID='$groupID'";
        
        $result = $conn->query($sql);
        
        if($result->num_rows == 1)
        {
            $searchResult =  $result->fetch_array(MYSQLI_ASSOC);
            $revID = $searchResult['RevID'];
        } 
        else
        {
            echo "ERROR: Could not able to execute " . $sql. " " . mysqli_error($conn);
        } 
        $conn->close(); //Make sure to close out the database connection
        return $revID;

    }

?>