<?php
// Fill up array with names
$uname=$_GET["uname"];

$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

$stmt1 = $mysqli->prepare("select uname from user where uname =? ");
$stmt1->bind_param("s",$uname);
$stmt1->execute();
$result = $stmt1->get_result();
$stmt1->close();

//Set output to "no suggestion" if no hint were found
//or to the correct values

if ($result->num_rows == 0)
{
    $response="";
}
else
{
    $response="already used!";
}

//output the response
echo $response;


?>