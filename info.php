<html>
    <body>
	<h1>Appliance</h1>
<?php
        $phonenum = $_POST['phonenum'];   
	$word = $_POST['word'];
	session_start();
	$_SESSION['phone'] = $phonenum;
	 
	mysql_connect("localhost","root","root");
	mysql_select_db("apl");
	$query ="select * from appliance natural join catalog where description like '%$word%' and status='available'";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	mysql_close();

	echo "<form method='post' action='appliance.php'>";
	echo '<table border="1">';
		echo '<tr>';
		   echo '<td>'; echo'Appliance';   echo '</td>';
                   echo '<td>'; echo'Description'; echo '</td>';
		   echo '<td>'; echo'Config';      echo '</td>';
		   echo '<td>'; echo'Price';       echo '</td>';
		   echo '<td>';	echo'Choose one';  echo '</td>';
		echo '</tr>';

	for($i=0;$i<$num;$i++){ 
		echo '<tr>';
		   echo '<td>';
		           echo mysql_result($result,$i,0);
		   echo '</td>';
                   echo '<td>';
			   echo mysql_result($result,$i,1);
		   echo '</td>';
		   echo '<td>';
		           echo mysql_result($result,$i,2);
		   echo '</td>';
		   echo '<td>';
		           echo mysql_result($result,$i,3);
		   echo '</td>';
		   echo '<td>';
		           $aname = mysql_result($result,$i,0);
		           $config = mysql_result($result,$i,2);
		           $price = mysql_result($result,$i,3);
			   echo "<p><input type='hidden' value='$aname' name='aname$i'></input></p>";
			   echo "<p><input type='hidden' value='$config' name='config$i'></input></p>";
			   echo "<p><input type='hidden' value='$price' name='price$i'></input></p>";
                           echo "<p>Click<input type='checkbox' name='checkbox$i'></input></p>";		   
		   echo '</td>';
		echo '</tr>';
	}
	echo "<input type='hidden' disable='disable' value='$num' name='total'>";
	echo '</table>';
	echo '<input type="submit" value="submit"></input>';
	echo '</form>';
	

?>  
	
    </body>
</html>

