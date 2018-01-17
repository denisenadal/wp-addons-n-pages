var map;

function initMap() {
	//this loads the Google Map and defines what features and styles it should use
   	map = new google.maps.Map(document.getElementById('map'), {
   	center: {lat: 37.10367922510574, lng:-113.5645},
   	zoom: 17,
   	mapTypeId: 'roadmap',
   	styles: [
	  {
	    "elementType": "geometry",
	    "stylers": [
	      {
	        "color": "#f5f5f5"
	      }
	    ]
	  },
	  {
	    "elementType": "labels.icon",
	    "stylers": [
	      {
	        "visibility": "off"
	      }
	    ]
	  },
	  {
	    "elementType": "labels.text.fill",
	    "stylers": [
	      {
	        "color": "#616161"
	      }
	    ]
	  },
	  {
	    "elementType": "labels.text.stroke",
	    "stylers": [
	      {
	        "color": "#f5f5f5"
	      }
	    ]
	  },
	  {
	    "featureType": "administrative.land_parcel",
	    "elementType": "labels.text.fill",
	    "stylers": [
	      {
	        "color": "#bdbdbd"
	      }
	    ]
	  },
	  {
	    "featureType": "landscape.man_made",
	    "elementType": "geometry.fill",
	    "stylers": [
	      {
	        "color": "#cfcece"
	      },
	      {
	        "visibility": "on"
	      }
	    ]
	  },
	  {
	    "featureType": "landscape.natural.landcover",
	    "elementType": "geometry.fill",
	    "stylers": [
	      {
	        "visibility": "off"
	      }
	    ]
	  },
	  {
	    "featureType": "poi",
	    "elementType": "geometry",
	    "stylers": [
	      {
	        "color": "#eeeeee"
	      }
	    ]
	  },
	  {
	    "featureType": "poi",
	    "elementType": "labels.text.fill",
	    "stylers": [
	      {
	        "color": "#757575"
	      }
	    ]
	  },
	  {
	    "featureType": "poi.attraction",
	    "elementType": "geometry.fill",
	    "stylers": [
	      {
	        "color": "#cfa3a4"
	      }
	    ]
	  },
	  {
	    "featureType": "poi.business",
	    "elementType": "geometry.fill",
	    "stylers": [
	      {
	        "color": "#cfa3a4"
	      }
	    ]
	  },
	  {
	    "featureType": "poi.government",
	    "elementType": "geometry.fill",
	    "stylers": [
	      {
	        "color": "#cfa3a4"
	      },
	      {
	        "visibility": "on"
	      }
	    ]
	  },
	  {
	    "featureType": "poi.medical",
	    "elementType": "geometry.fill",
	    "stylers": [
	      {
	        "color": "#cfa3a4"
	      }
	    ]
	  },
	  {
	    "featureType": "poi.park",
	    "elementType": "geometry",
	    "stylers": [
	      {
	        "color": "#e5e5e5"
	      }
	    ]
	  },
	  {
	    "featureType": "poi.park",
	    "elementType": "geometry.fill",
	    "stylers": [
		      {
		        "color": "#a7c8b5"
		      },
		      {
		        "visibility": "on"
		      }
		    ]
			  },
			  {
			    "featureType": "poi.park",
			    "elementType": "labels.text.fill",
			    "stylers": [
			      {
			        "color": "#9e9e9e"
			      }
			    ]
			  },
			  {
			    "featureType": "poi.school",
			    "elementType": "geometry.fill",
			    "stylers": [
			      {
			        "color": "#f2e4e0"
			      }
			    ]
			  },
			  {
			    "featureType": "poi.sports_complex",
			    "elementType": "geometry.fill",
			    "stylers": [
			      {
			        "color": "#a7c8b5"
			      }
			    ]
			  },
			  {
			    "featureType": "road",
			    "elementType": "geometry",
			    "stylers": [
			      {
			        "color": "#ffffff"
			      }
			    ]
			  },
			  {
			    "featureType": "road.arterial",
			    "elementType": "labels.text.fill",
			    "stylers": [
			      {
			        "color": "#757575"
			      }
			    ]
			  },
			  {
			    "featureType": "road.highway",
			    "elementType": "geometry",
			    "stylers": [
			      {
			        "color": "#dadada"
			      }
			    ]
			  },
			  {
			    "featureType": "road.highway",
			    "elementType": "labels.text.fill",
			    "stylers": [
			      {
			        "color": "#616161"
			      }
			    ]
			  },
			  {
			    "featureType": "road.local",
			    "elementType": "labels.text.fill",
			    "stylers": [
			      {
			        "color": "#9e9e9e"
			      }
			    ]
			  },
			  {
			    "featureType": "transit.line",
			    "elementType": "geometry",
			    "stylers": [
			      {
			        "color": "#e5e5e5"
			      }
			    ]
			  },
			  {
			    "featureType": "transit.station",
			    "elementType": "geometry",
			    "stylers": [
			      {
			        "color": "#eeeeee"
			      }
			    ]
			  },
			  {
			    "featureType": "transit.station.bus",
			    "elementType": "geometry.fill",
			    "stylers": [
			      {
			        "color": "#cfa3a4"
			      }
			    ]
			  },
			  {
			    "featureType": "water",
			    "elementType": "geometry",
			    "stylers": [
			      {
			        "color": "#c9c9c9"
			      }
			    ]
			  },
			  {
			    "featureType": "water",
			    "elementType": "geometry.fill",
			    "stylers": [
			      {
			        "color": "#c3ded8"
			      }
			    ]
			  },
			  {
			    "featureType": "water",
			    "elementType": "labels.text.fill",
			    "stylers": [
			      {
			        "color": "#9e9e9e"
			      }
			    ]
			  }
		]
   	});

	//an array to hold all the map markers
   	var markers =[];
   	var marker;
   	var infowindow = new google.maps.InfoWindow({maxWidth:300});
	//event listener to hide infowindow
   	google.maps.event.addListener(map, 'click', function() {
   		infowindow.close();
   	});
	//event listener to show/hide labels based on zoom level
	google.maps.event.addListener(map, 'zoom_changed', function() {
   		if(map.getZoom() < 17){
			markers.forEach(function(marker){
				marker.labelVisible = false;
			})
		}
		else{
			markers.forEach(function(marker){
				marker.labelVisible = true;
			})
		}
   	});

	var $lists =$('.legend-list');

	//create a marker for each feature in list
   	for (var i = 0; i < points.length; i++) {
		var iconSize = points[i].properties.icon == "icons/marker.png"? 35 : 25;
   		marker = new MarkerWithLabel({
   			position: new google.maps.LatLng(points[i].geometry.coordinates),
   			map: map,
			visible:points[i].properties.visible,
   			icon: {
				"url":'/wp-content/themes/dixie-s15/includes/campus-map/'+points[i].properties.icon,
				"scaledSize": {width:iconSize,height:iconSize},
			},
			labelContent: points[i].properties.name,
			labelAnchor: new google.maps.Point(-15, 20),
       		labelClass: "labels",
			"id":"marker-"+i,
			"category":points[i].properties.category
   		});
		//event listener to load infowindow when marker is clicked
   		google.maps.event.addListener(marker, 'click', (function(marker, i) {
   			return function() {
   				infowindow.setContent("<img src=\"/wp-content/themes/dixie-s15/includes/campus-map/"+points[i].properties.icon+"\" width=30 height=30 alt=\""+points[i].properties.category+"\" class=\"info-img\"><div class=\"info-box\"> <h3 class=\"info-title\">"+points[i].properties.name+"</h3><h4 class=\"info-cat\">"+points[i].properties.category+"</h4></div><p class=\"info-description\">"+points[i].properties.description+"</p>");
   				infowindow.open(map, marker);

   			}
   		})(marker, i));

   		// Push the marker to the 'markers' array
   		markers.push(marker);

		//add a link in the sidebar to load the marker infowindow
		$lists.each(function(index){
			if("legend-"+points[i].properties.category == $(this).attr('id')){
				$(this).append('<li class="legend-item '+ (points[i].properties.visible ? "active" : "") +'"><input type="checkbox" value="'+marker.id+'" id="'+marker.id+'"><label for=\"check-'+marker.id+'\">&nbsp;</label><span data-link="'+marker.id+'">'+points[i].properties.name+'</span></li>');
			}
		});
		//add event listener to link in sidebar to trigger marker's infowindow
		$('.legend-item span').unbind().click(function(){
			var id = $(this).attr("data-link");
			var clicked = markers.filter(function(marker){
				return marker.id == id;
			})[0];
			map.panTo(clicked.getPosition());
			if(!clicked.getVisible()){
				clicked.setVisible(true);
				$(this).parent('li').addClass('active');
				checkParent();
				clicked.setZIndex(google.maps.Marker.MAX_ZINDEX+1);

			}
			google.maps.event.trigger(clicked, 'click');
		});

		//event listeners to toggle visibility of markers
		$('.legend-item label').unbind().click(function(){
			var id = $(this).prev().val();
			var clicked = markers.filter(function(marker){
				return marker.id == id;
			})[0];
			if( clicked.getVisible() ){
				clicked.setVisible(false);
				infowindow.close();
			}
			else{
				clicked.setVisible(true);
				clicked.setZIndex(google.maps.Marker.MAX_ZINDEX+1);
			}
			$(this).parent('li').toggleClass('active');
			checkParent();
		});
   	}//end markers constructor

	//create polygons & markers for parking
	for(var j=0;j<parking.length;j++){
		var newPoly ={};
		newPoly.visible = parking[j].properties.visible;
		newPoly.map = map;
		newPoly.category = +parking[j].properties.category
		switch (parking[j].properties.category){
			case "student parking":
				newPoly.fillColor = "#525256";
				newPoly.fillOpacity =1;
				newPoly.strokeColor="##525256";
		 		newPoly.strokeOpacity= 1;
		 		newPoly.strokeWeight= 0;
				break;
			case "employee parking":
				newPoly.fillColor = "#2d2d38";
				newPoly.fillOpacity =1;
				newPoly.strokeColor="#2d2d38";
				newPoly.strokeOpacity= 1;
				newPoly.strokeWeight= 0;
				break;
			case "motorcycle parking":
				newPoly.icon = "icons/motorcycle.png";
				break;
			case "accessible parking":
				newPoly.icon = "icons/accessible.png";
				break;
			case "economy parking":
				newPoly.fillColor = "#633030";
				newPoly.fillOpacity =1;
				newPoly.strokeColor="#633030";
				newPoly.strokeOpacity= 1;
				newPoly.strokeWeight= 0;
				break;
			case "housing parking":
				newPoly.fillColor = "#1a247e";
				newPoly.fillOpacity =1;
				newPoly.strokeColor="#1a247e";
				newPoly.strokeOpacity= 1;
				newPoly.strokeWeight= 0;
				break;
			case "visitor parking":
				newPoly.fillColor = "#42946C";
				newPoly.fillOpacity =1;
				newPoly.strokeColor="#42946C";
				newPoly.strokeOpacity= 1;
				newPoly.strokeWeight= 0;
				break;
			default:
				newPoly.fillColor = "#777";
				newPoly.fillOpacity =.1;
				newPoly.strokeColor="#777";
				newPoly.strokeOpacity= 1;
				newPoly.strokeWeight= 0;
				newPoly.icon = "icons/parking.png";

		}

		if(parking[j].geometry.type == "Polygon"){
			newPoly.paths = parking[j].geometry.coordinates;
			var marker = new google.maps.Polygon(newPoly);
		}
		else if (parking[j].geometry.type == "Point"){
			marker = new MarkerWithLabel({
				position: parking[j].geometry.coordinates,
				map: map,
				visible:parking[j].properties.visible,
				icon: {
					"url":'/wp-content/themes/dixie-s15/includes/campus-map/'+newPoly.icon,
					"scaledSize": {width:25,height:25}
				},
				labelContent: "",
				labelClass: "labels",
				"id":"parking-"+i,
			});
		}
		// Push the marker to the 'markers' array
		marker.category = parking[j].properties.category;
		markers.push(marker);

		//load parking polygons & markers on click.
		$('.legend-list li.sub-cat *').unbind().click(function(){
			var cat = $(this).is('span') ? $(this).attr("data-link") + " parking" : $(this).next('span').attr("data-link") + " parking";
			for(var k=0;k<markers.length;k++){
				if(markers[k].category == cat ){
					if(!markers[k].getVisible() ){
						markers[k].setVisible(true);
						$(this).parent('li').addClass('active');
						checkParent();
					}
					else{
						markers[k].setVisible(false);
						$(this).parent('li').removeClass('active');
						checkParent();
					}
				}
			}
		});
	}//end parking markers &polygons constructor

	//toggle visibility of group of markers
	$('.legend-cat .group-nav span').click(function(){
		//default list behavior
		if(!$(this).parents('#legend-parking').length){
			//get all markers in this category
			var $markerLinks = $(this).parents('li').siblings('.legend-item');
			var visible = $(this).hasClass('show');

			//for each marker in cagetory:
			$markerLinks.each(function(index){
				var id = $(this).find('input').val();
				var marker = markers.filter(function(marker){
					return marker.id == id;
				})[0];
				//if user selected "show all", set marker to visible, add "active" class to link item, make sure parent is "active"
				if(visible){
					marker.setVisible(true);
					$(this).addClass('active');
					checkParent();
				}
				//if user selected "hide all", set marker to invisible, remove"active" class to link item, remove "active" from parent
				else{
					 marker.setVisible(false);
					 $(this).removeClass('active');
					 checkParent();
				}
			});
		}
		//otehrwise do special parking map stuff
		else{
			//get all markers with "parking" as a category
			var parking = markers.filter(function(marker){
				return marker.category.includes('parking');
			});
			//if user clicked "show all" add "active" class to parking categories, call checkParent() to add "active" class to parent category(parking) and lastly show all parking markers
			if($(this).hasClass('show') ){
				$('#legend-parking .sub-cat').addClass('active');
				checkParent();
				parking.forEach(function(marker){
					marker.setVisible(true);
				});
			}
			else{
				//remove active from all the subcategories, call checkParent() to remove "active" class from the parent category(parking), and lastly hide all parking markers
				$('#legend-parking .sub-cat').removeClass('active');
				checkParent();
				parking.forEach(function(marker){
					marker.setVisible(false);
				});
			}
		}
	});

	//sort list items in sidebar A-Z
	$lists.each(function(index){
		$(this).find("li.legend-item").sort(function(a, b){
			return ($(b).text()) < ($(a).text()) ? 1 : -1;
		}).appendTo(this);
	});

	//toggle list display when user clicked up/down
	$('li.legend-cat i').click(function(){
		if($(this).hasClass('fa-chevron-up')){
			$(this).parents('li.legend-cat').siblings().slideUp();
		}
		else{
			//if any lists are open, close them first
			if($('.fa-chevron-up').length > 0){
				$('li.legend-item').slideUp();
			}
			//open the selected list
			$(this).parents('li.legend-cat').siblings().slideDown();
			//scroll to focus on it
			var top = $(this).parents('ul.legend-list')[0].offsetTop;
			$('body').animate({scrollTop: top}, 500);
		}
		$(this).toggleClass('fa-chevron-up fa-chevron-down');
	});

	//hide lists on load
	$('i.fa-chevron-down').parents('li.legend-cat').siblings().slideUp();

	//style active and inactive lists, called on load and when
	function checkParent(){
		$('.legend-list:has(li.active)').addClass('active');
		$('.legend-list:not(:has(li.active))').removeClass('active');

	}
	checkParent();

   }//end initMap

//****instructions modal
//show modal on btn click, hide on exit button
$('#open-map-faq, #map-faq .exit-button').click(function(){
	$('#map-faq').toggle();
});
//hide modal when user clicks outside of box
$(document).on('click', function (e) {
	if($(e.target).attr('id') != 'open-map-faq' && !$(e.target).hasClass('exit-button') ){
		if ($(e.target).closest("#map-faq").length === 0 ) {
			$("#map-faq").hide();
		}
	}
});
//hide modal when user preses esc key
$('body').keypress(function(e){
	if(e.which == 27){
		$("#map-faq").hide();
	}
});
