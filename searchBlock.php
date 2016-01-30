<?php

session_start();

$keyword = $_POST['keyword'];
$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

$stmt = $mysqli->prepare("select bid, bname,hname from block NATURAL join hood where bname like ?");
$query_name = "%$keyword%";

$stmt->bind_param("s",$query_name);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/logo.png">

    <title>Thread</title>
    <link href="css/font-style.css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/main.css" rel="stylesheet">
    <link href="css/blog.css" rel="stylesheet">
    <script src="js/searchBlock.js"></script>
</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Hoods</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="main.php">Home</a></li>
                <li>

                    <ul class="dropdown-menu">
                        <li role="separator" class="divider"></li>
                        <li><a href="logout.php">log out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div>

    <?php
    $j = 1;
    echo "<table border='1' >";
    echo "<tr>";
    echo "<td width='500' height='30' class='bg-success'>Blockname</td>";
    echo "<td width='300' class='bg-success'>Detailed Infomation</td>";
    echo "</tr>";
    while($row = $result->fetch_row()) {
        echo "<tr>";

        echo "<td>$row[1]</td>";
        echo "<td><button class='btn-success' onclick='blockInfo($j)'>BlockInfo</button></td>";
        echo "</tr>";
        echo "<form id='block$j' method='post' action='BlockInfo.php' target='_blank'>";
        echo "<input type='hidden' id='val$j' name='bid' value='$row[0]'/>";
        echo "</form>";
        $j++;
    }
    echo "</table>";
    ?>

</div>


</body>
</html>
