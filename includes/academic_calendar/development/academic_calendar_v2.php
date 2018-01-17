<?php
//* this version has been modified to use multiple csv files for when we have many years worth of datat to sift through
//*TODO at some point we should get this page to accept GET requests with query parameters specifying which semesters the user wants to view. for now it just reads them all on load.
include_once(dirname(__FILE__).'/acClass.php');

$sidebar_position= $sidebar_position ?: '';
echo '<main><div id="the-content" class="'. $sidebar_position .'">';


$breadcrumbs = ( !is_home() && !is_archive() &&  (get_post_type($post) != "post") ? get_post_meta($post->ID, 'dsu_use_breadcrums', true) : 'hide');

if(!is_front_page() AND $breadcrumbs != 'hide'):
	echo '<div class="breadcrumbs">'.dsu_breadcrums($post).'</div>';
endif;

the_title('<h1 class="content-header">','</h1>');

?>
<div class="dsu-post">
	<div id="intro" class="clearfix">
		<h2>How to Use</h2>
		<div class="intro-half">
			<h3 >Search for Dates/Deadlines</h3>
			<p>Search for events by hovering over the colored days on the calendar view, or select a category to search for a specific event. Select "Semester", "1st Block", or "2nd Block" to select whether to display semester dates or block schedule dates. Select "Full Year", "Fall" or "Spring" to select whether to view dates for the whole year or a specifc semester.</p>
		</div>
		<div class="intro-half">
			<h3>Printing</h3>
			<a href="javascript:window.print()" class="btn float-right margin1">Print</a>
			<p>We recommend printing this page in chrome for the best results. The "month" view will print a color-coded legend, and the "list" view simply prints a list of dates with descriptions. </p>
		</div>
</div>
    <div id='calendar_container'>
        <div id="filters" class="clearfix">
            <h3 id="calendar_filters">Calendar Filters</h3>
				<ul id="semester_filter" class="clearfix">
					<li class="year semester-option active-semester" data-semester="year" data-year="year">Full Year</li>
					<?php echo $cal->displaySemesterFilter(); ?>
				</ul>
                <hr class="clear">
                <div id="reset" align="right"><a id="ac-month-view" class="active-view toggle-view">Month View</a><a id="ac-list-view" class="toggle-view">List View</a><a href="">Reset Filters</a></div>
                <div id="term-container">
                    <ul id="term_filter" class="clear">
                        <li class="active-term full term-option" data-term="full">Semester</li>
                        <li class="block1 term-option" data-term="block1">1<sup>st</sup> Block</li>
                        <li class="block2 term-option" data-term="block2">2<sup>nd</sup> Block</li>
                    </ul>
                </div>
                <div id="category-filters">
                    <ul id="categories" class="clearfix ">
						<li class="category-option all active-category" data-filter="all">All Types</li>
						<li class="category-option registration active-category" data-filter="registration">Registration</li>
                        <li class="category-option fees active-category" data-filter="fees">Tuition / Fees</li>
                        <li class="category-option classwork active-category" data-filter="classwork">Classwork</li>
                        <li class="category-option graduation active-category" data-filter="graduation">Graduation</li>
                        <li class="category-option breaks active-category" data-filter="breaks">Breaks</li>
                        <li class="category-option faculty active-category" data-filter="faculty">Faculty</li>
                    </ul>
                </div>
            </div>
            <div id="sub-filters">
                <ul  class="subcategory registration ">
                    <li class="registration" rel="AA">Last Day to Add/Audit</li>
                    <li class="registration" rel="CW">Last Day for Complete Withdrawal</li>
                    <li class="registration" rel="DD">Last Day to Drop Individual Class</li>
                    <li class="registration" rel="IA">Application Deadline for International Students</li>
                    <li class="registration" rel="SG">Last Day to Add Without Signature </li>
                    <li class="registration" rel="CA">Class Schedule Available Online</li>
                    <li class="registration" rel="SR">Registration Open to Seniors (90 + credits)</li>
                    <li class="registration" rel="JR">Registration Open to Juniors (60 + credits)</li>
                    <li class="registration" rel="SO">Registration Open to Sophomores (30 + credits)</li>
                    <li class="registration" rel="OR">Open Registration</li>
                    <li class="registration" rel="WG">Last Day to Drop Without Recieving a "W" Grade</li>
                    <li class="registration" rel="WL">Last Day for Waitlist</li>
                </ul>
                <ul  class="subcategory fees">
                    <!--<li class="fees" rel="AL">Admission Late Fee Begins</li>-->
                    <li class="fees" rel="DF">Drop/Audit Fee Begins($10 per class)</li>
                    <li class="fees" rel="LF">$50 Late Registration/Payment Fee</li>
                    <li class="fees" rel="NP">Courses dropped for Non Payment</li>
                    <li class="fees" rel="PG">Pell Grant Census</li>
                    <li class="fees" rel="R1">End of 100% Refund Period</li>
                    <li class="fees" rel="R5">End of 50% Refund Period</li>
                    <li class="fees" rel="RF">Last Day for Refund</li>
                    <li class="fees" rel="TD">Tuition &amp; Fees Due</li>
                    <li class="fees" rel="RE">Residency Application Deadline</li>
                </ul>
                <ul class="subcategory classwork">
                    <li class="classwork" rel="CE">Classwork Ends</li>
                    <li class="classwork" rel="FE">Final Exams</li>
                    <li class="classwork" rel="RD">Reading Day</li>
                    <li class="classwork" rel="CB">Classwork Begins</li>
                </ul>
                <ul class="subcategory graduation">
                    <li class="graduation" rel="CO">Commencement</li>
                    <li class="graduation" rel="GR">Graduation Application Deadline</li>
                </ul>
                <ul class="subcategory breaks">
                    <li class="breaks" rel="FB">Fall Break</li>
                    <li class="breaks" rel="H">Holidays</li>
                    <li class="breaks" rel="SB">Spring Break</li>
                </ul>
                <ul class="subcategory faculty">
                    <li class="faculty" rel="FW">Faculty Workshops</li>
                    <li class="faculty" rel="MT">Midterm Grades Due</li>
                    <li class="faculty" rel="FG">Final Grades Due</li>
                </ul>
            </div>

        <div id="academic-calendar">
			<?php echo $cal->displayCalendarHeader(); ?>
			<div id="calendar-inner" class="clearfix">
			<?php echo $cal->displayCalendar(); ?>
			</div>
        </div>
    </div><!-- end cal container--->
