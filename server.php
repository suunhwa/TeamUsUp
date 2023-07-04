<?php
session_start();

// initializing variables
$firstName = "";
$lastName ="";
$studentID = "";
$username = "";
$email    = "";
$dob = "";
$gender = "";
$errors = array(); 
$db = mysqli_connect('localhost', 'root', '1234', 'teamusup');

if (isset($_POST['Register'])) 
  {
    $firstName = mysqli_real_escape_string($db, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($db, $_POST['lastName']);
    $studentID = mysqli_real_escape_string($db, $_POST['studentID']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $gender = mysqli_real_escape_string($db, $_POST['gender']);
    $dob = mysqli_real_escape_string($db, $_POST['dob']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $enrolDate = mysqli_real_escape_string($db, $_POST['enrolDate']);
    $study = mysqli_real_escape_string($db, $_POST['study']);
    $course = mysqli_real_escape_string($db, $_POST['course']);
    if(empty($firstName))
    {
      array_push($errors, "First Name is not filled");
    }
    if(empty($lastName))
    {
      array_push($errors, "Last Name is not filled");
    }
    if(empty($studentID))
    {
      array_push($errors, "Student ID is not filled");
    }
    if(empty($email))
    {
      array_push($errors, "Email is not filled");
    }
    if(empty($dob))
    {
      array_push($errors, "Date of birth is not selected");
    }
    if(empty($gender))
    {
      array_push($errors, "Gender is not selected");
    }
    if(empty($password))
    {
      array_push($errors, "Password is not filled");
    }
    if(empty($enrolDate))
    {
      array_push($errors, "Enrolment Date is not selected");
    }
    if(empty($study))
    {
      array_push($errors, "Mode of study is not selected");
    }
    if(empty($course))
    {
      array_push($errors, "Course is not selected");
    }
    $user_check_query = "SELECT * FROM Account, Student WHERE Account.AccountID = Student.AccountID AND (Account.Email='$email' OR Student.StudNo = '$studentID') LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
  
    if ($user) 
    { // if user exists
      if ($user['Email'] === $email) 
      {
        array_push($errors, "email already exists");
      }
      if($user['StudNo']===$studentID)
      {
        array_push($errors, "student ID already exists");
      }
    }
    if (count($errors) == 0) 
    {
      $password = md5($password);//encrypt the password before saving in the database
      $query = "INSERT INTO account (firstName , lastName, dob, password, gender, role, email) 
                VALUES('$firstName', '$lastName','$dob','$password', '$gender', 'Student', '$email')";
      $query2 = "INSERT INTO student (studno, accountID , courseID, enrolDate, type) 
                      SELECT $studentID,count(*)+1, $course , '$enrolDate','$study' from student ";
      mysqli_query($db, $query);
      mysqli_query($db, $query2);

      $db->close(); 
      header("location: login.php");
    }
  }

?>




