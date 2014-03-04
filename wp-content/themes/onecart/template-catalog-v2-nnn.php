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
		<a href="template-catalog-v2.php" id="" title="template-catalog-v2">template-catalog-v2</a>}
		//]]>
		</script>

		
		<!-- categories, options -->
		<div class="filter">

			<?php ocart_show_grid_filters() ?>
			<div style="margin:20px 0"><iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FDress4Club%2F1409603325952688&amp;width=190&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:190px; height:290px;" allowTransparency="true"></iframe></div>
			<div id='the-fix'>
			<div style="margin:20px 0">
                <div class="newsletter newsletter-subscription">
					<form method="post" action="http://dress4club.com/wp-content/plugins/newsletter/do/subscribe.php" onsubmit="return newsletter_check(this)">
						<table cellspacing="5" cellpadding="0" border="0">
						<!-- email -->
							<tr><h3>NEWSLETTER SIGNUP123</h3></tr>
							<tr><td><input type="email" placeholder="Email" name="ne" size="20" required></td><td class="newsletter-td-submit"><input class="newsletter-submit" id="newsletter-submit" type="submit" value="Subscribe"/></td></tr>
							<tr></tr>
						</table>
					</form>
				</div>
				<!-- pint -->
				<div class='the-pint'>
					 <a data-pin-do="embedUser" href="http://www.pinterest.com/dress4club/" data-pin-scale-width="60" data-pin-scale-height="290" data-pin-board-width="188">Visit Dress4Club's profile on Pinterest.</a>
				</div>
			</div>
			</div>
		</div>
	
		<div class="catalog">

			<?php $posts = get_posts( array( 'post_type' => 'product', 'numberposts' => -1 ) ); ?>
			<div class="catalog_title"><ins><?php _e('All Products','ocart'); ?></ins><span><?php if (count($posts) == 1) { printf(__('<span class="totalprod"><span id="products_count">%s</span> Product Found</span>','ocart'), count($posts)); } else { printf(__('<span class="totalprod"><span id="products_count">%s</span> Products Found</span>','ocart'), count($posts)); } ?></span></div>
						<!-- data -->
			<?php
				// $paged 是页码号，默认0、1都是第一页，第二页会送2进来
				global $post, $paged;
				// 压栈$post
				$orig_post = $post;
				// 每页的内容数
				$post_per_page = 9;
				// 处理当前真正的页码，1是第一页，2是第二页
				$real_paged = ($paged <= 1) ? 1 : $paged;
				// 取得当前分类下所有的post
				$allpost = $posts; // 得到0起始下标的数组
				// var_dump($posts);
				$allpost_count = count($allpost);
				// echo($allpost_count);
				$total_paged = ceil($allpost_count / $post_per_page);
				// 避免页码越界，如果超过最大页数，设置为最大页，否则不改
				$real_paged = ($real_paged > $total_paged) ? $total_paged : $real_paged;
				$post_begin = ($real_paged - 1) * $post_per_page;
				$post_end = $post_begin + $post_per_page - 1;
				// 避免条数越界
				$post_end = ($post_end > ($allpost_count - 1)) ? ($allpost_count - 1) : $post_end;
				$i = 0;
			?>
			<?php if ($allpost): ?>
				<ul class="catalog_list">
			<?php
			    foreach ($allpost as $post) {
			        // 只显示当前页面内的
			        if ($i >= $post_begin && $i <= $post_end) {
			            setup_postdata($post);
			?>
			<li id="product-<?php the_ID(); ?>">
					
					<?php if (!ocart_get_option('disable_cart') && ocart_product_in_stock()) { ?>
					<div class="catalog_quickadd"><span><?php _e('Select Options','ocart'); ?></span></div>
					<?php } ?>
					
					<a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', '<?php the_ID(); ?>', '<?php echo get_permalink($post->ID); ?>');">
						
						<?php ocart_product('product_hover'); ?>
						<?php the_post_thumbnail( 'catalog-thumb', array('title' => '', 'class' => 'preload') ); ?>
						
						<?php if (!ocart_get_option('disable_cart')) { ?>
						
						<?php
						$status = get_post_meta($post->ID, 'status', true);
						// $link = get_post_meta($post->ID, 'buylink', true);
						$mark_as_onsale = get_post_meta($post->ID, 'mark_as_onsale', true);
						$mark_as_new = get_post_meta($post->ID, 'mark_as_new', true);
						if ($status == 'sold') {
						echo "<span class='catalog_item_status catalog_item_status_sold'>".__('Sold Out!','ocart')."</span>";
						} elseif (isset($mark_as_onsale) && $mark_as_onsale == 'on') {	
						echo "<span class='catalog_item_status catalog_item_status_sale'>".ocart_sticker_text('sale')."</span>";
						} elseif (isset($mark_as_new) && $mark_as_new == 'on' && ocart_is_new_product() ) {
						echo "<span class='catalog_item_status catalog_item_status_new'>".ocart_sticker_text('new')."</span>";
						}
						if (isset($mark_as_new) && isset($mark_as_onsale) && $mark_as_new == 'on' && $mark_as_onsale == 'on' && ocart_is_new_product() ) {
							echo '<div class="sticker_new">'.ocart_sticker_text('new', $wrap='span').'</div>';
						}
						?>
						
						<?php } ?>
		
						<span class="catalog_item_title">
							<span class="title"><?php the_title(); ?></span>
							
							<?php if (ocart_get_option('disable_cart') && ocart_get_option('disable_prices')) { } else { ?>
							<span class="price_orig"><?php ocart_product('plain_original_price'); ?></span>
							<span class="price">
								<?php ocart_product('price_in_grid'); ?>
								<?php if (ocart_has_product_tag()) { ?>
								<span class="catalog_item_options">
									<span class="catalog_item_options_div">
										<span class="arr"></span>
										<?php ocart_list_product_tag() ?>
									</span>
								</span>
								<?php } ?>
							</span>
							<?php } ?>
							
						</span>
					</a>
					<?php 
					$product_id = get_the_ID();
					$post2 = get_post($product_id);
					setup_postdata($post2);
					$link = get_post_meta($post2->ID, 'buylink', true);
					// echo($img);
					?>
					

					<div class="hover-box">
						<a href="<?php echo($link); ?>" id='buy-a' target='_blank'></a>
						<div id='bt'>
						<p><?php the_title(); ?></p>
						<ul>
							<li><iframe src="//www.facebook.com/plugins/like.php?href=<?php echo($link); ?>&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:35px;" allowTransparency="true"></iframe></li>
							<li><a target='_blank' href="//www.pinterest.com/pin/create/button/?url=<?php echo($link); ?>&media=<?php echo(wp_get_attachment_url(get_post_thumbnail_id($post_id -> $product_id))); ?>&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a></li>
							<li><a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', '<?php the_ID(); ?>', '<?php echo get_permalink($post->ID); ?>');" id='hover-a'>Details</a></li>
						</ul>
						</div>
					</div>
				</li>
			<?php } $i++; ?>
			<?php } ?>
				</ul>
			
			<?php endif; ?>
			<?php $post = $orig_post;?>
			<?php
			if (1 != $total_paged) { ?>
			    <div class="page-link">
			        <div id="pageintion">
			    <?php
			    /******* 显示分页链接 ********/
			    // 拿到当前页面链接
			    //total_paged 总数; real_paged 当前页码 //post_per_page 分几页
			    $cur_link = get_permalink();
			    $prev = $real_paged - 1;
			    $next = $real_paged + 1;
			    $showitems = ($range * 2) + 1;
			    //输出第一页
			    echo '<a href="' . get_pagenum_link(1) . '" alt="第一页" title="第一页" >上一頁</a>&nbsp;&nbsp;';
			    for ($i = 1; $i <= $total_paged; $i++) {
			        if (1 != $total_paged && (!($i >= $real_paged + $post_per_page + 1 || $i <= $real_paged - $real_paged - 1) || $total_paged <= $showitems)) {
			            echo ($real_paged == $i) ? "<span class='current'>" . $i . "</span>&nbsp;&nbsp;" : "<a 			href='" . get_pagenum_link($i) . "' class='inactive' >" . '['. $i . ']'. "</a>			&nbsp;&nbsp;";
			        }
			    }
			    //输出最后一页
			    echo '<a href="' . get_pagenum_link($next) . '" alt="最后页" title="下一页" >下一頁</a>';
			?>
			                        </div>
			                    </div><!---//page-link-->
			<?php } ?>

			<!-- end -->
			<div class="clear"></div>
		
		</div><div class="clear"></div>
	
	</div>
</div>