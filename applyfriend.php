<?php
/**
 * Created by PhpStorm.
 * User: maple
 * Date: 12/15/15
 * Time: 8:01 PM
 */
session_start();
$fid = $_GET['fid'];// 传过来的朋友id
$uid = $_SESSION['uid'];

$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}
$stmt1 = $mysqli->prepare("insert into applyfriend values (?,?) ");
$stmt1->bind_param("ii",$uid,$fid);
$stmt1->execute();
$stmt1->close();

echo "<script language='javascript'> alert('apply successfully'); </script>";

?>