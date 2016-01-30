<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="favicon.ico">

  <title>Hoods</title>

  <link href="css/bootstrap.min.css" rel="stylesheet">

  <link href="css/signin.css" rel="stylesheet">
</head>

<body>

<?php
   $newuser = $_POST["uname"];
   $pass = $_POST["pass"];
   if($newuser==""){
   }
   else {
     $mysqli = new mysqli("localhost", "root", "root", "hoods");
     if ($mysqli->connect_errno) {
       echo "Failed to connect MySQL";
     }

     $stmt = $mysqli->prepare("select uid from user where uname = ?");
     $stmt->bind_param("s",$newuser);
     $stmt->execute();
     $result = $stmt->get_result();
     $stmt->close();

     if($result->num_rows==0) {
       $stmt1 = $mysqli->prepare("insert into user(uname,password) values (?,?) ");
       $stmt1->bind_param("ss", $newuser, $pass);
       $stmt1->execute();
       $stmt1->close();
     }
     $mysqli->close();
   }
?>

<div class="container">
  <form class="form-signin" id="signinform" method="post" action="validUsernameAndPassword.php">
    <h2 class="form-signin-heading">Sign in</h2>
    <input type="text" id="inputUsername" class="form-control" value="<?php echo $newuser?>" name="uname" placeholder="Username" required autofocus>
    <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="pass" required>
    <label><span id="info"></span></label>
    <div class="checkbox">
      <label>
        <input type="checkbox" value="remember-me"> Remember me
      </label>
    </div>
    <button class="btn btn-info btn-primary btn-block" type="button" onclick="confirmSubmit()">Sign in</button>
    <a role="button" class="btn btn-success btn-primary btn-block" href="signup.php">Not a user yet? Sign up now!</a>
  </form>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/holder.min.js"></script>
<script src="js/signin.js"></script>

</body>
</html>
