<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Create Unit</title>
    <link rel="stylesheet" href="/css/a.createunit.css">
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
    <h2>Create Unit</h2>
        <form method = "POST" class="cunit">
            <li>
                <label class="details" for="unitid">Unit ID</label>
                <input type="text" name="unitid" required></input> 
            </li>
            
            <li>
                <label class="details" for="name">Name</label>
                <input type="text" name="name" required></input> 
            </li>
        
            <li>
                <label class="details" for="credit">Credit</label>
                <input type="number" name="credit" required></input> 
            </li>
            
            <li>
                <label class="details" for="desc">Description</label>
                <textarea name="desc"></textarea>
            </li>

            <div class="btn-save">
                <input type="submit" name="save" id="save" value="Save">
            </div>
        </form>
    </div>
    

</body>
</html>

<?php 
if (isset($_POST['save'])) 
{
    createUnit();      
}
function createUnit()
{
    include "../conn.php"; 
    $unitID = $_POST['unitid'];
    $name = htmlspecialchars($_POST['name']);
    $credit = $_POST['credit'];
    $description = $_POST['desc'];
    $sql = "INSERT INTO Unit (UnitID, Name, `Desc`, Credit)
    VALUES ('$unitID','$name', '$description','$credit')";
    mysqli_query($conn, $sql);
    
    $conn->close();
    echo '<meta http-equiv="refresh" content="0; URL=aunit.php">';

}
?>