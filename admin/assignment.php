<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Assignment Management</title>
    <link rel="stylesheet" href="/css/assignment.css">
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
        <h2>Assignment Management</h2>
        <p>Current Semester Units</p>
            <div class="table-box">
                <table>
                <colgroup>
                        <col style="width:100px">
                        <col>
                        <col style="width:150px">
                        <col style="width:120px">
                        <col style="width:100px">
                    </colgroup>
                    <thead>
                        <tr>
                            <td>Unit</td>
                            <td>Assignment</td>
                            <td>Select</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php displayAssignments()?>
                        
                    </tbody>
                </table>
            </div>

                <div class="btn-create">
                    <input type="button" name="create" id="create" onclick="location.href='createassign.php'" value="Create Assignment">
                </div>
    </div>
    

</body>
</html>

<?php 

function displayAssignments()
{
    include "../conn.php";
    $sql = "SELECT A.AssID, U.Name AS UnitName, A.Name AS AssName
    FROM Unit U, Offering O, Assignment A
    WHERE U.UnitID = O.UnitID
    AND O.OffID = A.OffID";
    $result = $conn->query($sql);
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            echo '<form method="POST" action="editassign.php">
            <tr>
            <input type="hidden" name="assID" value="' . $row['AssID'] . '">
            <td>'.$row['UnitName'].'</td>
            <td>'.$row['AssName'].'</td>
            <td><input type="submit" name="edit" class="btn-edit" value="Edit Assignment"></td></tr></form>';
        }
    }



}


?>