<?php
/**
 * Created by PhpStorm.
 * User: maple
 * Date: 12/15/15
 * Time: 6:18 PM
 */
session_start();

$uid = $_SESSION['uid'];
$address = $_POST['padd'];
$profile = $_POST['profile'];


$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

$stmt1 = $mysqli->prepare("update user set address = ?, profile = ? where uid = ?");
$stmt1->bind_param("ssd",$address,$profile,$uid);
$stmt1->execute();
$stmt1->close();

?>