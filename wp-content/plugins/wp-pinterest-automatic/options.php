<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=138008592989586";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="wrap">
<form  method="post" novalidate="" autocomplete="off">
<?php

$licenseactive=get_option('wp_pinterest_automatic_license_active','');

//purchase check 
if(isset($_POST['wp_pinterest_automatic_license']) && trim($licenseactive) == '' ){

	//save it
	update_option('wp_pinterest_automatic_license' , $_POST['wp_pinterest_automatic_license'] );
	
	//activating
	//curl ini
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER,0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT,20);
	curl_setopt($ch, CURLOPT_REFERER, 'http://www.bing.com/');
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8');
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Good leeway for redirections.
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Many login forms redirect at least once.
	curl_setopt($ch, CURLOPT_COOKIEJAR , "cookie.txt");
	
	//curl get
	$x='error';

	//change domain ?
	$append='';
	
	if( isset($_POST['wp_pinterest_options']) && in_array('OPT_CHANGE_DOMAIN', $_POST['wp_pinterest_options']) ){
		$append='&changedomain=yes';
	}
	
	$url='http://deandev.com/license/index.php?itm=2203314&domain='.$_SERVER['HTTP_HOST'].'&purchase='.$_POST['wp_pinterest_automatic_license'].$append;
	
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_URL, trim($url));
	while (trim($x) != ''  ){
		$exec=curl_exec($ch);
		 $x=curl_error($ch);
	}
	
	$resback=$exec;
	
	$resarr=json_decode($resback);
	
	if(isset($resarr->message)){
		$wp_pinterest_active_message=$resarr->message;
		
		//activate the plugin
		update_option('wp_pinterest_automatic_license_active', 'active');
		update_option('wp_pinterest_automatic_license_active_date', time('now'));
		$licenseactive=get_option('wp_pinterest_automatic_license_active','');
		
	}else{
		if(isset($resarr->error))
		$wp_pinterest_active_error=$resarr->error;
	}
	
	
	
}

// SAVE DATA
if (isset ( $_POST ['wp_pinterest_user'] )) {
	
	 
	
	foreach ( $_POST as $key => $val ) {
		update_option ( $key, $val );
	}
	echo '<div class="updated"><p>Changes saved</p></div>';
}

$dir = WP_PLUGIN_URL . '/' . str_replace ( basename ( __FILE__ ), "", plugin_basename ( __FILE__ ) );

$wp_pinterest_user = get_option ( 'wp_pinterest_user', '' );
$wp_pinterest_pass = get_option ( 'wp_pinterest_pass', '' );
$wp_pinterest_default = get_option ( 'wp_pinterest_default', '{awesome|nice|cool} [post_title]' );
$wp_pinterest_board = get_option ( 'wp_pinterest_board', '' );
$wp_pinterest_options = get_option ( 'wp_pinterest_options', array (
		'OPT_CHECK',
		'OPT_PIN' 
) );
$wp_pinterest_types = get_option ( 'wp_pinterest_types', array (
		'post',
		'page',
		'product' 
) );
$wp_pinterest_options = array_merge ( $wp_pinterest_options, $wp_pinterest_types );
$wp_pinterest_options = implode ( '|', $wp_pinterest_options );
$wp_pinterest_boards = get_option ( 'wp_pinterest_boards', array (
		'ids' => array (),
		'titles' => array () 
) );
$wp_pinterest_boards_ids = $wp_pinterest_boards ['ids'];
$wp_pinterest_boards_titles = $wp_pinterest_boards ['titles'];
$wp_pinterest_automatic_selector = get_option ( 'wp_pinterest_automatic_selector', '' );
$wp_pinterest_automatic_tax = get_option('wp_pinterest_automatic_tax','category,product_cat');
?>
<h2>
	General Settings <input type="submit" class="button-primary" value="Save Changes" name="save">
</h2>

<br class="clear">

