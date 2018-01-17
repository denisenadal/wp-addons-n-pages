#DSU CAMPUS MAPS DOCUMENTATION

Since DSU is in a state of rapid expansion, campus building locations and names change regularly, so the DSU campus map must be flexible enough to be regularly updated. This PHP & JS application is the framework for a future web application that will be easily updated and modified by an average user (EG: Facilities Management can manage this in the future) As of right now, it require someone with experience in javascript to modify or update it.


##HOW IT WORKS

This application expects a well-formatted JSON list of locations and uses the Google Maps API to add them to the map, as well as attaches event listeners for user interactions with the map.## Files & DependenciesThe page-campus-maps.php file enqueues all required scripts, css and dependencies. They are as follows:

	* File: '/includes/campus-map/map.css'
	  Purpose: the page-specific CSS styles
	  Dependencies: dixie-s15/css/sitewide/style.css,

	* File: https://maps.googleapis.com/maps/api/js
	  Purpose: the Google Maps API.
	  Dependencies: jQuery and an API Key

	* File: '/includes/campus-map/points.js'
	  Purpose: this is the current JSON source of the map points. In the future, we will develop a Formidable form that users may fill out to add/modify points, and we will use the WPDB to get the data from the form submissions to populate the map.
	  Dependencies: currently, none. In the future, a formidable form.

	* File: '/includes/campus-map/MarkerWithLabel.js'
	  Purpose: this is an open-sourced code used to add labels to each marker point.
	  Dependencies: Maps API, jQuery

	* File: '/includes/campus-map/dixie.js'
	  Purpose: this is the page specific javascript file. It instantiates the map, builds all the markers, add links to each marker in the sidebar and defines the event listeners and interactions. Details are noted within the file.
	  Events it handles:	  
		* on load
		* user clicks up/down on category list
		* user clicks show all/hide all for a category
		* user clicks on building name (in category list)
		* user clicks on location marker
		* user clicks checkbox next to building

	Dependencies: Maps API, MarkerWithLabel.js, points.js

	* File: '/includes/campus-map/index.htmls'

	  Purpose: this file is for testing only.

	  Dependencies: NA, testing file.



##WHERE DOES THE DATA COME FROM?

Right now it comes from a hand-coded JSON list of points and polygons exported from a Google MyMaps that is attached to the dsuwebmedia gmail account. The link is here: https://www.google.com/maps/d/u/2/edit?mid=1rl_opdM3enSheQ3DKjjnSLrvgEI&ll=37.10377747404662%2C-113.5661663326797&z=17

##Future Improvements

In the future use google maps api to allow users to add/edit points directly on the map, and save those to a file. (of course you also have to make sure only authorized users can access editable map, this way we can have Facilities or Parking Services update this when they add new buildings or parking).Since these points will only be saved locally (on DSU servers) and not publicly on google's servers, at this point we would have to either: A.) remove the "view on google" button or B .)export the "local" map data to the public google map version once a year or semester to make sure they stay up to date. https://developers.google.com/maps/documentation/javascript/info-windows-to-db#creating-the-markers-and-info-windows
