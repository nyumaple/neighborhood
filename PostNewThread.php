<?php
/**
 * Created by PhpStorm.
 * User: maple
 * Date: 12/15/15
 * Time: 6:52 PM
 */
session_start();
date_default_timezone_set('America/New_York');

$uid = $_SESSION['uid'];
$title = $_POST['title'];
$content = $_POST['content'];
$time = date('y-m-d h:i:s',time());
$address = $_POST['address'];
$to = $_POST['feed'];
$friend_name = $_POST['onefriend'];

$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}


$stmt1 = $mysqli->prepare("insert into thread(uid,title,content,address,timestamp) values (?,?,?,?,?)");
$stmt1->bind_param("issss",$uid,$title,$content,$address,$time);
$stmt1->execute();
$tid = $stmt1->insert_id;
$stmt1->close();

$stmt8 = $mysqli->prepare("insert into message(uid,title,textbody,timestamp,tid) values (?,?,?,?,?)");
$stmt8->bind_param("isssi",$uid,$title,$content,$time,$tid);
$stmt8->execute();
$stmt8->close();


if($to == 'friend'){
    $stmt = $mysqli->prepare("select uid from user where uname = ?");
    $stmt->bind_param("s",$friend_name);
    $stmt->execute();
    $ret = $stmt->get_result();
    $stmt->close();
    $row = $ret->fetch_row();
    $fid = $row[0];
    $type = 'friend';
    $stmt2 = $mysqli->prepare("insert into sendTo(tid,uid,kind,status) values(?,?,?,'unread')");
    $stmt2->bind_param("iis",$tid,$fid,$type);
    $stmt2->execute();
    $stmt2->close();
    $stmt2 = $mysqli->prepare("insert into sendTo(tid,uid,kind,status) values(?,?,?,'unread')");
    $stmt2->bind_param("iis",$tid,$uid,$type);
    $stmt2->execute();
    $stmt2->close();
}
else if($to == 'allfriends'){
    $stmt = $mysqli->prepare("select uid2 from friend where uid1 = ?");
    $stmt->bind_param("i",$uid);
    $stmt->execute();
    $ret = $stmt->get_result();
    $stmt->close();
    $type = 'allfriends';
    $stmt2 = $mysqli->prepare("insert into sendTo(tid,uid,kind,status) values(?,?,?,'unread')");
    $stmt2->bind_param("iis",$tid,$uid,$type);
    $stmt2->execute();
    $stmt2->close();
    while($row = $ret->fetch_row()){
        $fid = $row[0];
        $type = 'allfriends';
        $stmt2 = $mysqli->prepare("insert into sendTo(tid,uid,kind,status) values(?,?,?,'unread')");
        $stmt2->bind_param("iis",$tid,$fid,$type);
        $stmt2->execute();
        $stmt2->close();
    }
}
else if($to == 'block'){
    $stmt = $mysqli->prepare("select bid from user where uid = ?");
    $stmt->bind_param("i",$uid);
    $stmt->execute();
    $ret = $stmt->get_result();
    $stmt->close();
    $row = $ret->fetch_row();
    $bid = $row[0];
    $stmt2 = $mysqli->prepare("select uid from user where bid = ?");
    $stmt2->bind_param("i",$bid);
    $stmt2->execute();
    $ret = $stmt2->get_result();
    $stmt2->close();
    $type = 'block';
    while($row = $ret->fetch_row()){
        $ubid = $row[0];
        $stmt3 = $mysqli->prepare("insert into sendTo(tid,uid,kind,status) values(?,?,?,'unread')");
        $stmt3->bind_param("iis",$tid,$ubid,$type);
        $stmt3->execute();
        $stmt3->close();
    }
}
else if($to == 'hood'){
    $stmt = $mysqli->prepare("select bid from user where uid = ?");
    $stmt->bind_param("i",$uid);
    $stmt->execute();
    $ret = $stmt->get_result();
    $stmt->close();
    $row = $ret->fetch_row();
    $bid = $row[0];
    echo "<script language='javascript'> alert($bid); </script>";

    $stmt2 = $mysqli->prepare("select hid from block where bid = ?");
    $stmt2->bind_param("i",$bid);
    $stmt2->execute();
    $ret = $stmt2->get_result();
    $stmt2->close();
    $row = $ret->fetch_row();
    $hid = $row[0];

    echo "<script language='javascript'> alert($hid); </script>";

    $stmt3 = $mysqli->prepare("select uid from user,block where block.bid = user.bid and hid = ?");
    $stmt3->bind_param("i",$hid);
    $stmt3->execute();
    $ret = $stmt3->get_result();
    $stmt3->close();

    echo "<script language='javascript'> alert($ret->num_rows); </script>";

    $type = 'hood';
    while($row = $ret->fetch_row()){
        $unid = $row[0];
        $stmt3 = $mysqli->prepare("insert into sendTo(tid,uid,kind,status) values(?,?,?,'unread')");
        $stmt3->bind_param("iis",$tid,$unid,$type);
        $stmt3->execute();
        $stmt3->close();
    }

}

echo "<script language='javascript'> window.location= 'main.php'; </script>";

?>