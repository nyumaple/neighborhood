<?php
/**
 * Created by PhpStorm.
 * User: maple
 * Date: 12/24/15
 * Time: 2:48 PM
 */
session_start();

$acceptuid = $_SESSION['uid'];
$uid = $_GET['uid'];
$bid = $_GET['bid'];
echo "<script>alert('$bid')</script>";
$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

$stmt = $mysqli->prepare("select bid from user where uid = ?");
$stmt ->bind_param("i",$acceptuid);
$stmt->execute();
$ret = $stmt->get_result();
$row = $ret->fetch_row();
$stmt->close();

if($row[0]==$bid) {

    $stmt1 = $mysqli->prepare("select *from acceptmember where uid = ? and acceptuid = ? and bid = ?");
    $stmt1 -> bind_param("iii",$uid,$acceptuid,$bid);
    $result = $stmt1->get_result();



        $stmt2 = $mysqli->prepare("insert into acceptmember values (?,?,?)");
        $stmt2->bind_param("iii", $uid, $bid, $acceptuid);
        $stmt2->execute();
        $stmt2->close();

        $stmt3 = $mysqli->prepare("select count(*) from acceptmember where uid = ? and bid =?");
        $stmt3->bind_param("ii",$uid,$bid);
        $stmt3->execute();
        $numOfAcceptance = $stmt3->get_result()->fetch_row()[0];
        $stmt3 -> close();

        $stmt4 = $mysqli->prepare("select count(*) from user where bid = ? group by bid");
        $stmt4->bind_param("i",$bid);
        $stmt4->execute();
        $numOfMembersInBlock = $stmt4->get_result()->fetch_row()[0];
        $stmt4->close();

        if( ($numOfMembersInBlock<3 && $numOfAcceptance == $numOfMembersInBlock) || $numOfAcceptance>=3){
            $stmt5 = $mysqli->prepare("update user set bid = ? where uid = ?");
            $stmt5->bind_param("ii",$bid,$uid);
            $stmt5->execute();
            $stmt5->close();

            $stmt7 = $mysqli->prepare("delete from acceptmember where uid = ? and bid = ?");
            $stmt7->bind_param("ii",$uid,$bid);
            $stmt7->execute();
            $stmt7->close();

            $stmt6 = $mysqli->prepare("delete from applyblock where uid = ? and bid = ?");
            $stmt6->bind_param("ii",$uid,$bid);
            $stmt6->execute();
            $stmt6->close();
        }

}

?>