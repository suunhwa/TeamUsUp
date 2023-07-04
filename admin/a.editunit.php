<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Edit Unit</title>
    <link rel="stylesheet" href="/css/a.editunit.css">
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
    <h2>Edit Unit</h2>
        <form class="cunit" method = "POST">
        <?php fillDetails($_POST['unitID']);?>  

            <div class="btn-save">
                <input type="submit" name="save" id="save" value="Save">
            </div>

            <div class="btn-delete">
                <input type="submit" name="delete" id="delete" onclick="location.href='#'" value="Delete Unit">
            </div>
        </form>
    </div>
    

</body>
</html>

<?php 
function fillDetails($unitID)
{
    include "../conn.php"; 
    $sql = "SELECT U.UnitID, U.Name, U.Credit, U.Status, U.Desc
            FROM UNIT U
            WHERE UnitID = '$unitID'";
        $result = $conn->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                echo ' 
            <li>
                <label class="details" for="unitid">Unit ID</label>
                <input type="hidden" name="UnitID" value="'.$unitID.'">
                <input type="text" id="unitid" name="unitID" required value = "'.$row['UnitID'].'"></input>
            </li>

            <li>
                <label class="details" for="name">Name</label>
                <input type="text" id="name" name="name" required value = "'.$row['Name'].'"></input>
            </li>

            <li>
                <label class="details" for="credit">Credit</label>
                <input type="number" id="credit" name="credit" required value = "'.$row['Credit'].'"></input> 
            </li>

            <div class="status">
                <li>
                <label class="details" for="status">Status</label>
                    <label class="switch">';
                    if($row['Status'] == 'Available')
                    {
                        echo '<input type="checkbox" name ="status" checked>';
                    }
                    else{
                        echo '<input type="checkbox" name ="status" >';
                    }
                        echo '<span class="slider"></span>
                    </label>
                </li>
            </div>
            <li>
                <label class="details" for="desc">Description</label>
                <textarea name="desc">'.$row['Desc'].'</textarea>
            </li>

            ';
            }
        }

}

if (isset($_POST['delete'])) 
    {
        deleteUnit($_POST['UnitID']);      
    }
function deleteUnit($UnitID)
{
    include "../conn.php"; 
    $sql1 = "DELETE FROM Unit
            WHERE UnitID = '$UnitID'";
        mysqli_query($conn, $sql1);
        $conn->close();
        echo '<meta http-equiv="refresh" content="0; URL=aunit.php">';
}
if(isset($_POST['save']))
{
    updateCourse($_POST['UnitID']);
}
function updateCourse($UnitID)
{
    include "../conn.php"; 
    $UID = htmlspecialchars($_POST['unitID']);
    $name = htmlspecialchars($_POST['name']);
    $credit = $_POST['credit'];
    $status = "Hidden";
    $description = $_POST['desc'];
    if(isset($_POST['status']))
    {
        $status = "Available";
    }

    $sql = "UPDATE UNIT U
            SET U.UnitID = '$UID', U.name = '$name', U.Credit = '$credit', U.desc = '$description', U.status = '$status'
            WHERE UnitID = '$UnitID'";
            mysqli_query($conn, $sql);
    $conn->close();
    echo '<meta http-equiv="refresh" content="0; URL=aunit.php">';
}
?>