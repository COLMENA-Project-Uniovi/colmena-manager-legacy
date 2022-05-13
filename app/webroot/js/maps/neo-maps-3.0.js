/*
GOOGLE MAPS LOADER V 3.0
POWERED BY NEOZINK.COM
DEVELOPED BY CARLOS MEDINA & PABLO ABELLA & JULIA VALLINA
2013 © ALL RIGHTS RESERVED

REQUIREMENTS NOTE
THIS PLUGIN REQUIRES GOOGLE MAPS API

*/

(function ( $ ) {

	$.fn.mapsLoader = function( options ) {

		var canvas = this[0];

		if(typeof canvas === "undefined"){
			return;
		}

		var settings = $.extend({
		// These are the defaults.
			address           : null,                    // Address to geocode
			latitude          : null,                    // Latitude of marker point
			longitude         : null,                    // Longitude of marker point
			clatitude         : 43.362162,               // Longitude of map center
			clongitude        : -5.848417,               // Longitude of map center
			zoom              : 5,                       // Default zoom of map
			coordinatesClass  : ".map",                  // Default class where leave coordinates of geocode
			image             : 'img/logo/gmaps.png',    // Default marker image
			multipleMarkers   : false,                   // Allow or not multiple markers in the same map
			draggableMarker   : false,                   // Allow dragging marker
			infoWindowContent : null                     // Set infoWindow content if wanted
		}, options );

		if((options.clatitude == null && options.clongitude == null) ||
			(options.clatitude == "" && options.clongitude == "")){
			settings.clatitude = 43.362162;
			settings.clongitude = -5.848417;
		}

		initialize(canvas, settings);

		// Draw marker directly from latitude and longitude
		drawMarker(settings);

		// Draw marker from address
		codeAddress(settings);
	};

	var map = null;
	var geocoder;
	var bounds = new google.maps.LatLngBounds();
	var markers = [];
	var infoWindows = [];

	function initialize(canvas, settings){
		// Create center of the map
		var latlng = new google.maps.LatLng(settings.clatitude, settings.clongitude);
		// Only initialize map if is not already initialized
		if (map == null){
			geocoder = new google.maps.Geocoder();

			// Set map options according to settings
			var mapOptions = {
				zoom: settings.zoom,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			map = new google.maps.Map(canvas, mapOptions);
		}else{
			map.setZoom(settings.zoom);
			map.setCenter(latlng);
		}

		// If we don't allow multiple markers, clear previous
		if(!settings.multipleMarkers){
			clearMarkers();
		}
	}

	function codeAddress( settings ) {
		// If address is a valid address, perform geocode
		if(settings.address != null  && settings.address != ""){
			geocoder.geocode( { 'address': settings.address }, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {

					settings.latitude = results[0].geometry.location.lat();
					settings.longitude = results[0].geometry.location.lng();

					drawMarker(settings);

					$(settings.coordinatesClass).find(".input .latitude").val(settings.latitude);
					$(settings.coordinatesClass).find(".input .longitude").val(settings.longitude);
				} else {
					throw 'Google Maps Geocode was not successful for the following reason: ' + status;
				}
			});
		}
	}

	function drawMarker( settings ){
		// If latitude and longitude are valid values continue
		// If not, avoid this method
		if(settings.latitude != null && settings.longitude != null &&
			settings.latitude != "" && settings.longitude != ""){

			var latlngdir = new google.maps.LatLng(settings.latitude, settings.longitude);

			// Create marker and add it to markers array
			var marker = new google.maps.Marker({
				map: map,
				position: latlngdir,
				animation: google.maps.Animation.DROP,
				draggable: settings.draggableMarker
				//icon: settings.image
			});

			if(settings.draggableMarker){
				google.maps.event.addListener(marker, 'dragend', function()
				{
					var point = marker.getPosition();
					map.panTo(point);
					$(settings.coordinatesClass).find(".input .latitude").val(point.lat());
					$(settings.coordinatesClass).find(".input .longitude").val(point.lng());
				});
			}

			markers.push(marker);

			// Draw infoWindow according to settings
			drawInfoWindow(marker, settings.infoWindowContent);

			// Refresh map to fit markers bounds
			refreshMap(latlngdir, settings.zoom);
		}
	}

	function drawInfoWindow( marker, infoWindowContent ){
		if(infoWindowContent != null && infoWindowContent != ""){
			var infowindow = new google.maps.InfoWindow();

			google.maps.event.addListener(marker, 'click', (function(marker) {
				return function() {
					closeInfoWindows();
					infowindow.setContent(infoWindowContent);
					infowindow.open(map, marker);
				}
			})(marker));

			infoWindows.push(infowindow);
		}
	}

	function clearMarkers(){
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		};

		markers = [];

	}

	function closeInfoWindows(){
		for (var i = 0; i < infoWindows.length; i++) {
			infoWindows[i].close();
		};
	}

	function refreshMap( latlng, maxzoom ){
		map.setCenter(latlng);

		// Only change bounds if there are 2 or more points
		if(markers.length > 1){
			bounds.extend(latlng);
			map.fitBounds(bounds);
		}

		if(map.getZoom() > maxzoom){
			map.setZoom(maxzoom);
		}
	}

}( jQuery ));
