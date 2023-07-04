<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Unit Management</title>
    <link rel="stylesheet" href="/css/aunit.css">
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
?>
<body>
    <div class="container">
            <h2>Unit Management</h2>
    
            <div class="table-box">
            <table class="unit-table">
                <thead>
                    <tr>
                        <td>Unit ID</td>
                        <td>Unit Name</td>
                        <td>Edit</td>
                    </tr>
                </thead>
                <tbody>
                <?php displayUnits();?>
                </tbody>
            </table>
        

        </div>
                    

                <div class="btn-create">
                    <input type="button" name="create" id="create" onclick="location.href='a.createunit.php'" value="Create Unit">
                </div>  
    </div>
    

</body>
</html>

<?php
function displayUnits()
{
    include "../conn.php";
    $sql = "SELECT UnitID, U.Name AS UnitName FROM Unit U";
    $result = $conn->query($sql);
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            echo'<form method="POST" action="a.editunit.php">
            <tr>
            <input type="hidden" name="unitID" value="' . $row['UnitID'] . '">
            <td>'.$row['UnitID'].'</td>
                <td>'.$row['UnitName'].'</td>
            <td><input type="submit" name="edit" class="btn-edit" value="Edit Unit"></td></tr></form>';
        }
    }
    $conn->close();
}


?>