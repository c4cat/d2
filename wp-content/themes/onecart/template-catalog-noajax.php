<?php if (ocart_catalog_version() == 1) { ?>

<div id="catalog">

	<div class="wrap">
	
		<div class="catalogWrapper">
		
			<?php
			$sort = ocart_get_option('sort_products');
			if ($sort == 1) {
			$args = array( 'post_type' => 'product', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC', get_query_var( 'taxonomy' ) => get_query_var( 'term' ) );
			} else {
			$args = array( 'post_type' => 'product', 'numberposts' => -1, get_query_var( 'taxonomy' ) => get_query_var( 'term' ) );
			}
			$posts = get_posts( $args );
			?>

			<?php if (count($posts) > 0) { ?>
			
			<ul class="prods">
			
				<?php foreach ($posts as $post): setup_postdata($post); ?>

					<li id="item-<?php the_ID(); ?>" rel="<?php the_permalink(); ?>">
						<?php if (!ocart_get_option('disable_cart')) { ?>
						<?php ocart_product('tag'); ?>
						<?php } ?>
						<?php ocart_product('catalog_image'); ?>
						<div class="label">
							<div class="label-content">
								<span class="title"><?php ocart_product('title'); ?></span>
								<?php if (ocart_get_option('disable_cart') && ocart_get_option('disable_prices')) { } else { ?>
								<div class="price"><?php ocart_product('price'); ?></div>
								<?php } ?>
							</div>
						</div>
					</li>

				<?php endforeach; ?>

			</ul>
			
			<div class="nextItem"></div>
			<div class="prevItem"></div>
					
			<div class="nextproduct"></div>
			<div class="prevproduct"></div>
			
			<script type="text/javascript">
			
				// init carousel
				$('.prods').carouFredSel({
					width: 977,
					height: 277,
					scroll: 1,
					align: "left",
					auto: false,
					direction: "right",
					prev: {
						button: '.prevItem',
						onBefore: function(){
								$('.prods li').removeClass('viewport');
								$('.prevproduct').stop().animate({left: 0});
								$('.prods').trigger("currentVisible", function( items ) {
									items.addClass( 'viewport' );
									var next_item_id = $('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
									$('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id, function(){
										$('.prevproduct').hide().stop().animate({left: '-200px'});
									});
								});
						},
						onAfter: function(){
								$('.prods li').removeClass('viewport');
								$('.prods').trigger("currentVisible", function( items ) {
									items.addClass( 'viewport' );
									var $img = $('.prods li.viewport:first').last(),
										$prev = $img.prev();
									if (0==$prev.length) {
										$prev = $img.siblings().last();
									}
									var prev_item_id = $prev.attr('id').replace(/[^0-9]/g, '');
									$('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + prev_item_id, function(){
										$('.prevproduct').show();
									});
								});
						}
					},
					next: {
						button: '.nextItem',
						onBefore: function(){
								$('.prods li').removeClass('viewport');
								$('.nextproduct').stop().animate({right: 0});
								$('.prods').trigger("currentVisible", function( items ) {
									items.addClass( 'viewport' );
									var prev_item_id = $('.prods li.viewport:first').attr('id').replace(/[^0-9]/g, '');
									$('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + prev_item_id, function(){
										$('.nextproduct').hide().stop().animate({right: '-200px'});
									});
								});
						},
						onAfter: function(){
								$('.prods li').removeClass('viewport');
								$('.prods').trigger("currentVisible", function( items ) {
									items.addClass( 'viewport' );
									var next_item_id = $('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
									$('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id, function(){
										$('.nextproduct').show();
									});
								});
						}
					}
				});

				// change next/prev product
				if ($('.prods li').size() >= 7 ) {
					$('.prods').trigger("currentVisible", function( items ) {
						items.addClass( 'viewport' );
						var next_item_id = $('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
						$('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id);
						var last_item_id = $('.prods li:last').attr('id').replace(/[^0-9]/g, '');
						$('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + last_item_id);
					});
				}
				
			</script>
			
			<?php } ?>

		</div>
	
	</div>
	
</div>

<?php } else { ?>

<div id="index">
	<div class="wrap">
		

		<script type="text/javascript">
		//<![CDATA[
		if (typeof newsletter_check !== "function") {
		window.newsletter_check = function (f) {
		    var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-]{1,})+\.)+([a-zA-Z0-9]{2,})+$/;
		    if (!re.test(f.elements["ne"].value)) {
		        alert("The email is not correct");
		        return false;
		    }
		    if (f.elements["ny"] && !f.elements["ny"].checked) {
		        alert("You must accept the privacy statement");
		        return false;
		    }
		    return true;
		}
		}
		//]]>
		</script>
	
		<!-- categories, options -->
		<div class="filter">
			<div style="margin-top:20px">
			<div class="newsletter newsletter-subscription">
			<form method="post" action="http://dress4club.com/wp-content/plugins/newsletter/do/subscribe.php" onsubmit="return newsletter_check(this)">

			<table cellspacing="5" cellpadding="0" border="0">

			<!-- email -->
			<tr><h3>NEWSLETTER SIGNUP</h3></tr>
			<tr><td><input type="email" placeholder="Email" name="ne" size="20" required></td><td class="newsletter-td-submit"><input class="newsletter-submit" id="newsletter-submit" type="submit" value="Subscribe"/></td></tr>
			<tr></tr>

			</table>
			</form>
			</div>
			<div class='the-pint'>
			 	<a data-pin-do="embedUser" href="http://www.pinterest.com/dress4club/" data-pin-scale-width="60" data-pin-scale-height="290" data-pin-board-width="188">Visit Dress4Club's profile on Pinterest.</a>
			</div>
		</div>
			<?php ocart_show_grid_filters() ?>
		</div>
	
		<div class="catalog">

			<?php $posts = get_posts( array( 'post_type' => 'product', 'numberposts' => -1, get_query_var( 'taxonomy' ) => get_query_var( 'term' ) ) ); ?>
			<div class="catalog_title"><ins id=""><?php if (get_query_var('taxonomy')) { $current_tax = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); echo $current_tax->name; } ?></ins><span><?php if (count($posts) == 1) { printf(__('<span class="totalprod"><span id="products_count">%s</span> Product Found</span>','ocart'), count($posts)); } else { printf(__('<span class="totalprod"><span id="products_count">%s</span> Products Found</span>','ocart'), count($posts)); } ?></span></div>
			
			<ul class="catalog_list" <?php if (get_query_var('taxonomy')) { ?>rel="<?php echo get_query_var( 'taxonomy' ).'-'.get_query_var( 'term' ); ?>"<?php } ?>>
			<!-- data -->
			</ul><div class="clear"></div>
		
		</div><div class="clear"></div>
	
	</div>
</div>

<?php } ?>