<div class="metabox-holder columns-1" id="dashboard-widgets">
	<div style="" class="postbox-container" id="postbox-container-1">
		<div class="meta-box-sortables ui-sortable" id="normal-sortables">
		
			<?php if(trim($licenseactive) != '') { ?>
		
			<div class="postbox">
				<div title="Click to toggle" class="handlediv">
					<br>
				</div>
				<h3 class="hndle">
					<span>Basic Settings</span>
				</h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							
							<tr>
								<th scope="row"><label for="field-wp_pinterest_user"> Pinterest Login Email   </label></th>
								<td><input class="widefat" value="<?php echo $wp_pinterest_user  ?>" name="wp_pinterest_user" id="field-wp_pinterest_user" required="required" type="text"></td>
							</tr>

							<tr>
								<th scope="row"><label for="field-wp_pinterest_user"> Pinterest Password   </label></th>
								<td><input class="widefat" value="<?php echo $wp_pinterest_pass  ?>" name="wp_pinterest_pass" id="field-wp_pinterest_pass" required="required" type="password"></td>
							</tr>


							<tr>
								<th scope="row"><label for="field-wp_pinterest_user"> Default Pin Text   </label></th>
								<td><input class="widefat" value="<?php echo $wp_pinterest_default  ?>" name="wp_pinterest_default" id="field-wp_pinterest_default" required="required" type="text">

									<div class="description">
										Supported tags: <abbr title="Image alternative text">[image_alt]</abbr> , <abbr title="Post title">[post_title]</abbr> , <abbr title="the post excerpt">[post_excerpt]</abbr> , <abbr title="The post title">[post_author]</abbr> , <abbr title="the post url">[post_link]</abbr> , <abbr title="Post tags as pinterest hashtags">[post_tags]</abbr>
									</div></td>
							</tr>

							<tr>
								<th scope="row"><label for="field-wp_pinterest_board"> Default Pin Board ? </label></th>
								<td><select name="wp_pinterest_board" id="field1zz" required="required">
						      		
						      		<?php
														$i = 0;
														
														foreach ( $wp_pinterest_boards_ids as $id ) {
															?>
						      		
									<option value="<?php echo $id ?>" <?php wp_pinterest_automatic_opt_selected( $id ,$wp_pinterest_board) ?>><?php echo $wp_pinterest_boards_titles[$i]?></option>
						      			
						      		<?php
															$i ++;
														}
														
														?> 
						      	</select>  <a href="<?php echo site_url('/?wp_pinterest_automatic=boards')  ?>"><button id="get_boards" >fetch boards</button><img alt="" id="ajax-loadingimg" class="ajax-loading" src="images/wpspin_light.gif" style=" margin: 3px"></a>

									<div class="description">Select what pin board will be the default one so we select it for you by default .</div></td>
							</tr>

							

							<tr>
								<th scope="row"><label> Pin Box </label></th>
								<td><input name="wp_pinterest_options[]" id="field-wp_pinterest_options-1" value="OPT_PIN" type="checkbox"> <span class="option-title"> Expand pinning box on editing page </span></td>
							</tr>


							<tr>
								<th scope="row"><label> Auto check </label></th>
								<td><input name="wp_pinterest_options[]" id="field-wp_pinterest_options-1" value="OPT_CHECK" type="checkbox"> <span class="option-title"> Auto check first image to be pinned </span></td>
							</tr>

							<tr>
								<th scope="row"><label> Auto Pin </label></th>
								<td><input name="wp_pinterest_options[]" id="field-wp_pinterest_options-1" value="OPT_BOT" type="checkbox"> <span class="option-title"> Auto pin first image of bots posts (like wordpress robot or wordpress automatic ,etc) </span></td>
							</tr>

							<tr>
								<th scope="row"><label for="field-wp_pinterest_user"> Post types   </label></th>
								<td>
								<?php
								$post_types = get_post_types ();
								
								foreach ( $post_types as $post_type ) {
									?>
						
											 
											<input name="wp_pinterest_types[]" value="<?php echo $post_type ?>" type="checkbox"> <span class="option-title">
								       			 <?php echo $post_type ?> 
								                </span>
											 
											
										    <?php
								}
								
								?>
														
								<div class="description">Choose what post types the plugin will support so it shows it's box when you edit this post type</div>
								</td>

								
							</tr>




						</tbody>
					</table>
				</div>
			</div>

			<div class="postbox">
				<div title="Click to toggle" class="handlediv">
					<br>
				</div>
				<h3 class="hndle">
					<span>Category to board</span>
				</h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label>Active ?</label></th>
								<td> <input data-controls="ctb_container" name="wp_pinterest_options[]" id="field-ctb_enable-1" value="OPT_CTB" type="checkbox">  Enable category to board
								
								<div id="ctb_container" class="ctb-contain" style="padding-bottom: 20px;">

								
								
								
								
								<?php
								
								$wp_pinterest_automatic_wordpress_category = get_option ( 'wp_pinterest_automatic_wordpress_category', array (
										'' 
								) );
								
								$wp_pinterest_automatic_pinterest_category = get_option ( 'wp_pinterest_automatic_pinterest_category', array () );
								
								$pinterest_category = 0;
								
								$tax_txt=$wp_pinterest_automatic_tax;
								
								if(! stristr($tax_txt, 'category') ){
									$tax_txt='category,product_cat';
								}
								
								$tax=explode(',', $tax_txt);
								$tax=array_filter($tax);
								$tax=array_map('trim', $tax);
								
								
								
								foreach($tax as $key=>$taxitm){
									if(!taxonomy_exists($taxitm)){
										unset($tax[$key]);
									}
								}
								
								
								$cats = get_categories ( array (
											'hide_empty'               => 0 ,
											'taxonomy'                 => $tax
								) );
								
								?>
						 
								<?php
								$n = 0;
								foreach ( $wp_pinterest_automatic_wordpress_category as $wp_category ) {
									
									?> 
								 
								<div class="ctb">
		
											<div id="field-pinterest_category-container">
												<label> Category : </label> 
													<select name="wp_pinterest_automatic_wordpress_category[]" id="field1zza">
														
														<?php
															
															foreach ( $cats as $cat ) {
																?>
																	<option value="<?php echo $cat->term_id ?>" <?php wp_pinterest_automatic_opt_selected($cat->term_id,$wp_category) ?>><?php echo $cat->cat_name ?></option> 
																	<?php
															}
														?>
										
													</select>
		
		
												<label for="field-pinterest_category"> to Board </label> 
												
												<select name="wp_pinterest_automatic_pinterest_category[]" id="field1zzb">
										
													<?php
												$i = 0;
												
												foreach ( $wp_pinterest_boards_ids as $id ) {
													?>
											      		
														<option value="<?php echo $id ?>" <?php wp_pinterest_automatic_opt_selected( $id ,$wp_pinterest_automatic_pinterest_category[$n]) ?>><?php echo $wp_pinterest_boards_titles[$i]?></option>
											      			
											      		<?php
													$i ++;
												}
												
												?>
										
		 
												</select>
		
												<button class="ctb_add">+</button>
												<button class="ctb_remove">x</button>
		
											</div>
		
										</div>
										<!-- ctb contain-->
									
									<?php $n++;}?>
									
									</div>
									
									<div class="description">Select what post category will post to what board</div>

								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>
			
			<div class="postbox">
				<div title="Click to toggle" class="handlediv">
					<br>
				</div>
				<h3 class="hndle">
					<span>Advanced Settings (optional) </span>
				</h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							
							<tr><th scope="row"><label>Be Carefull</label> </th><td>  <div class="description">Faulty settings in this fields may break the plugin working. ask for support if you need so .</div></td></tr>
						
							<tr>
								<th scope="row"><label>Detect images from this custom field</label></th>
								<td><input class="widefat" value="<?php echo get_option('wp_pinterest_automatic_cf' )  ?>" name="wp_pinterest_automatic_cf" type="text">

									<div class="description">If your theme uses a custom field for the tumbnail and you like to use it to detect image from add this field name</div></td>
							</tr>

							<tr>
								<th scope="row"><label for="field-wp_pinterest_user">Custom jQuery selector</label></th>
								<td><input class="widefat" value="<?php echo $wp_pinterest_automatic_selector  ?>" name="wp_pinterest_automatic_selector" type="text">

									<div class="description">By default, the plugin searchs the editor for images but if your images are visible elsewhere then This selector for admin page will be used by the plugin to list images in it to check and pin . use a jQuery selector like ".my_class" for a div named "my_class" or "#my_id" for div with ID of "my_id".</div></td>
							</tr>
							
							<tr>
								<th scope="row"><label>Categories Taxonomies</label></th>
								<td><input class="widefat" value="<?php echo $wp_pinterest_automatic_tax  ?>" name="wp_pinterest_automatic_tax" type="text">

									<div class="description">By default, the plugin lists categories above on the category to board section from posts and woo-commerce product post type , but if you have another post type with a categories taxonomy you can add this taxonomy comma separated .</div></td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>			
							
			
			<div class="postbox">
				<div title="Click to toggle" class="handlediv">
					<br>
				</div>
				<h3 class="hndle">
					<span>Cron Setup (optional) </span>
				</h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							
							
						
							<tr>
								<th scope="row"><label>Cron command</label></th>
								<td><input class="widefat" value="<?php echo 'curl '. site_url('?wp_pinterest_automatic=cron')  ?>"   type="text">

									<div class="description">By Default, the plugin uses built-in wordpress cron that is triggered by site visiotrs but you can still setup a cron job to call processing the queue . make it every minute .</div></td>
							</tr>

							
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="postbox">
				<div title="Click to toggle" class="handlediv">
					<br>
				</div>
				<h3 class="hndle">
					<span>Call from the author </span>
				</h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							
							
						
							<tr>
								<th scope="row"><label>Keep Us Going </label></th>
								<td>

									<p>If you don't know me, I'm <a href="http://deandev.com">Mo Atef</a>, the developer of the <a href="http://codecanyon.net/item/pinterest-automatic-pin-wordpress-plugin/2203314">pinterest automatic</a> project started at MAR 2012 and some <a href="http://codecanyon.net/user/DeanDev/portfolio">other projects</a>. Since this date I made every effort to maintain the plugin keeping it working due to frequent pinterest changes and adding new features. <br><br>Unfortunately now the financial returns from the plugin is not sufficient to keep me going especially that I'm working full time on my wordpress plugins. So PLEASE SUPPORT ME by any mean of the listed below .  </p></td>
							</tr>
							
							<tr>
								<th scope="row"><label>Ways to support us </label></th>
								<td>

									<p> <table><tbody>
									
									<tr>	 <td>Donate by buying a another licenses</td> <td>Rate us 5 stars</td> <td>Share on facebook</td> <td>Tweet    </td>  <td>Check our other work</td> 						</tr>
									
									
									<tr><td  style="vertical-align: top;" ><a target="_blank" href="http://codecanyon.net/item/pinterest-automatic-pin-wordpress-plugin/2203314?ref=deandev"><img src="<?php echo plugins_url('images/buy.png',__FILE__) ?>" /></a></td><td  style="vertical-align: top;"><a  target="_blank"  href="http://codecanyon.net/downloads"><img src="<?php echo plugins_url('images/5stars.png',__FILE__) ?>" /></a></td><td  style="text-align: center;vertical-align: top;" > <div class="fb-share-button" data-href="http://codecanyon.net/item/pinterest-automatic-pin-wordpress-plugin/2203314?ref=deandev" data-type="button"></div></td><td  style="vertical-align: top;" ><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://codecanyon.net/item/pinterest-automatic-pin-wordpress-plugin/2203314?ref=deandev" data-text="Auto pin wordpress images to pinterest plugin " data-related="bydeandev" data-lang="en" data-size="large" data-count="none">Tweet</a>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script><br></td><td  style="vertical-align: top;"><a target="_blank" href="http://codecanyon.net/user/DeanDev/portfolio?ref=deandev"><img src="<?php echo plugins_url('images/portfolio.png',__FILE__) ?>" /></a></td></tr></tbody></table> </p></td>
							</tr>
							

							
						</tbody>
					</table>
				</div>
			</div>
			
			<?php } ?>			
			
			<div class="postbox <?php if(trim($licenseactive)!='')  echo 'closed'  ?>">
				<div title="Click to toggle" class="handlediv">
					<br>
				</div>
				<h3 class="hndle">
					<span>License </span>
				</h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							
							
						
							<tr>
								<th scope="row"><label>Purchase Code</label></th>
								<td><input class="widefat" name="wp_pinterest_automatic_license" value="<?php echo get_option('wp_pinterest_automatic_license','') ?>"   type="text">

									<div class="description">If you don't know what is your purchase code check this <a href="http://www.youtube.com/watch?v=Ii2V4mj7X_o">video</a> on how to get it   .</div></td>
							</tr>
							
							<?php if( isset($wp_pinterest_active_error) && stristr($wp_pinterest_active_error,	 'another')  ) {?>
							
							<tr>
								<th scope="row"><label> Change domain </label></th>
								<td><input name="wp_pinterest_options[]" id="field-wp_pinterest_options-1" value="OPT_CHANGE_DOMAIN" type="checkbox"> <span class="option-title"> Disable license at the other domain and use it with this domain </span></td>
							</tr>
							
							<?php } ?>
							
							<tr>
								<th scope="row"><label>License Status</label></th>
								<td>

									<div class="description"><?php 
									
									if(trim($licenseactive) !=''){
										echo 'Active';
									}else{
										echo 'Inactive ';
										if(isset($wp_pinterest_active_error)) echo '<p><span style="color:red">'.$wp_pinterest_active_error.'</span></p>';
									}
									
									?></div></td>
							</tr>

							
						</tbody>
					</table>
				</div>
			</div>
			
			

		</div>
	</div>
	<!-- end .postbox-container -->

	<div style="" class="postbox-container" id="postbox-container-2">
		<div class="meta-box-sortables ui-sortable empty-container" id="side-sortables"></div>
	</div>
	<!-- end .postbox-container -->

	<div style="" class="postbox-container" id="postbox-container-3">
		<div class="meta-box-sortables ui-sortable empty-container" id="column3-sortables"></div>
	</div>
	<!-- end .postbox-container -->

	<div style="" class="postbox-container" id="postbox-container-4">
		<div class="meta-box-sortables ui-sortable empty-container" id="column4-sortables"></div>
	</div>
	<!-- end .postbox-container -->

	<input style="margin-left:10px" type="submit" name="save" value="Save Changes" class="button-primary">
	
</div>
</form>
</div><!-- wrap -->
 <script type="text/javascript">
    var $vals = '<?php echo  $wp_pinterest_options ?>';
    $val_arr = $vals.split('|');
    jQuery('input:checkbox').removeAttr('checked');
    jQuery.each($val_arr, function (index, value) {
        if (value != '') {
            jQuery('input:checkbox[value="' + value + '"]').attr('checked', 'checked');
        }
    });
</script>
	