/**
 * Created by maple on 12/24/15.
 */
function  apply(j){

    var input = document.getElementById('val'+j).value;

    var xmlHttp=GetXmlHttpObject();

    if(xmlHttp==null){
        alert("Browser error!");
    }

    var url="applyfriend.php";
    url=url+"?fid="+input;
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
    document.getElementById('user'+j).className = 'btn-block';
    document.getElementById('user'+j).disabled = 'disabled';
    alert('apply successfully!');
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