<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>1st page</title>
    </head>
    <body>
        
        <?php
            
            $servername = "localhost";
            $username = "root";
            $password = "1234!";
            $database = "TeamUsUp";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) 
            {
                die("Connection failed: " . $conn->connect_error);
            } 

            echo "<table>"; // start a table tag in the HTML

            

            echo "</table>"; //Close the table in HTML

            $conn->close(); //Make sure to close out the database connection
 
        ?> 

        <form action = "register.php" method = "post">
            
        </form>

    </body>

</html>