$(function() {
	$( "#search-form" ).submit(function(e) {
		e.preventDefault();
		$.post( '/ajax.php', { action: "get_meteo" }, function(data){
            // jQuery('#comments-container .fyre-stream-content').append(data);
            console.log(data);
        });
	});

	$.post( '/ajax.php', { action: "get_meteo" }, function(data){
        // jQuery('#comments-container .fyre-stream-content').append(data);
        console.log(data);
    });

	$( "#date-checkin" ).datepicker({
		numberOfMonths: 2,
		showButtonPanel: true,
		dateFormat: "d M, y",
    });
	$( "#date-checkout" ).datepicker({
		numberOfMonths: 2,
		showButtonPanel: true,
		dateFormat: "d M, y",
    });
});

var map;
function initialize() {
  var mapOptions = {
    zoom: 3,
    center: new google.maps.LatLng(23, -18),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
}

google.maps.event.addDomListener(window, 'load', initialize);