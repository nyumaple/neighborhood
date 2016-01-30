<?php
/**
 * Created by PhpStorm.
 * User: maple
 * Date: 12/15/15
 * Time: 8:13 PM
 */

session_start();

$bid = $_POST['bid'];// 传过来的朋友名字
$uid = $_SESSION['uid'];

$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

$stmt2 = $mysqli->prepare("insert into applyblock values (?,?) ");
$stmt2->bind_param("ii",$uid,$bid);
$stmt2->execute();
$stmt2->close();

echo "<script language='javascript'> alert('apply successfully'); </script>";
echo "<script language='javascript'> window.location= 'main.php'; </script>";
?>