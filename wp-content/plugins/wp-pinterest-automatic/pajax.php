<?php 

//AJAX UPDATE PINNED IMAGES
add_action( 'wp_ajax_pinterest_automatic', 'wp_pinterest_automatic_callback' );

function wp_pinterest_automatic_callback() {
	global $wpdb; // this is how you get access to the database

	$pid=$_POST['pid'];

	$pin_images = get_post_meta($pid,'pin_images',1);
	$pins = get_post_meta($pid,'pins',1);
	
	if(! is_array($pins))$pins=array();
	
	print_r(json_encode($pins));
	
	
	die();
	
	
}

//AJAX SEND TO PIN QUEUE
add_action( 'wp_ajax_pinterest_automatic_pin', 'wp_pinterest_automatic_pin_callback' );

function wp_pinterest_automatic_pin_callback() {
	$itms=$_POST['itms'];
	
	$itms_arr = explode(',', $itms);
	$itms_arr= array_filter($itms_arr);
	
	foreach($itms_arr as $post_id ){
		update_post_meta($post_id, 'wp_pinterest_automatic_bot', 1);
		delete_post_meta($post_id, 'wp_pinterest_automatic_bot_processed');
	}
	
	die();
}

//AJAX TO CLEAR LOG
add_action( 'wp_ajax_pinterest_automatic_clear', 'wp_pinterest_automatic_clear_callback' );

function wp_pinterest_automatic_clear_callback() {
	global $wpdb;
	$wpdb->query('delete from wp_pinterest_automatic');
	
	print_r(json_encode(array('success')));
	
	die();
}
?>