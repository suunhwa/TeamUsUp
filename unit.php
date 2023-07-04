<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Unit</title>
    <link rel="stylesheet" href="/css/unit.css"> 
    
</head>  


<body>
<header><?php include "header.php"?></header>
        <section class="margin">
    <div class="container"> 
        <h2>Units</h2>
        <div class="table-box">
            <table class="unit-table">
                <thead>
                    <td>Unit ID</td>
                    <td>Unit Name</td>
                    <td>Description</td>
                    <td>Credits</td>
                    <td></td>
                </thead>
                <tbody>
                    <?php
                        displayAllUnits();
                    ?>   
                </tbody>
            </table> 
        </div>
    </div> 
 
    <?php 
        if(isset($_GET['unitID']))
        {
            displaySelUnit($_GET['unitID']);
        } 
    ?> 
    <div class="container">
            
        </div>
    </section> 
      

  <footer><?php include "footer.php"?></footer>
</body>
</html>


<?php 

    function displayAllUnits()
    {
        include "conn.php"; 
        $sql = "SELECT U.UnitID, U.Name AS UnitName, U.Desc AS UnitDesc, U.Credit
                FROM unit U
                WHERE U.Status = 'Available'";
        $result = $conn->query($sql);

        if($result->num_rows > 0)
        { 
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {    
                echo '<tr>
                        <form method="GET">
                            <td>'.$row['UnitID'].'</td>
                            <td>'.$row['UnitName'].'</td>
                            <td>'.$row['UnitDesc'].'</td>
                            <td class="credit">'.$row['Credit'].'</td>
                            <td><input type="hidden" name="unitID" value="'.$row['UnitID'].'"><input type="submit" name="selCourse" class="btn btn-info" value="Select"></td>
                        </form>
                        </tr>';   
            }   
        }
        $conn->close(); //Make sure to close out the database connection 
    }
 

    function displaySelUnit($unitID)
    {
        include "conn.php"; 
        $sql = "SELECT U.UnitID, U.Name AS UnitName, U.Desc, U.Credit
                FROM unit U
                WHERE U.UnitID='$unitID'";
        $result = $conn->query($sql);

        if($result->num_rows == 1)
        { 
            $resultUnit = $result->fetch_array(MYSQLI_ASSOC);
            echo '
            <div class="container">
            <h3>'.$resultUnit['UnitID'] . '  ' . $resultUnit['UnitName'] . '</h3><br> '.  
            '<h4>Description: '.$resultUnit['Desc'] . '<br></h4>'.
            '<h5>Credits: '.$resultUnit['Credit'] . '<br></h5>' .'
            <div class="table-box">
                <table class="unit-table">
                    <thead>  
                        <td>Semester</td>
                        <td>Class</td>      
                     </thead>
                     <tbody>';
            $sqlOffering = " SELECT O.Semester, O.Class
                                FROM offering O
                                WHERE O.UnitID='$unitID'";

            $resultOffering = $conn->query($sqlOffering);
            if($resultOffering->num_rows > 0)
            { 
                while($row = $resultOffering->fetch_array(MYSQLI_ASSOC))
                {    
                    echo '  <tr> 
                                <td class="sem">'.$row['Semester'].'</td>
                                <td class="class">'.$row['Class'].'</td>  
                            </tr>';   
                }   
            } 
            else
            {
                echo 'No Units Offered.';
            }
            echo'    </tbody>
                </table>
            </div>';   
        }
        else
        {
            echo 'Select a Course to display more information.';
        }
        $conn->close(); //Make sure to close out the database connection  
    }
 
?>