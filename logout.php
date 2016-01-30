<?php
if(isset($_SESSION['uid'])){
    $_SESSION = array();
    $id = $_SESSION['uid'];
    echo "<script language='javascript'> alert($id); </script>";
    if(isset($_COOKIE[session_name()])){
        setcookie(session_name(),'',time()-3600);
    }
    session_unset();
    session_destroy();
}
echo "<script language='javascript'> alert('log out successfully'); </script>";

echo "<script language='javascript'> window.location= 'signin.php'; </script>";
?>