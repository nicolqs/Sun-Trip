$(function() {

	var map;var line;
	function initialize() {
		var mapOptions = {
			zoom: 3,
			center: new google.maps.LatLng(30, -38),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
		        mapTypeControl: false
		};
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

		var lineCoordinates = [
			new google.maps.LatLng(22.291, 153.027),
			new google.maps.LatLng(18.291, 153.027)
		];

		var lineSymbol = {
			path: google.maps.SymbolPath.CIRCLE,
			scale: 8,
			strokeColor: '#393'
		};

		line = new google.maps.Polyline({
			path: lineCoordinates,
			icons: [{
				icon: lineSymbol,
				offset: '100%'
			}],
			map: map
		});

	}


	function animateCircle() {
		var count = 0;
		offsetId = window.setInterval(function() {
			count = (count + 1) % 200;

			var icons = line.get('icons');
			icons[0].offset = (count / 2) + '%';
			line.set('icons', icons);
		}, 20);
	}

	setTimeout( animateCircle, 2000 );
	google.maps.event.addDomListener(window, 'load', initialize);

	function getMeteoData() {
		$.post( '/ajax.php', ( $("#search-form").serialize(), { action: "get_meteo" } ) ).done( function(data){
			var marker = {};
			data = $.parseJSON( data );

			for ( var i = 0; i < 60; i++ ) {
				if ( data[i] != undefined && data[i].current_observation != undefined ) {

						if ( data[i].current_observation.icon_url.indexOf("/nt_") ) {
							r = data[i].current_observation.icon_url.split('/');
							if ( ! r[6].indexOf("nt_") ) {
								icon = '/assets/img/' + r[6].substr(3, r[6].length);

							} else {
								icon = data[i].current_observation.icon_url ;
							}
						}

					var myLatlng = new google.maps.LatLng(data[i].current_observation.display_location.latitude, data[i].current_observation.display_location.longitude);

					marker[i] = new google.maps.Marker({ 
						position: myLatlng,
						map: map,
						title: icon,//data[i].current_observation.display_location.full,
						icon: icon,
					});
				}

			}
			
		
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

  function test() {$.ajax({
	  type: "POST",
	      url: "ajax_fare.php",
	      async: false,
	      data: {
		"from": "sfo",
		"fromDate": "09/11/2013",
		"toDate": "09/21/2013",
		}
	  }).done(function(ret) {
	      $('#hello').html(ret);
	    });
	}
  function test2() {$.ajax({
	  type: "POST",
	      url: "ajax_fare.php",
	      async: false,
	      data: {
		"from": "sfo",
		"to": "mia",
		"fromDate": "09/11/2013",
		"toDate": "09/21/2013",
	      }
	  }).done(function(ret) {
	      $('#hello').html(ret);
	    });
	}
});

