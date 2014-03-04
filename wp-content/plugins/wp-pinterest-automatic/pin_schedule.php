<?php
add_filter ( 'cron_schedules', 'wp_pinterest_automatic_once_a_minute' );
function wp_pinterest_automatic_once_a_minute($schedules) {
	
	// Adds once weekly to the existing schedules.
	$schedules ['once_a_minute'] = array (
			'interval' => 1000,
			'display' => __ ( 'Once 1000 seconds!!' ) 
	);
	return $schedules;
}

if (! wp_next_scheduled ( 'wp_pinterest_automatic_pin_hook' )) {
	wp_schedule_event ( time (), 'once_a_minute', 'wp_pinterest_automatic_pin_hook' );
}

add_action ( 'wp_pinterest_automatic_pin_hook', 'wp_pinterest_automatic_pin_function' );
function wp_pinterest_automatic_pin_function() {

	$licenseactive=get_option('wp_pinterest_automatic_license_active','');
	
	if(trim($licenseactive) == '' ) return ;
	 
	$lastrun=get_option('wp_pinterest_last_run',1392146043);
	
	$timenow=time('now');
	
	$timediff=$timenow - $lastrun ;
	
	if($timediff < 60 ) {
	
		return;
	}
	
	update_option('wp_pinterest_last_run', $timenow);
	
	//check we are deactivated
	$deactive = get_option('wp_pinterest_automatic_deactivate', 5 );

	if($timenow < $deactive ) {
		return ;
	}

	//BOTS POSTS
	global $post;
	// display posts having the wp_pinterest_automatic_bot custom field
	$the_query = new WP_Query ( array (
			'post_status'=>'publish',
			'posts_per_page' => 100,
			'meta_query' => array (
	
					array (
	
							'key' => 'wp_pinterest_automatic_bot',
							'compare' => 'EXISTS'
					),
					array(
							'key' => 'wp_pinterest_automatic_bot_processed',
							'compare' => 'NOT EXISTS'
							
					)
			),
			'post_type' => 'product' ,
			'ignore_sticky_posts' => true
	) );
	
	// loop
	$i = 1;
	if ($the_query->have_posts ()) {
			
		while ( $the_query->have_posts () ) {
	
			$the_query->the_post ();
			$post_id = $post->ID;

			
			
			//check thumbnail url 
			$post_title=$post->post_title;
			$cont=$post->post_content;
			
			//extracing thumbnail if not extracting first image
			$post_thumbnail_id = get_post_thumbnail_id($post_id);
			$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
			$img=$post_thumbnail_url;
			$txtalt = get_post_meta($post_thumbnail_id , '_wp_attachment_image_alt', true);
			
			//if no featured image check custom field image
			if(trim($img) == ''){
				$customf=get_option('wp_pinterest_automatic_cf','');
				if(trim($customf) != ''){
					//get custom field value
					$imgsrc_custom=get_post_meta($post_id,$customf,true);
					
					if(trim($imgsrc_custom) != ''){
						//good found value for the custom field let's check if image
						$img = $imgsrc_custom;
					}
					
				}
				
				
			}
			
			
			if(trim($img) == ''){//no thumb lets extract first image
				preg_match_all('/<img [^>]*src=["|\']([^"|\']+)["|\'].*?>/i', $cont, $matches);
				@$imgs=$matches[1];
				$img='';
				@$img=$imgs[0];
				
				$txtalt='';
				$img_html=$matches[0][0];
					
				preg_match_all('/alt="([^"]*)"/i',$img_html, $alt);
					
				@$txtalt=$alt[1][0];
				
					
					
					
			}else{
				//$pinterest->log('Bot post >> Featured image found',$img );
			}
			
			if (trim($img) != '' ){
					
					
				require_once(str_replace('pin_schedule.php','core.php',__FILE__));
				$pinterest=new pinterest;
				$pinterest->log('Bot post','Found bot post '.$post_id. ' with images in content ' );
					
				$pinterest->log('Bot post >> Add image to queue',$img );
					
				$pin_images=array(($img));
				$wp_pinterest_user=get_option('wp_pinterest_user','');
				$wp_pinterest_pass=get_option('wp_pinterest_pass','');
				$pin_board=get_option('wp_pinterest_board','');
				
				
				$pin_text=get_option('wp_pinterest_default','');
			
				
			
				update_post_meta($post_id,'pin_images',$pin_images);
				update_post_meta($post_id,'pin_text',$pin_text);
				update_post_meta($post_id,'pin_board',$pin_board);
					
				update_post_meta($post_id,'pin_alt',array($txtalt));
					
				update_post_meta($post_id,'pin_index',$pin_images);
					
				update_post_meta($post_id,'pin_try',0);
			
				//building image trials array
				foreach($pin_images as $pin_image){
					$images_try [md5($pin_image)] = 0 ;
				}
			
				update_post_meta($post_id,'images_try',$images_try);
			
			
				//found image
			}
			
			//delete bot to process flag
			delete_post_meta($post_id, 'wp_pinterest_automatic_bot');

			//add bot processed flag
			update_post_meta($post_id, 'wp_pinterest_automatic_bot_processed', 'yes');
			
			$i ++;
		}
	}
	
	
	
	
	// PROCESS QUEUE
	global $post;
	$posts_displayed = array ();
		
	$the_query = new WP_Query ( array (
				
			'posts_per_page' => 1,
			'post_status' => 'publish',
			'meta_query' => array (
						
					array (
								
							'key' => 'pin_images',
							'compare' => 'EXISTS'
					)
			),
				
			'orderby' => 'meta_value_num',
			'meta_key' => 'pin_try',
			'order' => 'ASC',
			'post_type' => 'product' ,
			'ignore_sticky_posts' => true
	) );
	
	
	
	if ($the_query->have_posts ()) {
	
		while ( $the_query->have_posts () ) {
	
			$the_query->the_post ();
			$post_id = $post->ID;
			
			
			//incrment trial for this post 
			$pin_trial=get_post_meta($post_id , 'pin_try',1);
			$pin_trial = $pin_trial +1;
			update_post_meta($post_id,'pin_try',$pin_trial);
			
			//get pin variables
			$pin_images=get_post_meta($post_id,'pin_images',1);
			
			//check if pin_images field contains valid images
			if( ! is_array($pin_images) ) delete_post_meta($post_id, 'pin_images');
			
			
			$pin_text=get_post_meta($post_id,'pin_text',1);
			$pin_board=get_post_meta($post_id,'pin_board',1);
			$pin_alt=get_post_meta($post_id,'pin_alt',1);
			$images_index=get_post_meta($post_id,'pin_index',1);
			$post_title=get_the_title($post_id);
			$images_try_pre=get_post_meta($post_id,'images_try',1);
			
			foreach($pin_images as $pin_img){
				
				if(! isset($images_try_pre[md5($pin_img)]) || ! is_numeric($images_try_pre[md5($pin_img)]) ){
					$current_try=0;
				}else{
					$current_try = $images_try_pre[md5($pin_img)] ;
				}
				
				$images_try[md5($pin_img)]=$current_try;
			}
			
			//CTB CHECK
			$wp_pinterest_options=get_option('wp_pinterest_options',array());
			
			if(in_array('OPT_CTB', $wp_pinterest_options)){
					
				$default_board=get_option('wp_pinterest_board','');
					
				$wp_pinterest_automatic_wordpress_category = get_option ( 'wp_pinterest_automatic_wordpress_category', array ());
				$wp_pinterest_automatic_pinterest_category = get_option ( 'wp_pinterest_automatic_pinterest_category', array () );
					
				//check if this is a default board or user selected
				if(trim($default_board) == $pin_board){
					
					//get categories
					$tax_txt=get_option('wp_pinterest_automatic_tax','category,product_cat');
					
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
					
					$n=0;
					foreach($wp_pinterest_automatic_wordpress_category as $cat ){
			
						if( has_term($cat,$tax,$post_id)  ){
							//get board matching this category
							$pin_board=$wp_pinterest_automatic_pinterest_category[$n];
							break;
						}
							
						$n++;
					}
				}
			
			}
			
			//process pinning for one image of that post with id = $post_id
			require_once(str_replace('pin_schedule.php','core.php',__FILE__));
			$pinterest=new pinterest;
			$pinterest->log('Cron >> Pinning Post','Post with id {'.$post_id.'} has '.count($pin_images). ' scheduled pins'  );
			
			
			//logging
			$wp_pinterest_user=get_option('wp_pinterest_user','');
			$wp_pinterest_pass=get_option('wp_pinterest_pass','');
			

			//getting image with lowest try value 
			$min_try_val=min($images_try);
			
			if(! is_numeric($min_try_val)) $min_try_val=0;
			
			foreach($pin_images as $pin_image){
				if($images_try[md5($pin_image)] == $min_try_val ) break;
			}
			
			//increment try value 
			$images_try[md5($pin_image)]=$min_try_val +1 ;
			
			update_post_meta($post_id, 'images_try', $images_try);
			
			$pinterest->log('Cron >> Pinning image', $pin_image   );
			
			//pinning the image if successfull pin remve it from pin images array
			$wp_pinterest_user=get_option('wp_pinterest_user','');
			$wp_pinterest_pass=get_option('wp_pinterest_pass','');
				
			
			if( !(trim($wp_pinterest_user)== ''  | trim($wp_pinterest_pass) == ''  | trim($pin_board)== ''  | trim($pin_text) == '' )){
					
				$tocken=$pinterest->pinterest_login($wp_pinterest_user,$wp_pinterest_pass);
			
				if(trim($tocken) != ''){
					//valid login let's pin
					
						$sp= new Spintax;
							
						$pintext=$sp->spin($pin_text);
							
						if(trim($pintext == '')){
							$pintext= $pin_text ;
						}
							
						$i=0;
						foreach($images_index as $image){
							if($pin_image == $images_index[$i]){
								break;
							}
							$i++;
						}
							
							
						$thepost=get_post($post_id);
						$user=get_userdata( $thepost->post_author  );
						$username=$user->display_name;
						$pintext=str_replace('[post_title]',$post_title,$pintext);
						$pintext=str_replace('[post_excerpt]',  $thepost->post_excerpt ,$pintext);
						$pintext=str_replace('[post_content]', $thepost->post_content ,$pintext);
						$pintext=str_replace('[post_author]', $username ,$pintext);
						$pintext=str_replace('[post_link]', $thepost->guid ,$pintext);
						@$pintext=str_replace('[image_alt]',  $pin_alt[$i] ,$pintext);

						
						//get tags
						if(stristr($pintext, '[post_tags]')){
							//get tags
							$tags=wp_get_post_tags($post_id);
						
							$tag_text= '';
							foreach($tags as $tag){
								$tag_text = $tag_text .' #'. $tag->name;
							}
						
							$pintext=str_replace('[post_tags]', $tag_text ,$pintext);
								
						}
						
						
						$pinstatus=$pinterest->pinterest_pin($tocken,$pin_board,$pintext,get_permalink( $post_id ),$pin_image);
						
						if($pinstatus == true){
							$pins=get_post_meta($post_id,'pins',1);
							if(! is_array($pins)) $pins = array();
							$pins[]=$pin_image;
							update_post_meta($post_id,'pins',$pins);
						}
			
						if($min_try_val >= 2 ) $pinterest->log('Skipping image','Due to 3 failed pin trial for the image it will be skipped and removed from queue');
						
						if($pinstatus == true || $min_try_val >= 2){ 	
							$pin_images=array_filter($pin_images);
							//clear queue
							if(count($pin_images) == 1){
								//last image delete all
								delete_post_meta($post_id,'pin_images');
							}else{
								//delete this image only 
								foreach($pin_images as $pinimg){
									if($pinimg != $pin_image) $newpinimages[]=$pinimg;
								}
								
								update_post_meta($post_id, 'pin_images', $newpinimages);
								
							}
						}
					
			
				}//trim(tocken)
			}//COMPLETE DATA
			
			break; 
		}
	}
	
	 
	update_option('wp_pinterest_p', $post_id);
	
	wp_reset_postdata();
	
	
}