<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Create Course</title>
    <link rel="stylesheet" href="/css/a.createcourse.css">
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
    <h2>Create Course</h2>
        <form method = "POST" class="course">
            <li>
                <label class="details" for="name">Name</label>
                <input type="text" name="name" required></input> 
            </li>            
            <li>
                <label class="details" for="desc">Description</label>
                <textarea name="desc"></textarea>
            </li>

            <li>
            <?php
            include "../conn.php";
            $result = mysqli_query($conn, "SELECT * FROM Unit");
            ?>
            <label class="details" for="units">Select Units</label><br>
            <select name="Units[]" id="units" size="10" multiple="multiple">
            <?php
            while($row = mysqli_fetch_array($result)) {
            ?>
            <option value="<?=$row["UnitID"];?>"><?=$row["UnitID"];?></option>
            <?php
            }
            ?>
            </select>
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
        setCourse();      
    }

    function setCourse()
    {
        include "../conn.php"; 
        $name = htmlspecialchars($_POST['name']);
        $description = $_POST['desc'];
        $sql = "INSERT INTO course (name, `Desc`)
        VALUES ('$name', '$description')";
        mysqli_query($conn, $sql);
        $choices= $_POST['Units'];
        if(isset($choices))
        {
            foreach($choices as $value)
            {
                $sql2 = "INSERT INTO COURSEUNIT (COURSEID, UNITID)
                      SELECT count(*),'$value' from Course";
                mysqli_query($conn, $sql2);
            }
        }
        $conn->close();
        echo '<meta http-equiv="refresh" content="0; URL=acourse.php">';

    }
    ?>