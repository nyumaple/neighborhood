<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/logo.png">

    <title>Hoods</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">

</head>

<body>

<div class="container">

    <form  class="form-signin" id="signupform" method="post" action="signin.php">
        <h2 class="form-signin-heading">Sign up</h2>

        <label>Username:</label>
            <input type="text" id="inputUsername" class="form-control" name="uname"
                   onkeyup="validUsername()" placeholder="Username" required autofocus/>
        <p><span class = "warning" id="hint1"> </span></p>

        <label>Password:</label>
            <input type="password" id="inputPassword" class="form-control" name="pass"
                   onkeyup="confirmPass()" placeholder="Password" required/>

        <label>Confirm Your Password:</label>
            <input type="password" id="confirmPassword" class="form-control"
                   onkeyup="confirmPass()" placeholder="Password2" required/>
        <p><span class = "warning" id="hint2"> </span></p>

        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <button class="btn btn-info btn-primary btn-block" type="button" onclick="confirmSubmit()">Sign up</button>
        <a class="btn btn-success btn-primary btn-block" role="button" href="signin.php">Already a user? Sign in!</a>
    </form>
    <a href="signin.php">signin</a>
</div>
<script src="js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/holder.min.js"></script>
<script src="js/signup.js"></script>
</body>
</html>
