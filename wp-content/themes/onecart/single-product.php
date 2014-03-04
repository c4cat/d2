<?php get_header(); ?>

<script type="text/javascript">
// custom scripting starts here
$(function() {

	<?php if (ocart_get_option('single_product_in_popup')) { ?>
	
	product_id = $('.product').attr('id').replace(/[^0-9]/g, '');
	lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', product_id);
	
	<?php } else { ?>

	$('#details').css({opacity: 1, left: 0});
	$('.iosSlider').css({'display': 'none'});
	deviceWidth = $(window).width();
	if (deviceWidth <= 977) {
		$('#banner, #details').css({'height':'800px'});
	}
	$('.navi li:last').remove();
	$('input[type="text"]').not('#min_price, #max_price').clearOnFocus();
	$('.btn-quantity').tipsy({
		trigger: 'focus',
		gravity: 'w',
		offset: 18
	});
	$('.tip').tipsy({
		delayIn: 200,
		gravity: 'n',
		offset: 8
	});
	$('.optionprice').tipsy({
		trigger: 'hover',
		gravity: 'w',
		offset: 4
	});
	// reinstate carousel
	clearTimeout(resizeTimer);
	resizeTimer = setTimeout(reinitCarousel, 100);
	$('.main-image .zoom:first').fadeIn(800, function(){
				if (deviceWidth > 766) {
				$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
	});
	$('.thumbs a, .thumbs2 a').click(function(){
				var rel = $(this).attr('rel');
				var currentID = $('.main-image .zoom:visible').attr('id');
				if (rel !== currentID && rel != 'video') {
					$('.main-image .zoom').fadeOut(800);
					$(".main-image .zoom[id='" + rel + "']").fadeIn(800, function(){
						if (deviceWidth > 766) {
						$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
						}
					});
				}
	});
	
	<?php } ?>
	
});
</script>

<?php get_template_part('template','header'); ?>

<?php get_template_part('template','nav'); ?>

<div id="banner">
	<div class="wrap">
	
		<!-- product details -->
		<?php get_template_part('template','product-noajax'); ?>
		


<?php get_template_part('template','similar'); ?>

<?php // show collections after slider ?>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('home-afterslider') ) : else : endif; ?>

<div id="catalog-noajax">
<?php get_template_part('template','catalog-noajax'); ?>
</div>

<?php // show collections after catalog ?>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('home-aftercatalog') ) : else : endif; ?>

<?php get_template_part('template','bottom'); ?>

<?php get_template_part('template','footer'); ?>

<?php get_footer(); ?>