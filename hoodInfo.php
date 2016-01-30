<?php
/**
 * Created by PhpStorm.
 * User: maple
 * Date: 12/15/15
 * Time: 7:27 PM
 */

?>

<html>
<head>
</head>

<body onload="initialize()">
<button onclick="great()">aaaaaa</button>
<div id = "test" style="width: 500px; height: 300px">dddd</div>
</body>
</html>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
</script>

<script>
    function initialize() {
        alert("ok");
        geocoder = new google.maps.Geocoder();
        alert("ok");
        var latlng = new google.maps.LatLng(40.6,-74);
        var myOptions = {
            zoom: 9,
            center: latlng,
            navigationControl: true,
            scaleControl: true,
            streetViewControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("test"), myOptions);

        google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
        });
    }

    function placeMarker(location) {
        clearOverlays(infowindow);

        marker = new google.maps.Marker({
            position: location,
            map: map
        });

        markersArray.push(marker);
        var _cs = [];
        _cs[_cs.length] = "The coordinate is：";
        _cs[_cs.length] = location.lat();
        _cs[_cs.length] = ", ";
        _cs[_cs.length] = location.lng();


        var weidu =  document.getElementById('profile_lat');
        var jingdu = document.getElementById('profile_lng');
        var dizhi = document.getElementById('profile_add');

        weidu.value = location.lat();
        jingdu.value = location.lng();

        if (geocoder) {
            geocoder.geocode({'location': location}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        addr.innerHTML = " The address is：" + results[0].formatted_address;
                        dizhi.value = results[0].formatted_address;
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

</script>