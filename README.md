# wp-addons-n-pages

A collection of small custom WordPress pages and add-ons from my work for a public university. This repo is for demostration and archival purposes only, the main codebase for the university's website is private. 

## page-academic-calendar.php 
is a customized page template used to generate the annual academic calendar dynamically from a CVS file. The resulting calendar can be filtered using javascript.

## page-campus-maps.php
is a customized page template used to generate our campus maps page. It uses a GEO-JSON file and the Google Maps API to build a customized google map of buildings on campus and information about each building. In the future the GEO-JSON data will be stored in a database that can be updated from a WordPress admin screen by end users.

## includes
is a directory that stores the required JS & CSS for the above customized page templates. It also includes our customized widget builder, which allows admins to add CSS classes to a widget for customized diplay, and our events-slider add-on. The events-slider is a php include which includes PHP,CSS & JS to use REST/XML Requests to get calendar event data and displa it in a slider. This code will be complied into a WordPress widget for easy use in the future.
