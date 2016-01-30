<?php
/**
 * Created by PhpStorm.
 * User: maple
 * Date: 12/15/15
 * Time: 7:15 PM
 */
session_start();
$uid = $_POST['uid'];

$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}
$stmt = $mysqli->prepare("select uid,uname,address,profile,bname,from user natural join block where uid = ?");
$stmt->bind_param("d",$uid);
$stmt->execute();
$result = $stmt->get_result();

?>


