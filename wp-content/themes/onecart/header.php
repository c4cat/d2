<?php
global $ocart;
load_theme_textdomain('ocart', get_stylesheet_directory().'/lang/');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta name="viewport" content="width=device-width; initial-scale=1.0">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<!-- //here is meta  -->
<?php 
$the_id = get_the_ID();
$taxonomy='product_category';
$terms =  wp_get_post_terms($the_id,$taxonomy);
if($terms){
	for($i=0;$i<count($terms);$i++){
		$v = $terms[$i]->description;
		$arr = explode('|',$v);
		echo '<meta name="description" content="'.$arr[0].'"/>';
		echo '<meta name="keywords" content="'.$arr[1].'"/>';
	}
}
?>

<?php ocart_seo(); ?>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php do_action('ocart_after_body_start'); ?>

<div id="toTop"><?php _e('Back to Top','ocart'); ?></div>

<div id="wrapper">