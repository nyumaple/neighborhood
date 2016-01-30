<?php
/**
 * Created by PhpStorm.
 * User: maple
 * Date: 12/24/15
 * Time: 2:33 PM
 */
session_start();

$uid2 = $_SESSION['uid'];
$uid1 = $_GET['fid'];
$operation =  $_GET['op'];

$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

if($operation == "approve") {
    $stmt1 = $mysqli->prepare("insert into friend values(?,?)");
    $stmt1->bind_param("dd",$uid1,$uid2);
    $stmt1->execute();
    $stmt1->close();

    $stmt2 = $mysqli->prepare("insert into friend values(?,?)");
    $stmt2->bind_param("dd",$uid2,$uid1);
    $stmt2->execute();
    $stmt2->close();

    $status = 'approved';
    $stmt3 = $mysqli->prepare("delete from applyfriend where uid1 = ? and uid2 = ?");
   // $stmt3 = $mysqli->prepare("delete from friend where uid1 = ? and uid2 = ?");
    $stmt3->bind_param("dd",$uid1,$uid2);
    $stmt3->execute();
    $stmt3->close();
}
else if($operation == "decline"){
    $stmt3 = $mysqli->prepare("delete from applyfriend where uid1 = ? and uid2 = ?");
    $status = 'declined';
    $stmt3->bind_param("dd",$uid1,$uid2);
    $stmt3->execute();
    $stmt3->close();
}

?>