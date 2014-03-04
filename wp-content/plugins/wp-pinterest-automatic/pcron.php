<?php 

/**
 * custom request for cron
 */
function wp_pinterest_automatic_parse_request($wp) {

	// only process requests with "my-plugin=ajax-handler"
	if (array_key_exists('wp_pinterest_automatic', $wp->query_vars)) {
		if($wp->query_vars['wp_pinterest_automatic'] == 'cron'){
			
			wp_pinterest_automatic_pin_function();
			
			exit;
		}
	}
}

add_action('parse_request', 'wp_pinterest_automatic_parse_request');

function wp_pinterest_automatic_query_vars($vars) {
	$vars[] = 'wp_pinterest_automatic';
	return $vars;
}

add_filter('query_vars', 'wp_pinterest_automatic_query_vars');



?>