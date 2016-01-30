var map;
var marker;
var infowindow;
var geocoder;
var markersArray = [];
var content = document.getElementById("content");
var loca = document.getElementById("loca");
var addr = document.getElementById("addr");

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

function initialize()
{
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(28.212651557421317,112.94564378840637);
    var myOptions = {
        zoom: 9,
    center: latlng,
    navigationControl: true,
    scaleControl: true,
    streetViewControl: true,
    mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_profile"), myOptions);

    google.maps.event.addListener(map, 'click', function(event) {
        placeMarker(event.latLng);
    });
}

function placeMarker(location) {
    var lat = document.getElementById('profile_lat');
    var lng = document.getElementById('profile_lng');
    var add = document.getElementById('profile_add');
    var geocoder;

    clearOverlays(infowindow);
    marker = new google.maps.Marker({
        position: location,
        map: map
    });
    markersArray.push(marker);

    lat.value = location.lat();
    lng.value = location.lng();

    loca.innerHTML = location.lat();


    geocoder = new google.maps.Geocoder();

    if (geocoder) {
        geocoder.geocode({'location': location}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    add.value =results[0].formatted_address;
                }
            } else {
                alert("Geocoder failed due to: " + status);
            }
        });
    }

    content.style.display = "";

    infowindow = new google.maps.InfoWindow({
        content: content,
        //size: new google.maps.Size(50,50),
        position: location
    });
    loca.innerHTML = "abc" + location.lat() + location.lng();
    infowindow.open(map);
}

// Deletes all markers in the array by removing references to them
function clearOverlays(infowindow) {
    if (markersArray) {
        for (i in markersArray) {
            markersArray[i].setMap(null);
        }
        markersArray.length = 0;
    }
    if(infowindow){
        infowindow.close();
    }
}



