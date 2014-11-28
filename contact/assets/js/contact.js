/**
 *  Display maps using google maps API
 */
var eventMaps = new Array();
var myLatlng = new Array();
var myOptions = new Array();
var map = new Array();
var marker = new Array();

function loadGoogleMaps() {
    /* Google Maps Load */
    if(jQuery(".googlemap").length != 0){
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=initGoogleMaps";
        document.body.appendChild(script);
    }
}

function initGoogleMaps() {
    jQuery.each(eventMaps, function(k, v){
        myLatlng[k] = new google.maps.LatLng(v.x, v.y);
        myOptions[k] = {
            zoom: 14,
            center: myLatlng[k],
            popup: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map[k] = new google.maps.Map(document.getElementById(v.id), myOptions[k]);
        marker[k] = new google.maps.Marker({
            position: myLatlng[k],
            map: map[k],
            title: v.title
        });
    });
}

jQuery(document).ready(function(){
    loadGoogleMaps();
});