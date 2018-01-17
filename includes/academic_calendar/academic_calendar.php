<?php
include_once(dirname(__FILE__).'/acClass.php');

$sidebar_position= isset($sidebar_position) ? $sidebar_position : '';
echo '<main><div id="the-content" class="'. $sidebar_position .'">';

echo '<div class="breadcrumbs">'.dsu_breadcrums($post).'</div>';

the_title('<h1 class="content-header">','</h1>');

?>
<div class="dsu-post">
    <div id="intro" class="clearfix">
        <h2>How to Use</h2>
        <div class="intro-half">
            <h3 >Search for Dates/Deadlines</h3>
            <p>Search for events by hovering over the colored days on the calendar view, or select a category to search for a specific event. Select "Full Year", "1st Block", or "2nd Block" to select whether to display semester dates or block schedule dates. <!--Select "Full Year", "Fall" or "Spring" to select whether to view dates for the whole year or a specifc semester.--></p>
        </div>
        <div class="intro-half">
            <h3>Printing</h3>
            <span id="printButton" class="btn float-right margin1">Print</span>
            <p>We recommend printing this page in chrome for the best results. The "month" view will print a color-coded legend, and the "list" view simply prints a list of dates with descriptions. </p>
        </div>
</div>
    <div id='calendar_container'>
        <div id="filters" class="clearfix">
            <h3 id="calendar_filters">Calendar Filters</h3>
                <!--<ul id="semester_filter" class="clearfix">
                    <li class="year semester-option active-semester" data-semester="year" data-year="year">Full Year</li>
                    <?php echo $cal->displaySemesterFilter(); ?>
                </ul>-->
                <hr class="clear">
                <div id="reset" align="right"><a id="ac-month-view" class="active-view toggle-view">Month View</a><a id="ac-list-view" class="toggle-view">List View</a><a href="">Reset Filters</a></div>
                <div id="term-container">
                    <ul id="term_filter" class="clear">
                        <li class="active-term full term-option" data-term="full">Full Year</li>
                        <li class="block1 term-option" data-term="block1">1<sup>st</sup> Blocks</li>
                        <li class="block2 term-option" data-term="block2">2<sup>nd</sup> Blocks</li>
                    </ul>
                </div>
                <div id="category-filters">
                    <ul id="categories" class="clearfix ">
                        <li class="category-option all active-category" data-filter="all">All Types</li>
                        <li class="category-option registration active-category" data-filter="registration">Registration</li>
                        <li class="category-option fees active-category" data-filter="fees">Tuition / Fees</li>
                        <li class="category-option classwork active-category" data-filter="classwork">Classwork / Exams</li>
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
                    <li class="fees" rel="UR">Utah Residency Application Deadline</li>
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
            <div id="mobile-filters-section">
                <div class="mobile-term">
                    <label>Term</label>
                    <div class="semester-type">
                        <span class="termSelect">Full Year</span> <i class="fa fa-angle-down fa-2x" aria-hidden="true"></i>
                    </div>
                    <ul>
                        <li class="active-term full term-option" data-term="full">Full Year</li>
                        <li class="block1 term-option" data-term="block1">1st Blocks</li>
                        <li class="block2 term-option" data-term="block2">2nd Blocks</li>
                    </ul>
                </div>
                <div class="mobile-filters">
                    <label>Filter by</label>
                    <div class="filter-type">
                        <span class="filterSelect">All Types</span> <i class="fa fa-angle-down fa-2x" aria-hidden="true"></i>
                    </div>
                    <ul>
                        <a href=""><li class="section AllTypeSection all" data-filter="all">All Types</li></a>
                        <li class="RegistrationSection"><span class="section">Registration</span> <i class="fa fa-angle-up fa-2x" aria-hidden="true"></i>
                            <ul class="subcategory registration">
                                <li class="registration" rel="AA">Last Day to Add/Audit</li>
                                <li class="registration" rel="CW">Last Day for Complete Withdrawal</li>
                                <li class="registration" rel="DD">Last Day to Drop Individual Class</li>
                                <li class="registration" rel="IA">Application Deadline for International Students</li>
                                <li class="registration" rel="SG">Last Day to Add Without Signature</li>
                                <li class="registration" rel="SR">Registration Open to Seniors (90 + credits)</li>
                                <li class="registration" rel="JR">Registration Open to Juniors (60 + credits)</li>
                                <li class="registration" rel="SO">Registration Open to Sophomores (30 + credits)</li>
                                <li class="registration" rel="OR">Open Registration</li>
                                <li class="registration" rel="WG">Last Day to Drop Without Recieving a "W" Grade</li>
                                <li class="registration" rel="WL">Last Day for Waitlist</li>
                            </ul>
                        </li>
                        <li class="TuitionSection"><span class="section">Tuition / Fees</span> <i class="fa fa-angle-up fa-2x" aria-hidden="true"></i>
                            <ul class="subcategory  fees">
                                <li class="fees" rel="DF">Drop/Audit Fee Begins($10 per class)</li>
                                <li class="fees" rel="LF">$50 Late Registration/Payment Fee</li>
                                <li class="fees" rel="NP">Courses dropped for Non Payment</li>
                                <li class="fees" rel="PG">Pell Grant Census</li>
                                <li class="fees" rel="R1">End of 100% Refund Period</li>
                                <li class="fees" rel="R5">End of 50% Refund Period</li>
                                <li class="fees" rel="RF">Last Day for Refund</li>
                                <li class="fees" rel="TD">Tuition & Fees Due</li>
                                <li class="fees" rel="RE">Residency Application Deadline</li>
                            </ul>
                        </i>
                        <li class="ClassworkSection"><span class="section">Classwork / Exams</span> <i class="fa fa-angle-up fa-2x" aria-hidden="true"></i>
                            <ul class="subcategory classwork">
                                <li class="classwork" rel="CA">Class Schedule Available Online</li>
                                <li class="classwork" rel="CE">Classwork Ends</li>
                                <li class="classwork" rel="FE">Final Exams</li>
                                <li class="classwork" rel="RD">Reading Day</li>
                                <li class="classwork" rel="CB">Classwork Begins</li>
                            </ul>
                        </li>
                        <li class="GraduationSection"><span class="section">Graduation</span> <i class="fa fa-angle-up fa-2x" aria-hidden="true"></i>
                            <ul class="subcategory graduation">
                                <li class="graduation" rel="CO">Commencement</li>
                                <li class="graduation" rel="GR">Graduation Application Deadline</li>
                            </ul>
                        </li>
                        <li class="sub-dropdown BreakSection"><span class="section">Breaks</span> <i class="fa fa-angle-up fa-2x" aria-hidden="true"></i>
                            <ul class="subcategory breaks">
                                <li class="breaks" rel="FB">Fall Break</li>
                                <li class="breaks" rel="H">Holidays</li>
                                <li class="breaks" rel="SB">Spring Break</li>
                            </ul>
                        </li>
                        <li class="sub-dropdown FacultySection"><span class="section">Faculty</span> <i class="fa fa-angle-up fa-2x" aria-hidden="true"></i>
                            <ul class="subcategory faculty">
                                <li class="faculty" rel="FW">Faculty Workshops</li>
                                <li class="faculty" rel="MT">Midterm Grades Due</li>
                                <li class="faculty" rel="FG">Final Grades Due</li>
                            </ul>
                        </li>
                    </ul>
                </div>
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
include_once(dirname(__FILE__).'/acToJSON.php');
