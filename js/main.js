/**
 * Created by maple on 12/3/15.
 */
function changeView(str){
     if(str=='overview'){
         document.getElementById('overview').style.display = "";
         document.getElementById('friends').style.display = "none";
         document.getElementById('threads').style.display = "none";
         document.getElementById('blocks').style.display = "none";
         document.getElementById('profiles').style.display = "none";
         document.getElementById('o').className= 'active';
         document.getElementById('f').className= '';
         document.getElementById('t').className= '';
         document.getElementById('b').className= '';
         document.getElementById('p').className= '';
     }
     else  if(str=='friends'){
         document.getElementById('overview').style.display = "none";
         document.getElementById('friends').style.display = "";
         document.getElementById('threads').style.display = "none";
         document.getElementById('blocks').style.display="none";
         document.getElementById('profiles').style.display = "none";
         document.getElementById('f').className= 'active';
         document.getElementById('o').className= '';
         document.getElementById('t').className= '';
         document.getElementById('b').className= '';
         document.getElementById('p').className= '';
     }
     else if(str=='threads'){
         document.getElementById('overview').style.display = "none";
         document.getElementById('friends').style.display = "none";
         document.getElementById('threads').style.display = "";
         document.getElementById('blocks').style.display = "none";
         document.getElementById('profiles').style.display = "none";
         document.getElementById('t').className= 'active';
         document.getElementById('o').className= '';
         document.getElementById('f').className= '';
         document.getElementById('b').className= '';
         document.getElementById('p').className= '';
     }
     else if(str=='blocks'){
         document.getElementById('overview').style.display = "none";
         document.getElementById('friends').style.display = "none";
         document.getElementById('threads').style.display = "none";
         document.getElementById('profiles').style.display = "none";
         document.getElementById('blocks').style.display = "";
         document.getElementById('b').className= 'active';
         document.getElementById('o').className= '';
         document.getElementById('f').className= '';
         document.getElementById('t').className= '';
         document.getElementById('p').className= '';
     }
    else if(str=='profile'){
         document.getElementById('overview').style.display = "none";
         document.getElementById('friends').style.display = "none";
         document.getElementById('threads').style.display = "none";
         document.getElementById('profiles').style.display = "";
         document.getElementById('blocks').style.display = "none";
         document.getElementById('b').className= '';
         document.getElementById('o').className= '';
         document.getElementById('f').className= '';
         document.getElementById('t').className= '';
         document.getElementById('p').className= 'active';
     }
}


function makefriend(div1){
    div1.hover(function () {
        div1.innerHTML = 'makefriend';
        div1.className = "";
    })
}

function seefriend(j){
    document.getElementById('userInfo'+j).submit();
}

function  acceptBlock(j){

    var input = document.getElementById('ui'+j).value;
    var input1 = document.getElementById('bi'+j).value;
    var xmlHttp=GetXmlHttpObject();

    if(xmlHttp==null){
        alert("Browser error!");
    }

    var url="acceptJoin.php";
    url=url+"?uid="+input;
    url=url+"&bid="+input1;
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
    document.getElementById('acc'+j).disabled = 'disabled';
    alert('accept successfully!');
}

function  accept(j){

    var input = document.getElementById('in'+j).value;
    alert(input);
    var xmlHttp=GetXmlHttpObject();

    if(xmlHttp==null){
        alert("Browser error!");
    }

    var url="acceptFriend.php";
    url=url+"?fid="+input;
    url=url+"&op="+"approve";
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
    document.getElementById('ac'+j).disabled = 'disabled';
    document.getElementById('dc'+j).disabled = 'disabled';
    alert('accept successfully!');
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
