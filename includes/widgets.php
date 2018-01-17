<?php
/**
 *
 * Widgets
 * http://codex.wordpress.org/Widgetizing_Themes
 *
**/

function dsu_widgets_init(){

	register_sidebar(array(
		'name'			=>	'Global Sidebar',
		'id'			=>	'global_sidebar',
		'before_widget'	=>	'<div class="widget">',
		'after_widget'	=>	'</div>',
		'before_title'	=>	'<h2>',
		'after_title'	=>	'</h2>'
	));


	for($i=1;$i<=15;$i++):

		register_sidebar(array(
			'name'			=>	'Aux Sidebar '.$i,
			'id'			=>	'left_bar_widget_'.$i,
			'before_widget'	=>	'<div class="widget">',
			'after_widget'	=>	'</div>',
			'before_title'	=>	'<h2>',
			'after_title'	=>	'</h2>'
		));

	endfor;


	register_sidebar(array(
		'name'			=>	'Footer Widget',
		'id'			=>	'footer_widget',
		'before_widget'	=>	'<div class="widget">',
		'after_widget'	=>	'</div>',
		'before_title'	=>	'<h3>',
		'after_title'	=>	'</h3>'
	));

	remove_widgets();
}
add_action('widgets_init', 'dsu_widgets_init');
//use "dynamic_sidebar( 'widget_id' )" to display it in the theme




function remove_widgets() {
	$widgets = array(
		//'WP_Widget_Pages'			=> 'Pages Widget',
		'WP_Widget_Calendar'		=> 'Calendar Widget',
		//'WP_Widget_Archives'		=> 'Archives Widget',
		//'WP_Widget_Links'			=> 'Links Widget',
		'WP_Widget_Meta'			=> 'Meta Widget',
		'WP_Widget_Search'			=> 'Search Widget',
		//'WP_Widget_Text'			=> 'Text Widget',
		//'WP_Widget_Categories'		=> 'Categories Widget',
		'WP_Widget_Recent_Posts'	=> 'Recent Posts Widget',
		'WP_Widget_Recent_Comments'	=> 'Recent Comments Widget',
		'WP_Widget_RSS'				=> 'RSS Widget',
		//'WP_Widget_Tag_Cloud'		=> 'Tag Cloud Widget',
		//'WP_Nav_Menu_Widget'		=> 'Menus Widget',
	);

	foreach($widgets AS $key=>$value):
		unregister_widget($key);
	endforeach;
}

//========= CLEAN STYLING ON TAG CLOUDS =========//
add_filter('wp_generate_tag_cloud', 'remove_tag_cloud_styles',10,1);

function remove_tag_cloud_styles($string){
   return preg_replace('/style="font-size:.+pt;"/', '', $string);
}

//========= ADD CSS CLASSES TO WIDGETS =========//
add_filter('in_widget_form', 'addWidgetClasses', 10, 3);
//function to add class field to widget form
function addWidgetClasses($widget, $return, $instance) {
	if(!is_super_admin()){
		return;
	}

	$classes = isset($instance['addWC-classnames']) ? $instance['addWC-classnames'] : '';

	$output = '<p><label for="'. $widget->get_field_id('addWC-classnames') .'">Custom classname(s):</label>';
	$output .= '<input type="text" id="'. $widget->get_field_id('addWC-classnames') .'" name="'. $widget->get_field_name('addWC-classnames') .'" value="'. $classes .'"></p>';
	echo $output;


}
add_filter('widget_update_callback', 'addWidgetClassesUpdate', 10, 2);

function addWidgetClassesUpdate($instance, $new_instance) {
	//only save the new values if it is a super admin
	//we also don't bother with nonces because this is a filter function which hands off the filtered result to native wordpress functions.
    if(is_super_admin()){
		//strip out invalid/unconventional classname characters
		$instance['addWC-classnames'] = preg_replace('/[^a-zA-Z0-9-_\/ ]/', '' , $new_instance['addWC-classnames'] );
	}
    return $instance;
}

add_filter('widget_display_callback', 'addWidgetClassesDisplay', 10, 3);

function addWidgetClassesDisplay($instance, $widget, $args) {
    if (!isset($instance['addWC-classnames'])) {
        return $instance;
    }

    $widget_classname = $widget->widget_options['classname'];
    $classnames = $instance['addWC-classnames'];
	$args['before_widget'] = preg_replace('/(class="widget((_| ).+)?)(">)/U', '${1}${2}${3} '.$classnames.' ${4}', $args['before_widget']);

	$widget->widget($args, $instance);

    return false;
}
