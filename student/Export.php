<?php
session_start();
// Include autoloader 
require_once 'dompdf/autoload.inc.php'; 
 
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 
 
// Instantiate and use the dompdf class 
$dompdf = new Dompdf();

$user = "";
if (isset($_POST['export']))
{
    /*array_to_csv_download( // this array is going to be the second row
        "Teamwork History.csv"
      );*/
    
    include "conn.php";

    $sql = "SELECT (concat_ws(' ', A.FirstName, A.LastName)) AS Fullname, C.Name AS CourseName, S.Biography, S.OverallRating, S.EnrolDate
            FROM teamusup.account A INNER JOIN (student S INNER JOIN course C ON S.CourseID=C.CourseID) ON A.AccountID=S.AccountID 
            WHERE S.StudNo = '".$_SESSION['userSNo']."'"; 
    $sql2 ="SELECT (concat_ws(' ', A.FirstName, A.LastName)) AS reviewBy, R.Comment, R.Rating, U.Name AS UnitName, Ass.Name AS AssName
            FROM review R
            INNER JOIN (teamusup.group G INNER JOIN (assignment Ass INNER JOIN (offering O INNER JOIN unit U ON U.UnitID = O.UnitID) ON Ass.OffID = O.OffID) ON G.AssID = Ass.AssID)
            INNER JOIN
            (account A INNER JOIN student S ON A.AccountID = S.AccountID) ON R.ByStudNo = S.StudNo
            WHERE R.ToStudNo = '".$_SESSION['userSNo']."'
            AND R.Display = 'Accepted'
            AND R.GroupID = G.GroupID";
    $conn->query($sql);
    $conn->query($sql2);
    $result = $conn->query($sql);
    $result2 = $conn->query($sql2);
    $row = $result->fetch_assoc();
    $row2 = $result->fetch_assoc();
    $fullname = $row["Fullname"];  
    $strHtml = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TeamUsUp - Edit Group</title>
        <style>  
            * {
                margin: 0;
                padding: 0;
            }
            
            .container {
                margin-top: 5px;
               margin-left: 10px;
            }
            
            .cgrp-box {
                margin-top: 20px;
                display: flex;
            }
            
            .left, .mem {
                list-style: none;   
                float: left;
            }
            
            .mem {
                margin-left: 120px;
            }
            
            li .reg {
                font-size: 17px;
                width: 130px;
                color: black;
                font-weight: 400;
                float: left;
                text-align: left;
                margin-top: 30px;
            }
            
            
            li #unit, #assign, #semester, #year {
                width: 300px;
                height: 40px;
                padding: 8px;
                margin-top: 30px;
                border: 0.7px solid rgba(53, 51, 51, 0.74);
                border-radius: 7px;
            }
            
            li label[for="mem"], .addStud{
                font-size: 17px;
                width: 180px;
                color: black;
                font-weight: 400;
                float: left;
                text-align: left;
                margin-top: 30px;
            }
            
            li #mem {
                width: 270px;
                height: 40px;
                padding: 8px;
                margin-top: 30px;
                border: 0.7px solid rgba(53, 51, 51, 0.74);
                border-radius: 7px;
            } 

            
            .unit-table table{
                margin-left: 100px;
            }

            .unit-table thead{
                border-top: 2px solid rgba(255, 153, 0, 0.432);
                border-bottom: 2px solid rgba(255, 153, 0, 0.432);
            }

            .unit-table thead td {
                position: sticky;
                top: 0;
                color: black;
                font-size: 18px; 
                font-weight: 700;
            } 
            
            .unit-table td {
                padding: 15px 30px;
                font-size: 18px;
                color: #222;
            }
            .unit-table tr {
                border: 2px solid  #000000;
            }
            
            /*  Define the background color for all the ODD background rows  */
            .unit-table tr:nth-child(odd){ 
                background: #ffffff;
            }
            /*  Define the background color for all the EVEN background rows  */
            .unit-table tr:nth-child(even){
                background: #ffffff;
            }
             
            .unit-table th {
                background: #ffe4c4;
            }
            
    
            </style>
    </head>  <body>  
    ';  
    $strHtml .= '<table class="unit-table" cellspacing="0" cellpadding="5" width="90%" align="center" border="0"><tbody>'.   
                    '<tr><td><h1>'. $fullname .'</h1></td></tr>'.
                    '<tr><td>Course: '.  $row["CourseName"] .'</td></tr>'. 
                    '<tr><td>Enroled On: '.  $row["EnrolDate"] .'</td></tr>'.
                    '<tr><td>Biography: '.  $row["Biography"] .'</td></tr>'. 
                '</tbody></table>';  

    $strHtml .= '<br><br>'.
                '<div class="container"><h2>Reviews</h2>'.
                '<br><strong>Overall Ratings: <strong>' . $row["OverallRating"]  . '</strong>'.
                '<table class="unit-table" cellspacing="0" cellpadding="5" width="90%" align="center" border="0">'.
                
                '<tbody>';
    while($row2 = $result2->fetch_array(MYSQLI_ASSOC))
    {
        $Reviewee = $row2["reviewBy"];
        $Comment = $row2["Comment"];
        $Rating = $row2["Rating"];
        $Unit = $row2["UnitName"];
        $Assignment = $row2["AssName"];
        $strHtml .= '<tr>
                        <td>'. $Reviewee .'</td>
                        <td>'. $Comment .'</td>
                        <td>'. $Rating .'</td>
                        <td>'. $Unit .'</td>
                        <td>'. $Assignment .'</td>
                    </tr>';  
    }
    $strHtml .= '</tbody>';  
    $strHtml .= '</table></div>';  
    
    $strHtml .= '</body></html>'; 

