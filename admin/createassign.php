<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Assignment Management</title>
    <link rel="stylesheet" href="/css/createassign.css">
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
    <h2>Create Assignment</h2>
        <form method = "POST" class="assign">

            <li>
            <div class="input-box">
                        <span class="details">Unit</span>
                        <select name="offering" id="offering">
                            <option value="default" default>--No selection--</option>
                            <?php 
                                fillUnit($curSem);
                            ?>
                        </select>   
            </li>
            <br>
            <li>
                <label class="details" for="name">Name</label>
                <input type="text" name="name"required></input> 
            </li>
            
            <li>
                <label class="details" for="desc">Description</label>
                <textarea name="desc"required></textarea>
            </li>

            <div class="btn-save">
                <input type="submit" name="save" id="save" value="Save">
            </div>
        </form>
    </div>
    

</body>
</html>

<?php
    function fillUnit($curSem)
    { 
        include ('../conn.php');

        $sql = "SELECT O.OffID, U.Name AS UnitName
                FROM unit U, offering O
                WHERE U.UnitID = O.UnitID
                AND O.Semester = '" . $curSem . "'
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
                echo "<option value='". $row['OffID'] . "'>" . 
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
        setAssignment();      
    }
    function setAssignment()
    {
        include "../conn.php"; 
        $offID = $_POST['offering'];
        $name = htmlspecialchars($_POST['name']);
        $description = $_POST['desc'];
        $sql = "INSERT INTO Assignment (OffID, name, `Desc`)
        VALUES ('$offID','$name', '$description')";
        mysqli_query($conn, $sql);
        
        $conn->close();
        echo '<meta http-equiv="refresh" content="0; URL=assignment.php">';

    }
    ?>