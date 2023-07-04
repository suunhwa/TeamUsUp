<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Search Result</title>
    <link rel="stylesheet" href="css/result.css" >  
</head>


<body>
<header><?php include "header.php"?></header>
        <section class="margin">
<div class="container">
    <form method="GET" action="result.php">
        <div class="form-box" >
                <?php echo 
                '<input type="text" class="search-field" placeholder="Search by name..." name="searchTxt" id="searchTxt" value="'. $_GET["searchTxt"] .'">
                <input type="submit" class ="search-btn" name="SearchStudent" id="SearchStudent" value="Search">';
                ?>
        </div>
    </form>
        <?php
            if (isset($_GET["SearchStudent"]) || isset($_GET["searchTxt"]))
            {
                //echo 'Get pageNo is: ' . $_GET['pageNo'] . '  ';
                if (isset($_GET["pageNo"]))
                {
                    $selectedPage = $_GET["pageNo"];
                    if ($selectedPage>1)
                    {
                        //echo '.  Im here 1  .';
                        displayResults($_GET["searchTxt"], $_GET["pageNo"]);    
                    }
                    else
                    {
                        //echo '.  Im here 2  .';
                        displayResults($_GET["searchTxt"], 1);    
                    } 
                } 
                else
                {
                    //echo '.  Im here 3  .';
                    displayResults($_GET["searchTxt"], 1);    
                } 
            }
        ?>            
    </div>
     
 </section>
<footer><?php include "footer.php"?></footer>
</body>
</html>

<?php  
    function displayResults($studentName, $pageNo)
    {
        include "conn.php";
        $limit = 5;
        $offset = ($pageNo * $limit) - $limit;
        
        //echo $pageNo . ',  '. $offset;

        $sql = "SELECT A.AccountID, A.FirstName, A.LastName, C.Name AS CourseName 
                FROM account A
                INNER JOIN (student S INNER JOIN course C ON S.CourseID = C.CourseID) ON A.AccountID = S.AccountID
                WHERE instr((concat_ws(' ', FirstName, LastName)), '$studentName')
                LIMIT $offset, $limit"; //You don't need a ; like you do in SQL
        

        //echo $sql;
        //$result = mysql_query($query);
        $result = $conn->query($sql);
        //echo "Query has been executed";   
        echo '<table class="user-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Major</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>'; 
        if($result->num_rows > 0)
        { 
            //echo "Im here 1"; 
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {    
                echo '<tr><td class="name">' . $row['FirstName'] . ' ' . $row['LastName'] . '</td>
                <td>' . $row['CourseName'] . '</td>
                <td><a href="profile.php?fName=' . $row['FirstName'] . '&lName=' . $row['LastName']  . '"><button  class="btn btn-grp" >Select</button></a></td></tr>';
            }  
            echo '</tbody></table>';
        }
        else
        { 
            echo '</tbody></table>';
            echo 'No student matching the name, <b>' . $studentName . '</b> was found.';
        }  
        $conn->close(); //Make sure to close out the database connection

        pagination($studentName, $limit, $pageNo);
    }

    function pagination($studentName, $limit, $currPageNo)
    {
        $maxPages = 2;
        $adjacentPages = 1;
        include "conn.php";
        $sql = "SELECT
                COUNT(Role) AS NoOfResults
                FROM account A INNER JOIN student S ON A.AccountID = S.AccountID
                WHERE instr((concat_ws(' ', FirstName, LastName)), '$studentName')  ";
        
        $result = $conn->query($sql);
        $noOfRecords = $result->fetch_array(MYSQLI_ASSOC);
        echo '<div class="paging">'; 
        if ($noOfRecords['NoOfResults'] > 0)
        {
            $noOfPage = ceil($noOfRecords['NoOfResults'] / $limit); 
            //echo 'im heeeeeeeeeeeerrrrrrrrrrrrrrrrreeeeeeeeeeeeeeeeeeeeeeee ' . $noOfPage;
            if ($noOfPage == 1)
            {
                echo "<a href='profile.php?searchTxt=$studentName&pageNo=1' class='num on'>1</a>";
            }
            else if($noOfPage > 1)
            { 
                if($noOfPage > $maxPages)
                {
                    if($currPageNo != 1)
                    { 
                        echo"<a href='result.php?searchTxt=$studentName&pageNo=1' class='btn'><<</a>";
                        echo"<a href='result.php?searchTxt=$studentName&pageNo=" . $currPageNo-1 . "' class='btn'>Prev</a>";
                    } 
                    for($counter = $currPageNo-$adjacentPages+1; $counter < $currPageNo+$adjacentPages; $counter++)
                    {

                        echo "<a href='result.php?searchTxt=$studentName&pageNo=$counter' class='num on'>$counter</a>";
                    }
                    if($currPageNo != $noOfPage)
                    { 
                        echo"<a href='result.php?searchTxt=$studentName&pageNo=" . $currPageNo+1 . "' class='btn'>Next</a>"; 
                        echo"<a href='result.php?searchTxt=$studentName&pageNo=$noOfPage' class='btn'>>></a>";
                    }

                }
                else
                {
                    for($counter = 1; $counter < $noOfPage + 1; $counter++)
                    {
                        echo "<a href='result.php?searchTxt=$studentName&pageNo=$counter' class='num on'>$counter</a>";
                    }
                } 
            } 
        }      
        echo '</div>'; 
        $conn->close(); //Make sure to close out the database connection
    }
?>