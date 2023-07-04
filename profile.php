<?php include('student/Export.php') ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>TeamUsUp - Profile</title>
        <link rel="stylesheet" href="css/profile.css" >  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

    </head>
    <?php  
    class tStud
    { 
        var $studAccID; 
        var $studNo;
        var $studfName;
        var $studlName;
        var $studBio;
        var $studCourse;
        var $studORating;    
    }
    //echo $_GET["fName"] . $_GET["lName"];
    if (isset($_GET["fName"]) && isset($_GET["lName"]))
    {
        //echo 'IM HEREE ';
        $tProfile = new tStud;
        displayStudent($_GET["fName"], $_GET["lName"], $tProfile);  
    }
    
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
    <header><?php include "header.php"?></header>
        <section class="margin">
            <div class="container"> 
                <div class="profile-details">
                    <div class="left">
                        <div class="name"> 
                            <p>Name : <?php echo $_GET["fName"] . ' ' . $_GET["lName"];?></p>
                        </div>
                        <div class="degree"> 
                            <p>Degree : <?php echo $tProfile->studCourse;?></p>
                        </div> 
                        
                        <div class="info">
                            <span class="details">About</span>
                            <div class="bio-box">
                                <?php
                                    echo '
                                    <div class="edit-btn">
                                        <form method="POST" action="student/editprofile.php">
                                        <input type="text" name="userBio" class="form-control" value="'.$tProfile->studBio.'" readonly>';
                                    if($_SESSION["userID"] !== null && $_SESSION["userID"] == $tProfile->studAccID)
                                    { 
                                                echo'<input type="submit" name="editProfile" id="editProfile" class="btn btn-edit" value="Edit Profile">'; 
                                    }
                                    echo '</form>
                                    </div>';  
                                ?> 
                            </div>
                        </div> 
                        <div class="unit">
                            <p>Units</p>
                            <div class="unit-box">
                                <div class="unit-details">
                                    <h2>Current Semester</h2>
                                    <!--<input type="button" name="cunit"class="btn btn-info" value=""> -->
                                    <?php displayCurrUnit($tProfile, $curSem)?>
                                    <h2>Past Semester(s)</h2>
                                    <!--<input type="button" name="punit"class="btn btn-info" value=""> -->
                                    <?php displayPastUnit($tProfile, $curSem)?>  
                                </div>
                            </div> 
                        </div> 
                        <?php // Check if the user is this account is the logged in user's account. if yes create the button to export teamwork history
                                        if($_SESSION["userID"] !== null && $_SESSION["userID"] == $tProfile->studAccID)
                                        {
                                            echo '
                                            <form method="POST">
                                                <input type="submit" name="export" id="export" class="btn btn-export" value="Export Teamwork History">
                                            </form>';
                                        }
                                    ?>                          
                        <?php // Check if the user is this account is the logged in user's account. if yes create the button to export teamwork history
                            if($_SESSION["userID"] !== null && $_SESSION["userID"] == $tProfile->studAccID)
                            { 
                                echo ' 
                                    <form method="POST" action="student/group.php">
                                        <input type="submit" name="grouping" class="btn btn-grp" value="Manage Groups">
                                    </form>
                                ';
                            }
                        ?>                       
                    </div> 
                    <div class="right">
                        <div class="rating">
                            <p>Overall Rating <?php echo $tProfile->studORating?></p>
                            <div class="rating-box"> 
                                <!--
                                <input type="radio" name="rating" id="star1" ><label for ="star1"></label>
                                <input type="radio" name="rating" id="star2"><label for ="star2"></label>
                                <input type="radio" name="rating" id="star3"><label for ="star3"></label>
                                <input type="radio" name="rating" id="star4"><label for ="star4"></label>
                                <input type="radio" name="rating" id="star5"><label for ="star5"></label>
                                -->
                                <?php displayRatings($tProfile->studORating); ?>
                            </div>
                        </div> 
                        <div class="review">
                            <p>Reviews</p>
                            <?php // Check if the user is this account is the logged in user's account. if yes create the button to export teamwork history
                                if($_SESSION["userID"] !== null && $_SESSION["userID"] !== $tProfile->studAccID)
                                { 
                                    checkGrouping($tProfile->studNo, $_SESSION['userSNo'], $tProfile->studfName, $tProfile->studlName);
                                    
                                }
                            ?>     
                            <div class="review-box">
                                <div class="review-details">                                 
                                    <!--
                                    <input type="text" name="userReview"class="form-review" value="">
                                    <div class="btns">
                                        <input type="submit" name="delete"class="btn btn-delete" value="Report Review">
                                        <input type="submit" name="hide"class="btn btn-hide" value="Hide Review">
                                    </div>
                                    -->
                                    <?php displayReviews($tProfile, $curSem);?>
                                </div> 
                            </div> 
                        </div>


                        <?php // Check if the user is this account is the logged in user's account. if yes create the button to export teamwork history
                            if($_SESSION["userID"] !== null && $_SESSION["userID"] == $tProfile->studAccID)
                            { 
                                echo'
                                <div class="review">
                                    <p>Pending/Hidden Reviews</p> 
                                    <div class="review-box">
                                        <div class="review-details">     
                                ';  
                                displayHPRev($tProfile);
                                echo'
                                        </div> 
                                    </div> 
                                </div> 
                                ';

                                echo'
                                <div class="review">
                                    <p>Reported Reviews</p> 
                                    <div class="review-box">
                                        <div class="review-details">     
                                ';  
                                displayRepRev($tProfile);
                                echo'
                                        </div> 
                                    </div> 
                                </div> 
                                ';

                            }
                        ?>   
                    </div>   
                </div>
            </div>
        </section> 
        <script>
            $(document).ready(function() { 
                var star_rating_width = $(".fill-ratings span").width(); 
                $(".star-ratings").width(star_rating_width);
              });
            </script>
        <footer><?php include "footer.php"?></footer>
    </body> 
