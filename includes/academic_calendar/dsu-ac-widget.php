<?php
// Register and load the widget
function load_dsu_ac_widget() {
    register_widget( 'dsu_ac_widget' );
}

add_action( 'widgets_init', 'load_dsu_ac_widget' );

// Creating the widget
class dsu_ac_widget extends WP_Widget {

	function __construct() {
		parent::__construct(

			// Base ID of your widget
			'dsu_ac_widget',

			// Widget name will appear in UI
			'DSU Academic Calendar Widget',

			// Widget Options
			array(
				'description' => 'Inserts a simple list view of the Academic Calendar into a widget' ,
				'addWC-classnames' => ''
			)
		);
	}

	// Creating widget front-end

	public function widget( $args, $instance ) {
		wp_enqueue_script( 'dsu-ac-widget-js', get_template_directory_uri() . '/includes/academic_calendar/AC-widget-script.js', array('jquery','site-specific'), '1.0.0', true );
		wp_enqueue_style('dsu-ac-widget-css', get_template_directory_uri() . '/includes/academic_calendar/ac-widget.css', array('dsu-main-css'),'1.0',  "all");

		include_once( get_template_directory() . '/includes/academic_calendar/acToJSON.php');

		$title = apply_filters( 'widget_title', $instance['title'] );
		$title = !empty($title) ? $title :'Academic Calendar';
		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		?>
		   <div class="dsu-ac-dates">

		   </div>
		   <div class="dsu-ac-footer" >
			 <a href="https://academics.dixie.edu/academic-calendar/final-exam-schedule-fall-2017/">Finals Schedule</a>
			 <a href="https://academics.dixie.edu/academic-calendar/">Full Calendar</a>
		   </div><!-- end cal footer-->


		<?php
		echo $args['after_widget'];
	}

		// Widget Backend
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = 'Academic Calendar';
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class ends here


?>
