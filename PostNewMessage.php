<?php
/**
 * Created by PhpStorm.
 * User: maple
 * Date: 12/24/15
 * Time: 4:09 PM
 */
session_start();
date_default_timezone_set('America/New_York');

$tid = $_POST['tid'];
$uid = $_SESSION['uid'];
$title = $_POST['title'];
$content = $_POST['textbody'];
$time = date('y-m-d h:i:s',time());
$address = $_POST['address'];


$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

$stmt = $mysqli->prepare("update sendTo set status='unread' where tid =?");
$stmt->bind_param("i",$tid);
$stmt->execute();
$stmt->close();

$stmt = $mysqli->prepare("insert into message(uid,title,textbody,timestamp,address,tid) values(?,?,?,?,?,?)");
$stmt->bind_param("issssi",$uid,$title,$content,$time,$address,$tid);
$stmt->execute();
$stmt->close();

echo "<script language='javascript'> window.location= 'thread.php?tid=$tid'; </script>";

?>