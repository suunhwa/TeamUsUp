<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Assignment Management</title>
    <link rel="stylesheet" href="/css/editassign.css">
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
    <h2>Edit Assignment</h2>
        <form class="assign"method="POST">
        <?php fillDetails($_POST['assID']);?>  

            <div class="btn-save">
                <input type="submit" name="save" id="save" value="Save">
            </div>
            <div class="btn-delete">
                <input type="submit" name="delete" id="delete" onclick="location.href='#'" value="Delete Assignment">
            </div>
        </form>
    </div>
    

</body>
</html>


<?php
function fillDetails($AssID)
{
    include "../conn.php";
    $sql = "SELECT A.AssID, A.Name, A.Desc
            FROM ASSIGNMENT A
            WHERE AssID = '$AssID'";
    $result = $conn->query($sql);
    if($result->num_rows > 0)
    {
    while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
        echo'<li>
                <label class="details" for="name">Name</label>
                <input type="hidden" name="AssID" value="'.$AssID.'">
                <input type="text" id="name" name="name" required value = "'.$row['Name'].'"></input> 
            </li>
            <li>
                <label class="details" for="desc">Description</label>
                <textarea name="desc">'.$row['Desc'].'</textarea>
            </li>';
        }
    }
}

if (isset($_POST['delete'])) 
    {
        deleteAssignment($_POST['AssID']);      
    }
function deleteAssignment($AssID)
{
    include "../conn.php"; 
    $sql1 = "DELETE FROM ASSIGNMENT
            WHERE AssID = '$AssID'";
        mysqli_query($conn, $sql1);
        $conn->close();
        echo '<meta http-equiv="refresh" content="0; URL=assignment.php">';
}
if(isset($_POST['save']))
{
    updateCourse($_POST['AssID']);
}
function updateCourse($AssID)
{
    include "../conn.php"; 
    $name = htmlspecialchars($_POST['name']);
    $description = $_POST['desc'];

    $sql = "UPDATE ASSIGNMENT A
            SET A.name = '$name',  A.desc = '$description'
            WHERE AssID = '$AssID'";
            mysqli_query($conn, $sql);
    $conn->close();
    echo '<meta http-equiv="refresh" content="0; URL=assignment.php">';
}


?>