</html>

<?php
    function displayStudent($fName, $lName, &$tProfile)
    { 
        include "conn.php";
 
        $sql = "SELECT A.AccountID, A.FirstName, A.LastName, S.StudNo, C.Name AS CourseName, S.Biography, S.OverallRating
                FROM (account A INNER JOIN student S ON A.AccountID = S.AccountID) INNER JOIN course C ON S.CourseID = C.CourseID
                WHERE A.FirstName = '" . $fName . "' AND A.LastName = '" . $lName . "' AND A.Status='Active'"; //You don't need a ; like you do in SQL
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo "Query has been executed";  
        if($result->num_rows == 1)
        { 
            //echo "Im here 1 "; 
            $searchResult =  $result->fetch_array(MYSQLI_ASSOC);
            $tProfile->studAccID = $searchResult['AccountID']; 
            $tProfile->studNo = $searchResult['StudNo'];
            $tProfile->studfName = $searchResult['FirstName'];
            $tProfile->studlName = $searchResult['LastName'];
            $tProfile->studBio = $searchResult['Biography'];
            $tProfile->studCourse = $searchResult['CourseName']; 
            $tProfile->studORating = $searchResult['OverallRating'];  
        }
        else
        {
            //echo "Im here 2"; 
            $searchResult = "0 Results"; 
        }
        $conn->close(); //Make sure to close out the database connection 
    } 
    function displayReviews(&$tProfile, $curSem)
    { 
        include "conn.php";
 
        $sql = "SELECT (concat_ws(' ', A.FirstName, A.LastName)) AS reviewBy, R.RevID, R.ByStudNo, R.Comment, R.Rating, R.GroupID, U.UnitID, U.Name AS UnitName, Ass.Name AS AssName, O.Semester
            FROM review R
            INNER JOIN (teamusup.group G INNER JOIN (assignment Ass INNER JOIN (offering O INNER JOIN unit U ON U.UnitID = O.UnitID) ON Ass.OffID = O.OffID) ON G.AssID = Ass.AssID)
            INNER JOIN
            (account A INNER JOIN student S ON A.AccountID = S.AccountID) ON R.ByStudNo = S.StudNo
            WHERE R.ToStudNo = '" . $tProfile->studNo . "' 
            AND R.Display = 'Accepted'
            AND R.GroupID = G.GroupID";
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        {
            //echo "Im Review here 1"; 
            echo '<table class="revTable">'; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                echo '<tr><td>By: ' . $row['reviewBy'] . '&nbsp' .
                        '<br>'. $row['UnitID'] . ' ' . $row['UnitName'] . ' ' . $row['Semester']  . '&nbsp' .
                        '<br>Assignment: ' . $row['AssName'] .'&nbsp';
                        displayRatings($row['Rating']); //echo $row['Rating'];
                        echo '<br><textarea rows="3" cols="70" class="form-review"  name="userReview" wrap="hard" readonly>'. $row['Comment'] .'</textarea>'. '</td>';
                            if($_SESSION["userID"] == $tProfile->studAccID)
                            {
                                echo '<td><div class="btns">
                                      <form method="POST">
                                        <input type="hidden" name="revID" value="' . $row['RevID'] . '">
                                        <input type="submit" name="hide" class="btn-hide" value="Hide Review">
                                      </form>
                                      <form method="POST" action="/student/reportReview.php">
                                        <input type="hidden" name="revID" value="' . $row['RevID'] . '">
                                        <input type="submit" name="report" class="btn-report" value="Report Review" >
                                      </form>
                                    </div></td>';
                            } 
                            //edit review burron
                            if(($_SESSION["userSNo"] == $row['ByStudNo']) && $row['Semester'] == $curSem)
                            {
                                echo '<td><div class="btns">
                                      <form method="POST" action="/student/editReview.php">
                                        <input type="hidden" name="revID" value="' . $row['RevID'] . '">
                                        <input type="submit" name="edit" class="btn-report" value="Edit Review" >
                                      </form>
                                    </div></td>';
                            } 
                echo    '</tr>'; 
            }  
            echo "</table>"; //Close the table in HTML
        }
        else
        {
            //echo "Im Review here 2"; 
            echo 'No Reviews Availble';
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    function displayCurrUnit(&$tProfile, $curSem)
    { 
        include "conn.php";
 
        $sql = "SELECT U.UnitID, U.Name AS UnitName, E.Status, E.Grade
                FROM student S
                INNER JOIN (enrolment E INNER JOIN (offering O INNER JOIN unit U ON U.UnitID = O.UnitID) ON E.OffID = O.OffID)
                ON S.StudNo = E.StudNo
                WHERE S.StudNo = '". $tProfile->studNo ."' AND O.Semester = '" . $curSem . "'";
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        {
            //echo "Im Review here 1"; 
            echo '  <table class="unit-table">
                        <thead>
                            <th>Unit ID</th>
                            <th>Unit Name</th>
                            <th>Status</th>
                            <th>Grade</th>
                        </thead>
                        <tbody>'; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                echo '  <tr><form method="GET" action="unit.php">' .  
                        '<td><input type="submit" name="unitID" class="btn btn-info" value="'. $row['UnitID'] .'"></td>'.
                        '<td>'. $row['UnitName'] .'</td>'.
                        '<td>'. $row['Status'] .'</td>'.
                        '<td>'. $row['Grade'] .'</td>'. 
                    '</td></form></tr>'; 
            }  
            echo "</tbody></table>"; //Close the table in HTML
        }
        else
        {
            //echo "Im Review here 2"; 
            echo '<br>&nbsp;&nbsp;&nbsp; Not Enrolled in any unit this semester';
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    function displayPastUnit(&$tProfile, $curSem)
    { 
        include "conn.php";
 
        $sql = "SELECT U.UnitID, U.Name AS UnitName, E.Status, E.Grade
                FROM student S
                INNER JOIN (enrolment E INNER JOIN (offering O INNER JOIN unit U ON U.UnitID = O.UnitID) ON E.OffID = O.OffID)
                ON S.StudNo = E.StudNo
                WHERE S.StudNo = '". $tProfile->studNo ."' AND O.Semester <> '" . $curSem . "'";
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        {
            //echo "Im Review here 1"; 
            echo '  <table class="unit-table">
                        <thead>
                            <th>Unit ID</th>
                            <th>Unit Name</th>
                            <th>Status</th>
                            <th>Grade</th>
                        </thead>
                        <tbody>'; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                echo '  <tr><form method="GET" action="unit.php">' .  
                        '<td><input type="submit" name="unitID" class="btn btn-info" value="'. $row['UnitID'] .'"></td>'.
                        '<td>'. $row['UnitName'] .'</td>'.
                        '<td>'. $row['Status'] .'</td>'.
                        '<td>'. $row['Grade'] .'</td>'. 
                    '</td></form></tr>'; 
            }  
            echo "</tbody></table>"; //Close the table in HTML
        }
        else
        {
            //echo "Im Review here 2"; 
            echo '<br>&nbsp;&nbsp;&nbsp; Not Enrolled in any unit this semester';
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    function checkGrouping($profStudNo, $userStudNo, $studfName, $studlName)
    {
        include "conn.php";
 
        $sql = "SELECT tProfile.GroupID
                FROM 
                (SELECT G.GroupID FROM student S 
                INNER JOIN (groupmember GM INNER JOIN teamusup.group G ON GM.GroupID=G.GroupID) ON S.StudNo=GM.StudNo
                WHERE S.StudNo='" . $profStudNo . "' ) tProfile 
                INNER JOIN 
                (SELECT G.GroupID FROM student S 
                INNER JOIN (groupmember GM INNER JOIN teamusup.group G ON GM.GroupID=G.GroupID) ON S.StudNo=GM.StudNo
                WHERE S.StudNo='" . $userStudNo . "' ) tUser 
                ON tProfile.GroupID = tUser.GroupID
                "; //You don't need a ; like you do in SQL
        
        //echo $sql;
        $result = $conn->query($sql);
        // mgnr = MatchingGroupNoReview
        $mgnr = 0;
        //echo "Query has been executed";  
        if($result->num_rows > 0)
        {  
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                //This sql is to find out whether any review has been created by the logged in user about the student in the profile for the group that they are both in. 
                $sqlInner = "SELECT * 
                                FROM review R INNER JOIN teamusup.group G ON R.GroupID=G.GroupID 
                                WHERE G.GroupID='". $row['GroupID'] . "'
                                AND R.ByStudNo='" . $userStudNo . "' AND R.ToStudNo='" . $profStudNo . "' ";
                $innerResult = $conn->query($sqlInner);
                if($innerResult->num_rows == 0)
                {
                    $mgnr = $mgnr + 1;
                } 
            }   

            // if at least theres one group where the logged in user has not reviewed the current student profile
            if($mgnr > 0)
            {
                //echo "Im here 1 "; 
                echo '
                <form method="POST" action="student/review.php">
                    <input type="hidden" name="fName" id="fName" value="' . $studfName . '">
                    <input type="hidden" name="lName" id="lName" value="' . $studlName . '">
                    <input type="submit" name="addRev" id="addRev" class="btn-addRev" value="Add Review">
                </form>
                ';
            } 
        } 
        $conn->close(); //Make sure to close out the database connection 
    }

    function hideReview($revID)
    {
        include "conn.php";
 
        $sql = "UPDATE review
                SET review.Display='Hidden'
                WHERE review.revID='". $revID ."'
                ";
         
        if(mysqli_query($conn, $sql))
        {
            echo "Records updated successfully.";
            $conn->close(); //Make sure to close out the database connection
            echo '<meta http-equiv="refresh" content="0; URL=/profile.php?fName=' . $_SESSION['fName'] . "&lName=" . $_SESSION['lName'] . '">';
        } 
        else
        {
            echo "ERROR: Could not able to execute " . $sql. " " . mysqli_error($conn);
        }
        $conn->close(); //Make sure to close out the database connection
    }

    function displayRatings($rating)
    { 
        $rating = $rating * 20;
        //echo $rating;
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

    function displayHPRev($tProfile)
    {
        //display hidden pending review
        include "conn.php";
 
        $sql = "SELECT (concat_ws(' ', A.FirstName, A.LastName)) AS reviewBy, R.RevID, R.ByStudNo, R.Comment, R.Rating, R.GroupID, U.Name AS UnitName, Ass.Name AS AssName, R.Display
            FROM review R
            INNER JOIN (teamusup.group G INNER JOIN (assignment Ass INNER JOIN (offering O INNER JOIN unit U ON U.UnitID = O.UnitID) ON Ass.OffID = O.OffID) ON G.AssID = Ass.AssID)
            INNER JOIN
            (account A INNER JOIN student S ON A.AccountID = S.AccountID) ON R.ByStudNo = S.StudNo
            WHERE R.ToStudNo = '" . $tProfile->studNo . "' 
            AND R.Display <> 'Accepted'
            AND R.Display <> 'Reported'
            AND R.GroupID = G.GroupID";
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        {
            //echo "Im Review here 1"; 
            echo '<table class="revTable">'; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                echo '<tr><td>By: ' . 
                        $row['reviewBy'] . '&nbsp' .
                        '<br>Unit: '. $row['UnitName'] . '&nbsp' .
                        '<br>Assignment: ' . $row['AssName'] .'&nbsp' .
                        '<br>Review Status: ' . $row['Display'] .'&nbsp' ;
                        displayRatings($row['Rating']); //echo $row['Rating'];
                        echo '<br><textarea rows="3" cols="80" class="form-review"  name="userReview" wrap="hard" readonly>'. $row['Comment'] .'</textarea>'.
                        '</td>' ; 
                            if($_SESSION["userID"] == $tProfile->studAccID)
                            {
                                echo '<td><div class="btns">';
                                if($row['Display']=='Hidden')
                                {
                                    //if hidden, buttons available: display, report
                                    
                                    echo'
                                    <form method="POST">
                                        <input type="hidden" name="revID" value="' . $row['RevID'] . '">
                                        <input type="submit" name="display" class="btn-display" value="Display Review">
                                    </form>
                                    <form method="POST" action="/student/reportReview.php">
                                        <input type="hidden" name="revID" value="' . $row['RevID'] . '">
                                        <input type="submit" name="report" class="btn-report" value="Report Review" >
                                    </form>';
                                }
                                else if($row['Display']=='Pending')
                                {
                                    //if pending, buttons available: display, report, hide
                                    echo'
                                    <form method="POST">
                                        <input type="hidden" name="revID" value="' . $row['RevID'] . '">
                                        <input type="submit" name="display" class="btn-display" value="Display Review">
                                        <input type="submit" name="hide" class="btn-hide" value="Hide Review">
                                    </form>
                                    <form method="POST" action="/student/reportReview.php">
                                        <input type="hidden" name="revID" value="' . $row['RevID'] . '">
                                        <input type="submit" name="report" class="btn-report" value="Report Review" >
                                    </form>';
                                }
                                echo'</div></td>';
                            } 
                echo    '</td></tr>'; 
            }  
            echo "</table>"; //Close the table in HTML
        }
        else
        {
            //echo "Im Review here 2"; 
            echo 'No Reviews Hidden/Pending';
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    function displayRepRev($tProfile)
    {
        //display hidden pending review
        include "conn.php";
 
        $sql = "SELECT (concat_ws(' ', A.FirstName, A.LastName)) AS reviewBy, R.RevID, R.ByStudNo, R.Comment, R.Rating, R.GroupID, U.Name AS UnitName, Ass.Name AS AssName, R.Display
            FROM review R
            INNER JOIN (teamusup.group G INNER JOIN (assignment Ass INNER JOIN (offering O INNER JOIN unit U ON U.UnitID = O.UnitID) ON Ass.OffID = O.OffID) ON G.AssID = Ass.AssID)
            INNER JOIN
            (account A INNER JOIN student S ON A.AccountID = S.AccountID) ON R.ByStudNo = S.StudNo
            WHERE R.ToStudNo = '" . $tProfile->studNo . "' 
            AND R.Display = 'Reported'
            AND R.GroupID = G.GroupID";
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        {
            //echo "Im Review here 1"; 
            echo '<table class="revTable">'; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                echo '<tr><td>By: ' . 
                        $row['reviewBy'] . '&nbsp' .
                        '<br>Unit: '. $row['UnitName'] . '&nbsp' .
                        '<br>Assignment: ' . $row['AssName'] .'&nbsp' .
                        '<br>Review Status: ' . $row['Display'] .'&nbsp' ;
                        displayRatings($row['Rating']); //echo $row['Rating'];
                        echo '<br><textarea rows="3" cols="80" class="form-review"  name="userReview" wrap="hard" readonly>'. $row['Comment'] .'</textarea>'.  
                        '</td></tr>'; 
            }  
            echo "</table>"; //Close the table in HTML
        }
        else
        {
            //echo "Im Review here 2"; 
            echo 'No Reviews Reported';
        }  
        $conn->close(); //Make sure to close out the database connection
    }


    function acceptReview($revID)
    {
        //could also call it display review
        include "conn.php";
 
        $sql = "UPDATE review
                SET review.Display='Accepted'
                WHERE review.revID='". $revID ."'
                ";
         
        if(mysqli_query($conn, $sql))
        {
            echo "Records updated successfully.";
            $conn->close(); //Make sure to close out the database connection
            echo '<meta http-equiv="refresh" content="0; URL=/profile.php?fName=' . $_SESSION['fName'] . "&lName=" . $_SESSION['lName'] . '">';
        } 
        else
        {
            echo "ERROR: Could not able to execute " . $sql. " " . mysqli_error($conn);
        }
        $conn->close(); //Make sure to close out the database connection
    }
    

    if (isset($_POST['editProfile'])) 
    {
        // Edit Profile was clicked
        echo 'Hello profile';
    } 
    if (isset($_POST['hide']))
    {
        hideReview($_POST['revID']);
    }
    
    if (isset($_POST['display']))
    {
        acceptReview($_POST['revID']);
    }
?>