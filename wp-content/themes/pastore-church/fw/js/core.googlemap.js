function pastore_church_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof PASTORE_CHURCH_STORAGE['googlemap_init_obj'] == 'undefined') pastore_church_googlemap_init_styles();
	PASTORE_CHURCH_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: PASTORE_CHURCH_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		pastore_church_googlemap_create(id);

	} catch (e) {
		
		dcl(PASTORE_CHURCH_STORAGE['strings']['googlemap_not_avail']);

	};
}

function pastore_church_googlemap_create(id) {
	"use strict";

	// Create map
	PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].dom, PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers)
		PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	pastore_church_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].map)
			PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].map.setCenter(PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function pastore_church_googlemap_add_markers(id) {
	"use strict";
	for (var i in PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (PASTORE_CHURCH_STORAGE['googlemap_init_obj'].geocoder == '') PASTORE_CHURCH_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			PASTORE_CHURCH_STORAGE['googlemap_init_obj'].geocoder.geocode({address: PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						pastore_church_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(PASTORE_CHURCH_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].title;
			PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].map.setCenter(PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].map,
								PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			PASTORE_CHURCH_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function pastore_church_googlemap_refresh() {
	"use strict";
	for (id in PASTORE_CHURCH_STORAGE['googlemap_init_obj']) {
		pastore_church_googlemap_create(id);
	}
}

function pastore_church_googlemap_init_styles() {
	// Init Google map
	PASTORE_CHURCH_STORAGE['googlemap_init_obj'] = {};
	PASTORE_CHURCH_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.pastore_church_theme_googlemap_styles!==undefined)
		PASTORE_CHURCH_STORAGE['googlemap_styles'] = pastore_church_theme_googlemap_styles(PASTORE_CHURCH_STORAGE['googlemap_styles']);
}