<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Add Offering</title>
    <link rel="stylesheet" href="/css/addoffering.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<?php include "admin.php";
    if ($_SESSION['role'] == 'Admin')
    {

    }
    else
    {
        echo '<meta http-equiv="refresh" content="0; URL=/home.php">';  
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
    <div class="container">
        <h2>Add Offering</h2>
        <form method = "POST">
            <li>
                <p>Current Semester  <?php echo $curSem;?></p>

            </li>
            <div class="unit-box">
            <br>

            <span class="details">Unit</span>

                        <select name="unit" id="unit">
                            <option value="default" default>--No selection--</option>
                            <?php 
                                fillUnit();
                            ?>
                        </select>
                        <br>
                        <br>
                        <br>
                        <li>
                <label class="details" for="class">Class (A-Z)</label>
                <textarea name="class" required></textarea>
            </li>

            </div>
            <div class="btn-save">
                <input type="submit" name="save" id="save" value="Save">
            </div>
        </form>
    </div>
    

</body>
</html>

<?php
    function fillUnit()
    { 
        include ('../conn.php');

        $sql = "SELECT Distinct U.UnitID, U.Name AS UnitName
                FROM unit U
                ";
        
        //echo $sql;
        $result = $conn->query($sql);
        //echo " Query has been executed";  
        //echo $result->num_rows;
        if($result->num_rows > 0)
        {
            //echo "Im course here 1"; 
            echo "<table>"; // start a table tag in the HTML
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                echo "<option value='". $row['UnitID'] . "'>" . 
                $row['UnitName'] . "</option>"; 
            }  
            echo "</table>"; //Close the table in HTML
        }
        else
        {
            //echo "Im course here 2"; 
            echo 'Error';
        }  
        $conn->close(); //Make sure to close out the database connection
    }

    if (isset($_POST['save'])) 
    {
        setOffering($curSem);      
    }
    function setOffering($curSem)
    {
        include "../conn.php"; 
        $unitid = $_POST['unit'];
        $class = htmlspecialchars($_POST['class']);
        $sql = "INSERT INTO Offering (Semester, UnitID, Class)
        VALUES ('$curSem','$unitid', '$class')";
        mysqli_query($conn, $sql);
        
        $conn->close();
        echo '<meta http-equiv="refresh" content="0; URL=offering.php">';

    }
    ?>