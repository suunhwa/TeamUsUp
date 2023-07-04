<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Offering Management</title>
    <link rel="stylesheet" href="/css/offering.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<?php include "admin.php";
    if ($_SESSION['role'] == 'Admin')
    {

    }
    else
    {
        echo '<meta http-equiv="refresh" content="0; URL=home.php">';  
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
        <h2>Offering Management</h2>
        <form method="POST">
            <li>
                <p>Current Semester <?php echo $curSem;?></p>
            </li>
            <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <td>Unit ID</td>
                        <td>Unit Name</td>
                        <td>Class</td>
                        <td>Remove</td>
                    </tr>
                </thead>
                <tbody>
                <?php displayOffering($curSem) ?>
            </table>
            </div>
            <div class="btn-add">
                    <input type="button" name="add" id="add" onclick="location.href='addoffering.php'" value="Add Offering">
                </div>
        </form>
    </div>
    

</body>
</html>
<?php 
function displayOffering($curSem)
{
    include "../conn.php";
    $sql = "SELECT O.OffID, U.UnitID, U.Name, O.Class
            FROM Unit U, Offering O
            WHERE U.UnitID = O.UnitID
            AND O.Semester = '$curSem'";
    $result = $conn->query($sql);
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            echo '
            <tr>
                <input type="hidden" name="OffID" value="'.$row['OffID'].'">
                <td>'.$row['UnitID'].'</td>
                <td>'.$row['Name'].'</td>
                <td>'.$row['Class'].'</td>
                <td><input type="submit" name="remove" class="btn btn-info" value="Remove Offering"></td></form></tr>
    
            ';
        }
    }
    $conn->close();
}
if (isset($_POST['remove'])) 
    {
        deleteOffering($_POST['OffID']);      
    }
function deleteOffering($offID)
{
    include "../conn.php"; 
    $sql1 = "DELETE FROM offering
            WHERE OffID = '$offID'";
        mysqli_query($conn, $sql1);
        $conn->close();
        echo '<meta http-equiv="refresh" content="0; URL=offering.php">';

}
?>