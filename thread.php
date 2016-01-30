<!DOCTYPE html>

<?php
session_start();
$uid = $_SESSION['uid'];
$tid = $_GET['tid'];


$mysqli = new mysqli("localhost","root","root","hoods");
if($mysqli->connect_errno){
    echo "Failed to connect MySQL";
}

$stmt = $mysqli->prepare("update sendTo set status = 'read' where tid = ? and uid = ?");
$stmt->bind_param("ii",$tid,$uid);
$stmt->execute();
$stmt->close();

$stmt = $mysqli->prepare("select mid,uid,title,textbody,timestamp from message where tid = ?");
$stmt->bind_param("i",$tid);
$stmt->execute();
$message = $stmt->get_result();
$stmt->close();

$stmt = $mysqli->prepare("select title from thread where tid = ?");
$stmt->bind_param("i",$tid);
$stmt->execute();
$thread = $stmt->get_result();
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

<div class="container">

    <div class="blog-header">
        <h1 class="blog-title">Thread</h1>
        <p class="lead blog-description"><?php echo $thread->fetch_row()[0]?>.</p>
    </div>

    <div class="row">
        <?php echo $message->num_rows; ?>
        <div class="col-sm-8 blog-main">
            <?php
            while($row = $message->fetch_row()) {
                $stmt = $mysqli->prepare("select uname from user where uid = ?");
                $stmt->bind_param("i",$row[1]);
                $stmt->execute();
                $nnn = $stmt->get_result();
                $stmt->close();
                $na = $nnn->fetch_row()[0];

                echo "<div class='blog-post'>";
                echo "<h2 class='blog-post-title'>$row[2]</h2>";
                echo "<p class='blog-post-title'>$row[3]</p>";
                echo "<p class='blog-post-meta'>$row[5] by <a href='#'> $na </a></p>";
                echo "<p>$row[4]</p>";
                echo "</div>";
                echo "<hr>";
            }
            ?>

        </div><!-- /.blog-main -->

    </div><!-- /.row -->

</div><!-- /.container -->

<footer class="blog-footer">
    <form class="form-group-lg" id="threadform" method="post" action="PostNewMessage.php">
        <h2 class="form-group-lg-heading">Reply a new message</h2>
        <label>Title:</label>
        <input type="text" id="inputUsername" class="form-control" value="" name="title" placeholder="Title"
               required autofocus>
                <textarea rows="5" cols="10" id="inputContent" class="form-control" placeholder="Content" name="textbody"
                          required></textarea>
        <input type="text" class="form-control" value="" name="address" placeholder="Address"/>
        <input type="hidden" name="tid" value ="<?php echo $tid; ?>"/>
        <label><span id="info"></span></label>
                <button class="btn btn-info btn-primary btn-block" type="submit">Submit
        </button>
    </form>
</footer>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>


