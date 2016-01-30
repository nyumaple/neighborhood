<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<?php
session_start();
echo $_SESSION['uname']; echo $_SESSION['uid'];
if(isset($_SESSION['uname']) && isset($_SESSION['uid'])){

$uid = $_SESSION['uid'];
$uname = $_SESSION['uname'];
$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

//user info
$stmt = $mysqli->prepare("select uid,uname,address,profile,bid from user where uid = ?");
$stmt->bind_param("i",$uid);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();


$user = $result->fetch_row();
if($user[4] != NULL){
    $_SESSION['bid'] = $user[4];
}

//friends
$stmt1 = $mysqli->prepare("select uid,uname from friend f,user u where f.uid1 = ? and u.uid = f.uid2");
$stmt1->bind_param("i",$uid);
$stmt1->execute();
$friends = $stmt1->get_result();
$stmt1->close();

//block
$bid = $_SESSION['bid'];
$stmt2= $mysqli->prepare("select bid, bname from block where bid = ?");
$stmt2->bind_param("i",$bid);
$stmt2->execute();
$block = $stmt2->get_result();
$stmt2->close();

//unread thread
$stmt3 = $mysqli->prepare("select t.tid,t.uid,t.title,t.content,t.address,t.timestamp from thread t,sendTo s where t.tid=s.tid and s.uid = ? and s.status ='unread' order by t.timestamp desc ");
$stmt3->bind_param("i",$uid);
$stmt3->execute();
$unread = $stmt3->get_result();
$stmt3->close();

//read thread
$stmt4 = $mysqli->prepare("select t.tid,t.uid,t.title,t.content,t.address,t.timestamp from thread t, sendTo s where t.tid=s.tid and s.uid = ? and s.status ='read' order by t.timestamp desc ");
$stmt4->bind_param("i",$uid);
$stmt4->execute();
$read = $stmt4->get_result();
$stmt4->close();

//block application
$stmt5 = $mysqli->prepare("select uid,bid from applyblock where (uid,bid) not in (select uid, bid from acceptmember where acceptuid = ?)");
$stmt5->bind_param("i",$uid);
$stmt5->execute();
$application = $stmt5->get_result();
$stmt5->close();

//friend request
$stmt6 = $mysqli->prepare("select uid1,uname,address from applyfriend,user where uid1 = uid and uid2 = ?");
$stmt6->bind_param("i",$uid);
$stmt6->execute();
$request = $stmt6->get_result();
$stmt6->close();

?>

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

    <title>Main Page</title>
    <link href="css/font-style.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/map.js"></script>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAb6pkJc_ePmkHzvzZK9ZiJhQQter7LsUL0TW3HjWgvAD4t4lefxRGxlg7r1mcVrp1NCTj2lXH5dTTOw" type="text/javascript"></script>

    <link href="css/main.css" rel="stylesheet"/>
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
                <li><a href="#">Home</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><?php echo "User"; ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a type="button" class="btn-info" data-toggle="modal" data-target="#request">
                                requests
                            </a>
                        </li>
                        <li>
                            <a type="button" class="btn-info" data-toggle="modal" data-target="#applyblock">
                                application to block
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li><a role="button" class="btn-info" href="logout.php">log out</a></li>
                    </ul>
                </li>
            </ul>
            <form class="navbar-form navbar-right" method="POST" action="searchUser.php">
                <input type="text" class="form-control" name="keyword" placeholder="Search Users">
            </form>
            <form class="navbar-form navbar-right" method="post" action="searchBlock.php">
                <input type="text" class="form-control" name="keyword" placeholder="Search Blocks">
            </form>

        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active" id="o"><a onclick="changeView('overview')">Profile <span
                            class="sr-only">(current)</span></a></li>
                <li id="f"><a role="button" onclick="changeView('friends')">Friends</a></li>
                <li id="t"><a role="button" onclick="changeView('threads')">Threads
                        <span
                            class="badge pull-right" style="color: red"><?php echo $unread->num_rows?></span>
                    </a></li>
                <li id="b"><a role="button" onclick="changeView('blocks')">My block</a></li>
                <li id="p"><a role="button" onclick="changeView('profile')">New thread</a></li>
                <li id="p"><a href="searchBlock.php">All Blocks</a></li>
            </ul>
        </div>
        <div id="overview" class="col-sm-9 col-sm-offset-1 col-md-9 col-md-offset-2 main">
            <h1 class="page-header">address</h1>
            <p>input your address</p>
                <input id="semap" type="text" value="" required/>
                <button class="btn-success" onclick="searchInMap()">search</button>
            <form method="post" action="updateProfile.php">
                <p class="alert">find your location on the map</p>

                <div id="googleSearch"></div>
                <div id="googleResult" style="width:490px; margin:4px 0;"></div>
                <div id="googleMap" style="width:490px; height:300px; border:solid 1px #ccc">loading...</div>
                <input id="profile_add" type="hidden" name="padd" placeholder="address" required/>

                <h1 class="sub-header">profile</h1>
                <textarea cols="50" rows="10" name="profile" required></textarea>
                <input type="submit" class="btn-success" value="submit change"/>
            </form>
        </div>

        <div id="friends" style="display: none" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h2 class="sub-header">Friends</h2>

            <?php
            $j = 1;
            $recordF =$friends;

            while($row = $recordF->fetch_row()) {
                echo "<div class='col-sm-3 col-lg-3'>";
                echo "<div class='dash-unit' style='border: dotted'>";
                echo "<h3>$row[1]</h3>";
                echo "<br>";

                echo "<div class='info-user'>";
                echo "<a onclick='seefriend($j)' role='button' data-toggle='tooltip' title='profile' id='profile' aria-hidden='true' class='li_user fs1'>Profile</a><br>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "<form id='userInfo$j' method = 'post' action='userInfo.php'>";
                echo "<input type='hidden' value='$row[0]' name='uid'/>";
                echo "</form>";
                $j++;
            }
            ?>
        </div>

        <div id="neighbors" style="display: none" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Neighbors</h1>

            <div class="col-sm-3 col-lg-3">
                <div class="dash-unit">
                    <div class="thumbnail">
                        <img src="assets/img/face80x80.jpg" alt="Marcel Newman" class="img-circle">
                    </div><!-- /thumbnail -->
                    <h1>username</h1>
                    <br>

                    <div class="info-user">
                        <a data-toggle="tooltip" title="profile" id="profile" aria-hidden="true" class="li_user fs1">Profile</a><br>
                    </div>
                </div>
            </div>

        </div>

        <div id="threads" style="display: none" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Threads</h1>
            <table class="table" border="1">
                <tr>
                    <td width="500" class="bg-success">Title</td>
                    <td width="200" class="bg-success">User</td>
                    <td width="200" class="bg-success">Time</td>
                    <td width="300" class="bg-success">Address</td>
                </tr>
                <?php
                //t.tid,t.uid,t.title,t.content,t.address,t.timestamp
                while($row = $unread->fetch_row()) {
                    $uuuid = $row[1];
                    $stmt = $mysqli->prepare("select uname from user where uid =?");
                    $stmt->bind_param("i",$uuuid);
                    $stmt->execute();
                    $rrr = $stmt->get_result();
                    $uuuname = $rrr->fetch_row();
                    echo "<tr>";
                    echo "<td class='bg-danger' ><a href='thread.php?tid=$row[0]'>$row[2] </a ></td >";
                    echo "<td class='bg-danger' ><p>$uuuname[0]</p></td >";
                    echo "<td class='bg-danger' ><p>$row[5]</p></td >";
                    echo "<td class='bg-danger' ><p>$row[4]</p></td >";
                    echo "</tr>";
                }
                ?>

                <?php
                //t.tid,t.uid,t.title,t.content,t.address,t.timestamp
                while($row = $read->fetch_row()) {
                    $uuuid = $row[1];
                    $stmt = $mysqli->prepare("select uname from user where uid =?");
                    $stmt->bind_param("i",$uuuid);
                    $stmt->execute();
                    $rrr = $stmt->get_result();
                    $uuuname = $rrr->fetch_row();
                    echo "<tr>";
                    echo "<td class='bg-info' ><a href='thread.php?tid=$row[0]'>$row[2] </a ></td >";
                    echo "<td class='bg-info' ><p>$uuuname[0]</p></td >";
                    echo "<td class='bg-info' ><p>$row[5]</p></td >";
                    echo "<td class='bg-info' ><p>$row[4]</p></td >";
                    echo "</tr>";
                }
                ?>

            </table>
        </div>

        <div id="hoods" style="display: none" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">My hood Info</h1>

            <div class="row placeholders">
                <div class="col-xs-6 col-sm-3 placeholder">
                    <h4>Bay Ridge</h4>
                </div>
                <img src="img/logo.png">
            </div>

            <h2 class="sub-header">blocks in hood</h2>

            <div class="col-sm-3 col-lg-3">
                <h4>block</h4>
                <div class="info-user">
                    <a data-toggle="tooltip" title="profile" id="profile" aria-hidden="true"
                       class="li_star">Information</a><br>
                </div>
            </div>
        </div>
    </div>


    <div id="blocks" style="display: none" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header">Block Info</h1>
        <?php
            if(isset($bid)) {
                $stmt1 = $mysqli->prepare("select bname from block where  bid = ? ");
                $stmt1->bind_param("i",$bid);
                $stmt1->execute();
                $bn = $stmt1->get_result();
                $bb = $bn->fetch_row();
                ?>

                <div class="row placeholders">
                    <div class="col-xs-6 col-sm-3 placeholder">
                        <h4><?php echo $bb;?></h4>
                    </div>
                </div>
                <form method="post" action="BlockInfo.php">
                    <input id="myBlock" type="hidden" value="<?php echo $bid;?>"/>
                    <button type="submit" class="btn-success">Block Info</button>
                </form>
                <?php
            }
        ?>

    </div>

    <div id="profiles" style="display: none" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header">New Thread</h1>

        <div class="container">
            <form class="form-group-lg" id="threadform" method="post" action="PostNewThread.php">
                <h2 class="form-group-lg-heading">Post a new thread</h2>
                <label>Title:</label>
                <input type="text" id="inputUsername" class="form-control" value="" name="title" placeholder="Title"
                       required autofocus>
                <textarea rows="5" cols="10" id="inputContent" class="form-control" placeholder="Content" name="content"
                          required></textarea>
                <input type="text" class="form-control" value="" name="address" placeholder="Address"/>
                <label><span id="info"></span></label>
                <input type="radio" name="feed" value="hood" checked="checked" /> hood
                <input type="radio" name="feed" value="block" /> block
                <input type="radio" name="feed" value="allfriends" /> all friends
                <input type="radio" name="feed" value="friend"/> one friend
                <div id="ff">
                        <select name ="onefriend" style="width: 100px">
                            <?php
                            $stmt9 = $mysqli->prepare("select uid,uname from friend f,user u where f.uid1 = ? and u.uid = f.uid2");
                            $stmt9->bind_param("i",$uid);
                            $stmt9->execute();
                            $friends = $stmt9->get_result();
                            $stmt9->close();
                            while($fri = $friends->fetch_row()) {
                                echo "<option>$fri[1]</option>";
                            }
                            ?>
                        </select>
                </div>
                <button class="btn btn-info btn-primary btn-block" type="submit">Submit
                </button>
            </form>

        </div>

    </div>
</div>


<div class="modal fade" id="request" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Friend Request:
                <?php
                echo "<table class='alert-info'>";
                echo "<tr>";
                echo "<td width='130'>Name:</td>";
                echo "<td width='200'>Address:</td>";
                echo "<td width='200'>Accept/decline</td>";
                echo "</tr>";
                $j = 1;
                while($row=$request->fetch_row()){
                    echo "<tr>";
                    echo "<td>$row[1]</td>";
                    echo "<td>$row[2]</td>";
                    echo "<td>";
                    echo "<input id='in$j' type='hidden' value='$row[0]' />";
                        echo "<button id='ac$j' onclick='accept($j)' type='submit' class='btn-info'>Accept</button> <button id='dc$j' class='btn-danger'>Decline</button>";
                    echo "</td>";
                    echo "<hr>";
                    echo "</tr>";
                    $j++;
                }
                echo "</table>";
                ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<div class="modal fade" id="applyblock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Block application:
                <?php
                echo "<table class='alert-info'>";
                echo "<tr>";
                echo "<td width='130'>Name:</td>";
                echo "<td width='200'>Address:</td>";
                echo "<td width='200'>Accept/decline</td>";
                echo "</tr>";
                $j = 1;
                while($row=$application->fetch_row()){
                    echo "<tr>";
                    $ms = $mysqli->prepare("select uname,address from user where uid =?");
                    $ms->bind_param("i",$row[0]);
                    $ms->execute();
                    $r = $ms->get_result();
                    $rr = $r->fetch_row();
                    echo "<td>$rr[0]</td>";
                    echo "<td>$rr[1]</td>";
                    echo "<td>";
                    echo "<input id='ui$j' type='hidden' value='$row[0]' />";
                    echo "<input id='bi$j' type='hidden' value='$row[1]' />";
                    echo "<button id='acc$j' onclick='acceptBlock($j)' type='submit' class='btn-info'>Accept</button>";
                    echo "</td>";
                    echo "<hr>";
                    echo "</tr>";
                    $j++;
                }
                echo "</table>";
                ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<?php
}
else {

    ?>
    <script language='javascript'> window.location= 'signin.php'; </script>

    <?php
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
                            map.setCenter(point, 18);
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
        initialize("New york");
        document.body.onunload = GUnload;
        mapReady = true;
    };
</script>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/vendor/holder.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
