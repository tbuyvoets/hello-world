<?php
/**
 * Plugin Name: Ionmoon Mobile Footer Selectmenu widget
 * Plugin URI: https://ionmoon.nl/
 * Description: Creates a custom footer selectmenu widget.
 * Author: Tom Buyvoets
 * Author URI: https://ionmoon.nl/
 * Text Domain: ionmoon-footer-selectmenu
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

function register_select_script() {
	wp_enqueue_script('script', plugins_url( '/js/script.js', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'register_select_script' );

global $post;

class select_Menu_Widget extends WP_Widget {

	

	public function __construct() {
		$widget_ops = array(
			'classname' => 'select_menu_widget',
			'description' => 'Create select menu for mobile');
		parent::__construct( "select_menu_widget", 'select_Menu_Widget', $widget_ops );
	}

	
	function widget( $args, $instance) { 
		$title = apply_filters( 'widget_title', $instance['title'] );
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
	
			if ( !$nav_menu )
				return;
		
		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title'];

		$menuArray = (array) $nav_menu;

		$selectedMenu = wp_get_nav_menu_items($menuArray['name']);
		
		$page_id = get_queried_object_id();
		?>

	    <div>
		<select onchange="handleSelect(this)">
			<option selected="selected" value="Choose one">Choose one</option>
		<?php 
		foreach ( $selectedMenu as $navItem ) {	
			// $selected = $page_id == $navItem->id ? ' selected="selected" ' : '';
			$title = $navItem->title;
			$url = $navItem->url;
			echo '<option'. $selected .' value="' . $url . '">' . $title . '</option>';
		}
		?>
		</select>
		</div> <?php 

		echo $args['after_widget'];
 	}

 	public function form($instance) {
 		$title = isset( $instance['title'] ) ? $instance['title'] : '';
        $nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

        if ( !$menus ) {
        	echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
            return;
        } ?>

        <p>
            <label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
            <select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
        <?php
            foreach ( $menus as $menu ) {
                $selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
                echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
            }
        ?>
            </select>
        </p> <?php
 	}

 	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	    $instance['nav_menu'] = (int) $new_instance['nav_menu'];
		return $instance;
	}
	
}

function select_menu_register() {
	register_widget( 'select_Menu_Widget' );
}

add_action( 'widgets_init', 'select_menu_register' );
 
 