</div><!-- end dsu-post -->
<?php

/*
 * Converts CSV to JSON
 * Example uses Google Spreadsheet CSV feed
 * csvToArray function found on php.net
 * source: https://gist.github.com/robflaherty/1185299
 */

// Function to convert CSV into associative array
function csvToArray($file, $delimiter) {
  if (($handle = fopen($file, 'r')) !== FALSE) {
    $i = 0;
    while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) {
      for ($j = 0; $j < count($lineArray); $j++) {
        $arr[$i][$j] = $lineArray[$j];
      }
      $i++;
    }
    fclose($handle);
  }
  return $arr;
}

// Scan's 'source' directory for all CSV files and puts them in the 'feeds' array
$feeds = scandir(dirname(__FILE__).'/source');
$feeds = array_filter($feeds, function($e){
    if(strpos($e,'csv') ==true){
		return true; // true to keep it.
	}
    else{
		return false; // false to filter it.
	}
});
//array to put final list in
$results = array();


foreach ($feeds as $feed) {
	// Arrays we'll use later
	$keys = array();
	$newArray = array();
	// Do it
	$data = csvToArray(dirname(__FILE__).'/source/'.$feed, ',');
	// Set number of elements (minus 1 because we shift off the first row)
	$count = count($data) - 1;

	//Use first row for names
	$labels = array_shift($data);
	foreach ($labels as $label) {
	  $keys[] = $label;
	}

	// Add Ids, just in case we want them later
	$keys[] = 'id';
	for ($i = 0; $i < $count; $i++) {
	  $data[$i][] = $i;
	}

	// Bring it all together
	for ($j = 0; $j < $count; $j++) {
	  $d = array_combine($keys, $data[$j]);
	  $newArray[$j] = $d;
	}
	$results = array_merge($results, $newArray);
}
error_log(json_encode($results));
// Print it out as JSON
echo '<script>
		console.log("hi");
		var semestersInfo ='. json_encode($cal->getSemesters() ).' ;
		var academics='.json_encode($results).';
	</script>';
