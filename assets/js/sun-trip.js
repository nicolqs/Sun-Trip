$(function() {

	var map;
	function initialize() {
		var mapOptions = {
			zoom: 3,
			center: new google.maps.LatLng(23, -18),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	}

	google.maps.event.addDomListener(window, 'load', initialize);

	function getMeteoData() {
		$.post( '/ajax.php', { action: "get_meteo" }, function(data){
            // jQuery('#comments-container .fyre-stream-content').append(data);

            var marker = {};
            data = JSON.parse(data);

            for ( var i = 0; i < 60; i++ ) {
            	// console.log(data[i]);
            	if (  data[i].current_observation != undefined ) {
	            	var myLatlng = new google.maps.LatLng(data[i].current_observation.display_location.latitude, data[i].current_observation.display_location.longitude);

					marker[i] = new google.maps.Marker({ 
						position: myLatlng,
						map: map,
						title: data[i].current_observation.display_location.full,
				        icon: 
					});
				}
				
            }
			// $.each(data, function (i, val) {
			// 	// console.log( val );

			// 	var myLatlng = new google.maps.LatLng(val.current_observation.display_locaion.latitude, val.current_observation.display_locaion.latitude);

			// 	marker[i] = new google.maps.Marker({ 
			// 		position: myLatlng,
			// 		map: map,
			// 		title: val.current_observation.display_locaion.full,
			// 	});

			// });


        });
	}

	$( "#search-form" ).submit(function(e) {
		e.preventDefault();
		getMeteoData();
	});

	$.post( '/ajax.php', { action: "get_meteo" }, function(data){
		getMeteoData();
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

