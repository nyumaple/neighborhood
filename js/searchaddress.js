/**
 * Created by maple on 12/15/15.
 */
$(document).ready(function () {
    initGoogleMap();
});

function initGoogleMap(){
    google.maps.event.addDomListener(window, 'load', function() {
        var map = new google.maps.Map(document.getElementById('bannerbox'), {
            zoom: 13,
            center: new google.maps.LatLng(31.1646242, 121.3747333),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infoWindow = new google.maps.InfoWindow;

        var onMarkerClick = function() {
            var marker = this;
            var latLng = marker.getPosition();
            infoWindow.setContent('<h3>输入的位置:</h3>上海徐汇区漕宝70号<br><h3>坐标是:</h3>31.1646242, 121.3747333');

            infoWindow.open(map, marker);
        };
        google.maps.event.addListener(map, 'click', function() {
            infoWindow.close();
        });

        var marker1 = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(31.1646242, 121.3747333),
        });

        google.maps.event.addListener(marker1, 'click', onMarkerClick);
    });
}