//$strHtml = file_get_contents("test.html");
// Load content from html file 
$dompdf->loadHtml($strHtml); 
 
// (Optional) Setup the paper size and orientation 
$dompdf->setPaper('A4', 'potrait'); 
 
// Render the HTML as PDF 
$dompdf->render(); 
$dompdf->set_base_path('/css/manageGroup2.css');
 
// Output the generated PDF (1 = download and 0 = preview) 
$dompdf->stream("test", array("Attachment" => 1));
}



/*function array_to_csv_download( $filename = "export.csv") {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');

$servername = "localhost";
$username = "root";
$password = "1234";
$database = "TeamUsUp";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "Select (concat_ws(' ', a.firstName, a.lastName)) as Fullname 
    from account a, student s 
    where a.accountid = s.accountid
    and s.studno = '".$_SESSION['userSNo']."'"; 
    $sql2 = "SELECT (concat_ws(' ', A.FirstName, A.LastName)) AS reviewBy, R.Comment, R.Rating, U.Name AS UnitName, Ass.Name AS AssName
    FROM review R
    INNER JOIN (teamusup.group G INNER JOIN (assignment Ass INNER JOIN (offering O INNER JOIN unit U ON U.UnitID = O.UnitID) ON Ass.OffID = O.OffID) ON G.AssID = Ass.AssID)
    INNER JOIN
    (account A INNER JOIN student S ON A.AccountID = S.AccountID) ON R.ByStudNo = S.StudNo
    WHERE R.ToStudNo = '".$_SESSION['userSNo']."'
    AND R.Display = 'Accepted'
    AND R.GroupID = G.GroupID";
    $result = $conn->query($sql);
    $result2 = $conn->query($sql2);
    $list = array('Reviewee', 'Comment', 'Rating out of 5', 'Unit Name', 'Assignment Name');
    $f = fopen('php://output', 'w');
    $row = $result->fetch_array(MYSQLI_ASSOC);
    fputcsv($f, $row);
    fputcsv($f, $list);

    while($row2 = $result2->fetch_array(MYSQLI_ASSOC))
            {
                fputcsv($f, $row2);
            }
    fclose($f);
    die();
}*/
?>