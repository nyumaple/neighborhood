/**
 * Created by maple on 12/2/15.
 */

function validSubmit(){
    uname = document.getElementById("inputUsername").value;
    pass = document.getElementById("inputPassword").value;

    if(uname.length<6){
        document.getElementById("info").innerHTML = "username should be at least 6";
        return false;
    }
    else if(pass.length<6){
        document.getElementById("info").innerHTML = "password should be at least 6";
        return false;
    }
    else {
        document.getElementById("info").innerHTML="";
        return true;
    }
}

function  confirmSubmit(){
    if(validSubmit()) document.getElementById("signinform").submit();
}
