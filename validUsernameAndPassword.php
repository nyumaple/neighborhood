<?php
// Fill up array with names
$uname=$_POST["uname"];
$pass=$_POST["pass"];

$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

$stmt1 = $mysqli->prepare("select uid from user where uname =? and password=?");
$stmt1->bind_param("ss",$uname,$pass);
$stmt1->execute();
$result = $stmt1->get_result();
$stmt1->close();
echo $result->num_rows;
//Set output to "no suggestion" if no hint were found
//or to the correct values
$row = $result->fetch_row();

if ($result->num_rows == 0)
{
    echo "<script language='javascript'> alert('invalid username and password');</script>";
    echo "<script language='javascript'> window.location= 'signin.php'; </script>";
    exit;
}
else
{
    session_start();
    $_SESSION['uid'] = $row[0];
    $_SESSION['uname'] = $uname;
    echo "<script language='javascript'> window.location= 'main.php'; </script>";
}

//output the response
echo "great";


?>
