<?php
//custom academic calendar page template

//register all CSS & JS files with WP to render in correct order
add_action( 'wp_enqueue_scripts', 'enqueue_academic_cal_scripts' );
function enqueue_academic_cal_scripts() {
    wp_enqueue_style( 'academic-cal-css', get_template_directory_uri() . '/includes/academic_calendar/academic-calendar.css', array('dsu-main-css','dsu-print-css','hint-css','font-awesome','jquery-mmenu-css') );
	wp_enqueue_script( 'academic-cal-events-js', get_template_directory_uri() . '/includes/academic_calendar/acEventHandlers.js', array('jquery'), '1.0.0', true );
}

get_header();
/*the main content area*/
include_once(get_template_directory() . '/includes/academic_calendar/academic_calendar.php');

get_footer();
