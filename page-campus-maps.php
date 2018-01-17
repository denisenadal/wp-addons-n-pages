<?php
function enqueue_map_scripts(){

	 wp_enqueue_style( 'dsu-map-css', get_template_directory_uri() . '/includes/campus-map/map.css',array( 'dsu-main-css', 'dsu-print-css'), '1.1',false);

	wp_enqueue_script( 'googlemapapi-js', "https://maps.googleapis.com/maps/api/js?key=AIzaSyDHUmYXet4df3IpNKLnbaPXdhZAmjdZpPs", array ( 'jquery'), '1.1', true);

	 wp_enqueue_script( 'mappoints-js', get_template_directory_uri() . '/includes/campus-map/points.js', array ( 'jquery'), '1.1', true);

	wp_enqueue_script( 'mapmarkerslabels-js', get_template_directory_uri() . '/includes/campus-map/MarkerWithLabel.js', array ( 'jquery', 'googlemapapi-js'), '1.1', true);

	wp_enqueue_script( 'dixiemap-js', get_template_directory_uri() . '/includes/campus-map/dixie.js', array ( 'jquery', 'mappoints-js', 'mapmarkerslabels-js', 'googlemapapi-js'), '1.1', true);
}
add_action( 'wp_enqueue_scripts', 'enqueue_map_scripts');

