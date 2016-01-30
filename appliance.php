<!DOCTYPE HTML>
<html>
    <body>
	<h1>Appliance</h1>
<?php
	date_default_timezone_set('America/New_York');

        $num = $_POST['total'];
        $time = date('y-m-d h:i:s',time());
	session_start();
	$phone = $_SESSION['phone'];

	$mysqli = new mysqli("localhost","root","root","apl");
	mysql_connect("localhost","root","root");
	mysql_select_db("apl");
        	if($mysqli->connect_errno){
			echo "Failed to connect MySQL";
		}
	for($i = 0; $i < $num; $i++){
	$a = $_POST["checkbox$i"];
	        if(isset($a)){
			$aname=$_POST["aname$i"];
			$config=$_POST["config$i"];
			$price=$_POST["price$i"];
			$query="select * from orders where phone='$phone' and aname='$aname' and config='$config' and status='pending'";
			$result=mysql_query($query);
			$number = mysql_num_rows($result);
			if($number==0){
				//mysql_query("insert into orders values('$phone','$aname','$config','$time',1,$price,'pending')");
				$stmt1 = $mysqli->prepare("insert into orders values (?,?,?,?,?,?,?)");
				$stmt1->bind_param("ssssids",$phone,$aname,$config,$time,$quantity,$price,$status);
				$quantity=1;
				$status='pending';
				$stmt1->execute();
				$stmt1->close();
			}
			else if($number==1){
				$stmt2 = $mysqli->prepare("update orders set price=price+?, quantity=quantity+1,o_time=?
					where phone=? and aname=? and config=? and status='pending'");
				$stmt2->bind_param('dssss',$price,$time,$phone,$aname,$config);
				$stmt2->execute();
				$stmt2->close();
				//mysql_query("update orders set price=price+$price,quantity=quantity+1
 					//where phone='$phone' and aname='$aname' and config='$config' and status='pending'");
			}
        	}
	}
         
       	 echo"<form action='info.php' method='post'>";
	 echo"<p>Phone number:<input type='text' value='$phonenum' name='phonenum'></input></p>";
	 echo"<p>Keyword:     <input type='text' value='' name='word'></input></p>";
         echo"<input type='submit' value='submit'></input>";
	 echo'</form>';
	 $mysqli->close();
	 mysql_close();
?>
    </body>
</html>
