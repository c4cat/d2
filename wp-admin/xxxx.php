<?php
/**
 * Edit post administration panel.
 *
 * Manage Post actions: post, edit, delete, etc.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );
?>
<?php 
	echo('123');
	$my_post = array(
		'post_title' => 'My post',
		'post_content' => 'This is my post.',
		'post_status' => 'publish',
		'post_author' => 1,
		'post_type' => 'product',
		'seo_title' => 'Lavender Paisley Print Pleated Detail Party Dress',
		'_thumbnail_id' => '2766',
		);

	wp_insert_post($my_post);
?>