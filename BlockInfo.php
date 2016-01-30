<!DOCTYPE html>
<?php
/**
 * Created by PhpStorm.
 * User: maple
 * Date: 12/15/15
 * Time: 7:15 PM
 */
session_start();
$bid = $_POST['bid'];

$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

//bname, hname
$stmt = $mysqli->prepare("select bname, address, hid, hname,north,south,east,west from block natural join hood where bid = ?");
$stmt->bind_param("i",$bid);
$stmt->execute();
$block = $stmt->get_result();
$stmt->close();
$b = $block->fetch_row();

$stmt1 = $mysqli->prepare("select uid,uname from user where bid = ?");
$stmt1->bind_param("i",$bid);
$stmt1->execute();
$members = $stmt1->get_result();
$stmt1->close();
?>

<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/logo.png">

    <link href="http://www.google.com/uds/css/gsearch.css" rel="stylesheet" type="text/css" />
    <link href="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css" rel="stylesheet" type="text/css" />

    <title>Block Info</title>
    <link href="css/font-style.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/map.js"></script>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAb6pkJc_ePmkHzvzZK9ZiJhQQter7LsUL0TW3HjWgvAD4t4lefxRGxlg7r1mcVrp1NCTj2lXH5dTTOw" type="text/javascript"></script>

    <link href="css/main.css" rel="stylesheet">
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

<div id="blocks" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h1 class="page-header">Block Info</h1>

    <div id="googleMap" style="height: 300px;width: 500px"></div>

    <div class="row placeholders">
        <div class="col-xs-6 col-sm-3 placeholder">
            <h4><?php echo $b[1];?></h4>
        </div>
    </div>

        <form method="post" action="applyBlock.php">
            <input type="hidden" name="bid" value='<?php echo $bid;?>'/>
         <button type="submit" class="btn-info">Apply to this Block</button>
        </form>
    <h2 class="sub-header">Members in block</h2>
<?php
    $j = 1;
while($row = $members->fetch_row()) {
    echo "<div class='col-sm-3 col-lg-3'>";
    echo "<div class='dash-unit' style='border: dotted'>";
    echo "<h3>$row[1]</h3>";
    echo "<br>";

    echo "<div class='info-user'>";
    echo "<a onclick='' data-toggle='tooltip' title='profile' id='profile' aria-hidden='true' class='li_user fs1'>Profile</a><br>";
    echo "<form id='userInfo$j' method = 'post' action='userInfo.php''>";
    echo "<input type='hidden' value='$row[0]'/>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>

    <script type="text/javascript">
        var map = null;
        var geocoder = null;
        var mapReady = false;
        var mapResult = [];
        function initialize(address) {

            if (GBrowserIsCompatible()) {

                //搜索结果元素
                var resultElem = document.getElementById("googleResult");
                //地图容器元素
                var mapElem = document.getElementById("googleMap");

                map = new GMap2(mapElem);

                // 平移及缩放控件（左上角）
                map.addControl(new GLargeMapControl());

                //比例尺控件（左下角）
                map.addControl(new GScaleControl());

                //创建缩略图控件（右下角）
                var overviewMap = new GOverviewMapControl();
                map.addControl(overviewMap);

                geocoder = new GClientGeocoder();

                //初始化位置
                if (geocoder) {
                    geocoder.getLatLng(
                        address,
                        function(point) {
                            if (point) {
                                map.setCenter(point, 13);
                                var marker = new GMarker(point);
                                map.addOverlay(marker);
                                marker.openInfoWindowHtml(address);
                            }
                        }
                    );
                }

            }
        }

        function searchInMap(){
            var address = document.getElementById('semap').value;
            document.getElementById('profile_add').value = address;
            initialize(address);
        }

        window.onload = function(){
            initialize('<?php echo $b[1];?>');
            document.body.onunload = GUnload;
            mapReady = true;
        };

        function initMap(n,s,e,w){
            var map = new google.maps.Map(document.getElementById('map'),{zoom: 12,center: {lat:40.63, lng:-74},
            mapTypeId: google.maps.MapTypeId.ROADMAP});
            var rectangle = new google.maps.Rectangle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: 'FF0000',
                fillOpacity: 0.35,
                map : map,
                bounds : {
                    north: 40.59,
                    south: 40.607,
                    east: -74.014,
                    west: -74.021
                }
            })
        }
    </script>
    <?php
    echo "<script>";
      echo "window.onload = initMap($b[4],$b[5],$b[6],$b[7])";
    echo "</script>";
    ?>
</div>
</body>
</html>