get_header();
?>
<main class="is-edge-to-edge">
    <div class="container">
        <h1 id="content-header">Dixie State University Campus Map</h1>
    </div>
    <div id="map-info" class="container">
        <div id="map-btns" class="container">
            <a href="#" class="btn2" id="open-map-faq"><i class=" fa fa-info-circle" aria-hidden="true" style="padding-right:1rem;" ></i> How to use the map </a>
            <a class="btn2" href="#" onclick="window.location.reload()"> <i class="fa fa-refresh" aria-hidden="true" style="padding-right:1rem;"></i> Reset Map </a>
            <a class="btn2" href="https://drive.google.com/open?id=1rl_opdM3enSheQ3DKjjnSLrvgEI&usp=sharing">  View in Google Maps <i class=" fa fa-chevron-right" aria-hidden="true" style="padding-left:1rem;" ></i></a>
            <a class="btn2" href="http://dixie.edu/wp-content/uploads/2018/01/DSUCampusMap-Jan2018.pdf"><i class=" fa fa-download" aria-hidden="true" style="padding-right:1rem;"></i> PDF Map</a>
            <a href=""></a><a class="btn2" href="http://campus.dixie.edu/maps-floor-plans/">  Building Maps &amp; Floorplans <i class="fa fa-chevron-right" style="padding-left:1rem;" aria-hidden="true"></i></a>
        </div>
        <div id="map-faq" style="display:none;">
            <a class="exit-button"><i class="fa fa-times float-right fa-2x" aria-hidden="true"></i></a>
            <h4>Show/Hide a Category</h4>
            <img src="/wp-content/themes/dixie-s15/includes/campus-map/images/map-tutorial-02.png" class="float-right" style="width:33%;margin-left:2rem;">
            <p>Click "Show All"/"Hide All" to show or hide all markers in a category. Use the "Up" and "Down" icons to view all the buildings/landmarks in a category. </p>
            <h4>Show/Hide a Specific Building</h4>
            <p>Click the "down" arrow for the type of building you are looking for. Click the checkbox next to the building name to show or hide it on the map.</p>
            <img src="/wp-content/themes/dixie-s15/includes/campus-map/images/map-tutorial-03.png">
            <h4>Find a Specific Building</h4>
            <p>Click the "down" arrow for the type of building you are looking for. Click the building name in the sidebar and the map will center on your selected building or landmark. </p>
            <h4>View More Information</h4>
            <img src="/wp-content/themes/dixie-s15/includes/campus-map/images/map-tutorial-04.png" class="float-right" style="width:33%;margin-left:2rem;">
            <p>Click the marker in the map to view details about the building. Alternatively, click the building or landmark's name in the sidebar. </p>
            <h4>Find an Office or Classroom</h4>
            <p>Visit the <a href="https://campus.dixie.edu/maps-floor-plans/">Building Floorplans</a> page to download .pdfs of each building's layout.</p>
        </div>
    </div>
    <div id="map-frame">
        <div id="map"></div>
        <div id="legend">
            <ul id="legend-centers" class="legend-list">
                <li class="legend-cat">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" class="cat-icon float-left">
					  <path d="M12 2C8 2 5 5 5 9c0 5.3 7 13 7 13s7-7.8 7-13c0-4-3-7-7-7zm0 9.5c-1.4 0-2.5-1-2.5-2.5s1-2.5 2.5-2.5 2.5 1 2.5 2.5-1 2.5-2.5 2.5z"/>
					  <path fill="none" d="M0 0h24v24H0z"/>
					</svg>
                    <h3>Student Centers &amp; Services</h3>
                    <p class="group-nav"><span class="show">Show All</span> | <span class="hide">Hide All</span>
                    </p>
                    <i class="fa fa-chevron-down fa-2x"></i>
                </li>
            </ul>
            <ul id="legend-dining" class="legend-list">
                <li class="legend-cat">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" class="cat-icon float-left">
					  <path fill="none" d="M0 0h24v24H0z"/>
					  <path d="M8 13.3l3-2.8-7-7C2.3 5 2.3 7.5 4 9.2l4 4zm7-1.8c1.4.7 3.6.2 5-1.4 2-1.7 2.4-4.4 1-6-1.5-1.3-4.2-1-6.2 1-1.6 1.5-2 3.7-1.3 5.2L3.7 20 5 21.2l7-7 7 7 1.3-1.4-7-7 1.6-1.5z"/>
					</svg>
                    <h3>Dining &amp; Shopping</h3>
                    <p class="group-nav"><span class="show">Show All</span> | <span class="hide">Hide All</span>
                    </p>
                    <i class="fa fa-chevron-down fa-2x"></i>
                </li>

            </ul>
            <ul id="legend-buildings" class="legend-list">
                <li class="legend-cat">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 48 48"  class="cat-icon float-left">
					  <path fill="none" d="M0 0h24v24H0z"/>
					  <path d="M24.2 4.4l21 8.4v2.8h-3c0 .3 0 .8-.2 1-.2.2-.7.3-1 .3H7.5c-.3 0-.8-.4-1-.5-.2-.2-.3-.7-.3-1h-3v-2.8l21-8.4zM8.6 18.6h5.8v16.8h2.8V18.6H23v16.8h2.8V18.6h5.8v16.8h2.8V18.6H40v16.8h1.3l1 .3c.2.2.3.7.3 1V38H6.3v-1.3l.3-1c.2-.2.7-.3 1-.3H9l-.4-16.8zm35.2 21c.3 0 .8 0 1 .2.2.2.3.7.3 1v2.8H3v-2.8l.3-1c.3-.2.7-.3 1-.3h39.5z" class="st0"/>
					  <path d="M24.2 4.4l21 8.4v2.8h-3c0 .3 0 .8-.2 1-.2.2-.7.3-1 .3H7.5c-.3 0-.8-.4-1-.5-.2-.2-.3-.7-.3-1h-3v-2.8l21-8.4zM8.6 18.6h5.8v16.8h2.8V18.6H23v16.8h2.8V18.6h5.8v16.8h2.8V18.6H40v16.8h1.3l1 .3c.2.2.3.7.3 1V38H6.3v-1.3l.3-1c.2-.2.7-.3 1-.3H9l-.4-16.8zm35.2 21c.3 0 .8 0 1 .2.2.2.3.7.3 1v2.8H3v-2.8l.3-1c.3-.2.7-.3 1-.3h39.5z" class="st0"/>
					</svg>
                    <h3>Campus Buildings</h3>
                    <p class="group-nav"><span class="show">Show All</span> | <span class="hide">Hide All</span>
                    </p>
                    <i class="fa fa-chevron-down fa-2x"></i>
                </li>

            </ul>
            <ul id="legend-sports" class="legend-list">
                <li class="legend-cat">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" class="cat-icon float-left">
					  <path fill="none" d="M0 0h24v24H0z"/>
					  <path d="M13.5 5.5c1 0 2-1 2-2s-1-2-2-2-2 1-2 2 1 2 2 2zm-3.6 14l1-4.5 2 2v6h2v-7.5l-2-2 .4-3C14.8 12 16.8 13 19 13v-2c-2 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1h-.8L6 8.4V13h2V9.6L9.8 9l-1.6 8-5-1-.3 2 7 1.4z"/>
					</svg>
                    <h3>Sports Fields &amp; Arenas</h3>
                    <p class="group-nav"><span class="show">Show All</span> | <span class="hide">Hide All</span>
                    </p>
                    <i class="fa fa-chevron-down fa-2x"></i>
                </li>

            </ul>
            <ul id="legend-dorms" class="legend-list">
                <li class="legend-cat">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" class="cat-icon float-left">
					  <path fill="none" d="M0 0h24v24H0z"/>
					  <path d="M7 13c1.7 0 3-1.3 3-3S8.7 7 7 7s-3 1.3-3 3 1.3 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.2-1.8-4-4-4z"/>
					</svg>
                    <h3>Dorms</h3>
                    <p class="group-nav"><span class="show">Show All</span> | <span class="hide">Hide All</span>
                    </p>
                    <i class="fa fa-chevron-down fa-2x"></i>
                </li>
            </ul>
            <ul id="legend-outdoors" class="legend-list">
                <li class="legend-cat">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 48 48"  class="cat-icon float-left">
					  <path fill="none" d="M0 0h24v24H0z"/>
					  <path d="M42.6 40.7h-13V46h-11v-5.4H5.3L15.8 28h-7L19 15.2h-6L24 3l11.2 12.2H29L39.4 28H32l10.5 12.7z"/>
					</svg>
                    <h3>Outdoor Spaces</h3>
                    <p class="group-nav"><span class="show">Show All</span> | <span class="hide">Hide All</span>
                    </p>
                    <i class="fa fa-chevron-down fa-2x"></i>
                </li>

            </ul>
            <ul id="legend-parking" class="legend-list">
                <li class="legend-cat">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" class="cat-icon float-left">
					  <path fill="none" d="M0 0h24v24H0z"/>
					  <path d="M13 3H6v18h4v-6h3c3.3 0 6-2.7 6-6s-2.7-6-6-6zm.2 8H10V7h3.2c1 0 2 1 2 2s-1 2-2 2z"/>
					</svg>
                    <h3>Parking</h3>
                    <p class="group-nav"><span class="show">Show All</span> | <span class="hide">Hide All</span>
                    </p>
                    <i class="fa fa-chevron-down fa-2x"></i>
                </li>
                <li class="sub-cat">
                    <input type="checkbox" value="employee" id="employeee">
                    <label for="employee">&nbsp;</label>
                    <span data-link="employee">Faculty &amp; Staff Parking</span>
                </li>
                <li class="sub-cat">
                    <input type="checkbox" value="student" id="student">
                    <label for="student">&nbsp;</label>
                    <span data-link="student">Student Parking</span>
                </li>
                <li class="sub-cat">
                    <input type="checkbox" value="visitor" id="visitor">
                    <label for="visitor">&nbsp;</label>
                    <span data-link="visitor">Visitor Parking</span>
                </li>
                <li class="sub-cat">
                    <input type="checkbox" value="accessible" id="accessible">
                    <label for="accessible">&nbsp;</label>
                    <span data-link="accessible">Accessible Parking</span>
                </li>
                <li class="sub-cat">
                    <input type="checkbox" value="motorcycle" id="motorcycle">
                    <label for="motorcycle">&nbsp;</label>
                    <span data-link="motorcycle">Motorcycle Parking</span>
                </li>
                <li class="sub-cat">
                    <input type="checkbox" value="economy" id="economy">
                    <label for="economy">&nbsp;</label>
                    <span data-link="economy">Economy Parking</span>
                </li>
                <li class="sub-cat">
                    <input type="checkbox" value="housing" id="housing">
                    <label for="housing">&nbsp;</label>
                    <span data-link="housing">Student Housing Parking</span>
                </li>
            </ul>
        </div>
    </div>

    <?php get_footer(); ?>
    <script>
        jQuery(document).ready(function ($) {
                initMap();
            });

    </script>
