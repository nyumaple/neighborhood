/**
 * Created by maple on 12/2/15.
 */
var xmlHttp;

function validUsername(){

    uname = document.getElementById("inputUsername").value;

    if(uname.length<6){
        document.getElementById("hint1").innerHTML = "username can not be less than 6";
        return false;
    }

    xmlHttp=GetXmlHttpObject();

    if(xmlHttp==null){
        alert("Browser error!");
    }

    var url="findDup.php";
    url=url+"?uname="+uname;
    xmlHttp.onreadystatechange=stateChanged;
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
    if(document.getElementById("hint1").innerHTML=="")
        return true;
    else return false;
}


function stateChanged()
{
    if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
    {
        document.getElementById("hint1").innerHTML=xmlHttp.responseText
    }
}


function GetXmlHttpObject()
{
    var xmlHttp=null;
    try
    {
        // Firefox, Opera 8.0+, Safari
        xmlHttp=new XMLHttpRequest();
    }
    catch (e)
    {
        // Internet Explorer
        try
        {
            xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e)
        {
            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xmlHttp;
}

function confirmPass(){
    pass1 = document.getElementById("inputPassword").value;
    pass2 = document.getElementById("confirmPassword").value;

    hint = "";

    if(pass1.length<6){
        hint = "password can not be less than 6";
        document.getElementById("hint2").innerHTML=hint;
        return false;
    }
    else if(pass1 != pass2){
        hint = "different password input";
        document.getElementById("hint2").innerHTML=hint;
        return false;
    }
    else{
        hint = "It is ok";
        document.getElementById("hint2").innerHTML=hint;
        return true;
    }
}

function confirmSubmit() {
    if(confirmPass() && validUsername()){
        document.getElementById("signupform").submit();
    }
}