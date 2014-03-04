<?php
/*
 Plugin Name:Wordpress Pinterest Automatic
 Plugin URI: http://deandev.com/wppinterest/
 Description: Pin Images in your posts when you publish it
 Version: 3.0.0
 Author: Atef
 Author URI: http://deandev.com/
 */

/*  Copyright 2012-2014  Wordpress Pinterest Automatic  (email : sweetheatmn@gmail.com) */


// PIN SCHEDULE PROCESS
require_once 'pin_schedule.php';

//AJAX UPDATE PINNED
require_once 'pajax.php';

//PIN THEM BULK ACTION
require_once 'pactions.php';



/* Add a new meta box to the admin menu. */
add_action( 'admin_menu', 'wppinterest_create_meta_box' );
add_action('admin_print_scripts-' . 'post-new.php', 'wp_pinterest_automatic_admin_scripts_new');
add_action('admin_print_scripts-' . 'post.php', 'wp_pinterest_automatic_admin_scripts2');
add_action('admin_print_scripts-' . 'edit.php', 'wp_pinterest_automatic_admin_edit');

//bulk pin scripts
function wp_pinterest_automatic_admin_edit(){
	wp_enqueue_script(
	'wp_pinterest_automatic_bulk_pin',plugins_url( '/js/bulk-pin.js' , __FILE__ )
	);
}

//create metabox
function wppinterest_create_meta_box() {

		$wp_pinterest_types=get_option('wp_pinterest_types',array('post','page'));
		foreach($wp_pinterest_types as $post_type){
			add_meta_box( 'wppinterest-meta-boxes', 'Pinterest Automatic', 'wppinterest_meta_boxes', $post_type, 'side', 'high' );
		
		}
}

//metabox function
function wppinterest_meta_boxes(){
	require_once('metabox.php');
}


//scripts for queue page
function wp_pinterest_automatic_queue_scripts(){
	wp_enqueue_style( 'wp_pinterest_automatic-admin-style', plugins_url('css/style.css', __FILE__) );
}


// scripts for options page
function wp_pinterest_automatic_admin_scripts(){
	//jquery
	wp_enqueue_script('jquery');

	 
 
	
	//jquery otions
	wp_enqueue_script(
				'wp_pinterest_automatic_jquery_options',plugins_url( '/js/options.js' , __FILE__ )
	);


	//styles
	wp_enqueue_style( 'wp_pinterest_automatic-options-style', plugins_url('css/options.css', __FILE__) );



}

//scripts for log page
function wp_pinterest_automatic_admin_scripts_log(){
	
	 
	//jquery
	wp_enqueue_script('jquery');

	//jquery tools
	wp_enqueue_script(
			'wp_pinterest_automatic_jquery_tools',plugins_url( '/js/jquery.tools.js' , __FILE__ )
	);

	//jquery uniform
	wp_enqueue_script(
			'wp_pinterest_automatic_jquery_uniform',plugins_url( '/js/jquery.uniform.min.js' , __FILE__ )
	);

	//jquery main
	wp_enqueue_script('wp_pinterest_automatic_jquery_main',plugins_url( '/js/main_log.js' , __FILE__ ));
	 
	
	wp_enqueue_style( 'wp_pinterest_automatic-admin-style', plugins_url('css/style.css', __FILE__) );
	
	
	wp_enqueue_style( 'wp_pinterest_automatic-admin-style2', plugins_url('css/uniform.css', __FILE__) );
	
}


//post.php scripts
function wp_pinterest_automatic_admin_scripts2(){

	//jquery
	wp_enqueue_script('jquery');
 
	//jquery main
	wp_enqueue_script(
				'wp_pinterest_automatic_jquery_main',plugins_url( '/js/main.js' , __FILE__ )
	);

	wp_enqueue_style( 'wp_pinterest_automatic-admin-style', plugins_url('css/style.css', __FILE__) );
		
}

//post-new.php scripts
function wp_pinterest_automatic_admin_scripts_new(){ 
	//jquery
	wp_enqueue_script('jquery');

	//jquery main
	wp_enqueue_script(
			'wp_pinterest_automatic_jquery_main-new',plugins_url( '/js/main.post_new.js' , __FILE__ )
	);

	wp_enqueue_style( 'wp_pinterest_automatic-admin-style', plugins_url('css/style.css', __FILE__) );
}

//function select 
if(! function_exists('wp_pinterest_automatic_opt_selected')){

	function wp_pinterest_automatic_opt_selected($src,$val){
		echo 'src='.trim($src) . ' dest='.trim($val);
		if (trim($src) === trim($val)) {
				
			echo ' selected="selected" ';
		}
			
	}
}

// adding menu to dashboard
if(is_admin())
{
	add_action('admin_menu', 'wppinterest_automatic_init');
}

function wppinterest_automatic_init()
{
	$hook=add_menu_page('Pinterest Automatic', 'Pinterest<br> <small>Automatic</small>', 'administrator', 'wppinterestautomatic', 'wppinterestautomatic', plugins_url('wp-pinterest-automatic/images/icon.png'), 1000);
	add_submenu_page('wppinterestautomatic', 'Pinterest automatic action log', 'Action Log', 'manage_options', 'wppinterestautomatic' );
	
	$page_hook_suffix=add_submenu_page( 'wppinterestautomatic', 'Waiting queue', ' Waiting queue', 'administrator', 'wppinterestautomaticq', 'wppinterestautomaticq' );
	add_action('admin_print_scripts-' . $page_hook_suffix, 'wp_pinterest_automatic_queue_scripts');
	
	$page_hook_suffix=add_submenu_page( 'wppinterestautomatic', 'Settings', ' Settings', 'administrator', 'wppinterestautomatics', 'wppinterestautomatics' );
	
	
	add_action('admin_print_scripts-' . $page_hook_suffix, 'wp_pinterest_automatic_admin_scripts');
	add_action('admin_print_scripts-' . $hook, 'wp_pinterest_automatic_admin_scripts_log');
	
	
	
}

//check curl , conflict with wp pinner
function wp_pinterest_automatic_admin_notice() {
	
	if( ! function_exists('curl_init')){
	
	?>
<div class="error">
	<p><?php echo 'Curl is not installed . it should be installed for <strong>"Wordpress Pinterest Automatic"</strong> to work !'; ?></p>
</div>
<?php
	
	}
	
	if(  function_exists('wppinner_publishpost_data')){
	
		?>
<div class="error">
	<p><?php echo 'WP Pinner plugin should be deactivated for <strong>"Wordpress Pinterest Automatic"</strong> to work !'; ?></p>
</div>
<?php
		
		}
    
}
add_action( 'admin_notices', 'wp_pinterest_automatic_admin_notice' );

function wppinterestautomaticq(){
	require_once 'pin_queue.php';
}


function wppinterestautomatics(){
	require_once(dirname(__FILE__).'/options.php');
}

/* Saves the meta box data */
add_action( 'save_post', 'wp_pinterest1_save_meta_data' );

$wp_pinterest_types=get_option('wp_pinterest_types',array('post','page'));
foreach($wp_pinterest_types as $post_type){
	add_action('publish_'.$post_type,'wp_pinterest_publish');
}

function wp_pinterest_publish($post_id){
	
	
	//check if scheduled ?
	$pin_images=get_post_meta($post_id,'pin_images',1);
	if(0){
		/*
		require_once(str_replace('pinterest-auto.php','core.php',__FILE__));
		$pinterest=new pinterest;
		$pinterest->log('Pinning scheduled','Scheduled post with id {'.$post_id.'} has '.count($pin_images). ' scheduled pins'  );
		
		//get pin variables
		$pin_text=get_post_meta($post_id,'pin_text',1);
		$pin_board=get_post_meta($post_id,'pin_board',1);
		$pin_alt=get_post_meta($post_id,'pin_alt',1);
		$images_index=get_post_meta($post_id,'pin_index',1);

		 
		
		$post_title=get_the_title($post_id);
		
		$wp_pinterest_user=get_option('wp_pinterest_user','');
		$wp_pinterest_pass=get_option('wp_pinterest_pass','');
			

		if( !(trim($wp_pinterest_user)== ''  | trim($wp_pinterest_pass) == ''  | trim($pin_board)== ''  | trim($pin_text) == '' )){
			
			$tocken=$pinterest->pinterest_login($wp_pinterest_user,$wp_pinterest_pass);
				
			if(trim($tocken) != ''){
				//valid login let's pin
					
				foreach($pin_images as $img){
					$sp= new Spintax;
					
					$pintext=$sp->spin($pin_text);
					
					if(trim($pintext == '')){
						$pintext= $pin_text ;
					}
					
					$i=0;
					foreach($images_index as $image){
						if($img == $images_index[$i]){
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
					
					$pinterest->pinterest_pin($tocken,$pin_board,$pintext,get_permalink( $post_id ),$img);
						
				}//foreach

				//clear queue
				delete_post_meta($post_id,'pin_images');

			}//trim(tocken)
		}//COMPLETE DATA
	
	*/
	
	}else{//is_array
	
	$publish='';
	@$publish=$_POST['post_status'];
	
	//check if instant publish 
	if (trim($publish) == 'publish' && ! isset($_POST['post_date'])){

			//echo 'instant publish ';
			$pin_options=$_POST['pin_options'];
				
			if(is_array($pin_options)){

				 
				$pimages=array();
				@$pimages=$_POST['pin_images'];
				if( in_array('OPT_PIN',$pin_options) & is_array($pimages) ){
					require_once(str_replace('pinterest-auto.php','core.php',__FILE__));
					$wp_pinterest_user=get_option('wp_pinterest_user','');
					$wp_pinterest_pass=get_option('wp_pinterest_pass','');
					$PIN_BOARD=$_POST['PIN_BOARD'];
					$pin_board=$_POST['PIN_BOARD'];
					$pin_text=$_POST['pin_text'];
					if( !(trim($wp_pinterest_user)== ''  | trim($wp_pinterest_pass) == ''  | trim($PIN_BOARD)== ''  | trim($pin_text) == '' )){

						
						$pin_text=$_POST['pin_text'];
						$pin_board=$_POST['PIN_BOARD'];
						$pin_images=$_POST['pin_images'];
						$pin_images=array_filter($pin_images);
						$post_title=  $_POST['post_title'] ;

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
								
									if( has_term($cat,$tax,$post_id) ){
										//get board matching this category 
										$pin_board=$wp_pinterest_automatic_pinterest_category[$n];
										break;
									}	
									
								$n++;		
								}	
							}
						
						}
						 
						
						//add other pins to the queue
						if( count($pin_images) > 1 ){
							$flag=0;
						
							//deleting first one
							foreach($pin_images as $pin_image){
								if($flag != 0 ){
									$schedulespinimages[]=$pin_image;
								}else{
									$flag =1;
								}
							}
						
							//saving them
						
							//save pin variables
							$pin_text=$_POST['pin_text'];
							
						
							$post_title=$_POST['post_title'];
							$pin_alt=$_POST['wp_pinterest_alts'];
							@$images_index=$_POST['wp_pinterest_index'];
								
								
							update_post_meta($post_id,'pin_images',$schedulespinimages);
							update_post_meta($post_id,'pin_text',$pin_text);
							update_post_meta($post_id,'pin_board',$pin_board);
							update_post_meta($post_id,'pin_alt',$pin_alt);
							update_post_meta($post_id,'pin_index',$images_index);
							update_post_meta($post_id,'pin_try',0);
								
							//building image trials array
							$firstitm = 0;
							foreach ( $pin_images as $pin_image ) {
								if ($firstitm != 0) {
									$images_try [md5 ( $pin_image )] = 0;
								} else {
									$firstitm = 1;
								}
							}
							
							update_post_meta ( $post_id, 'images_try', $images_try );
						}
						
						
						
						$pinterest=new pinterest;
						$tocken=$pinterest->pinterest_login($wp_pinterest_user,$wp_pinterest_pass);
						if(trim($tocken) != ''){
							
							
							
							//valid login let's pin
							$instantpinimages[]=$pin_images[0];
							foreach($instantpinimages as $img){
								$sp= new Spintax;
								$pintext=$sp->spin($pin_text);

								if(trim($pintext == '')){
									$pintext= $pin_text ;
								}

								$thepost=get_post($post_id);
								$user=get_userdata( $thepost->post_author  );
								$username=$user->display_name; 
								@$images_alt=$_POST['wp_pinterest_alts'];
								@$images_index=$_POST['wp_pinterest_index'];
						 
								
								$i=0;
								foreach($images_index as $image){
									if($img == $images_index[$i]){
										break;
									}
									$i++;
								}
								
						  
								
								$pintext=str_replace('[post_title]',$post_title,$pintext);
								$pintext=str_replace('[post_excerpt]',  $thepost->post_excerpt ,$pintext);
								$pintext=str_replace('[post_content]', $thepost->post_content ,$pintext);
								$pintext=str_replace('[post_author]', $username ,$pintext);
								$pintext=str_replace('[post_link]', $thepost->guid ,$pintext);
							 	@$pintext=str_replace('[image_alt]',  $images_alt[$i] ,$pintext);
								
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
							 	
							 	$pin=$pinterest->pinterest_pin($tocken,$pin_board, $pintext ,get_permalink( $post_id ),$img);
								
								if($pin === true){
									//successfull pin 
									
									$pins=get_post_meta($post_id,'pins',1);
									if(! is_array($pins)) $pins = array();
									$pins[]=$img;
									update_post_meta($post_id,'pins',$pins);
									
									
								}
								 
							}//foreach
						}//trim(tocken)
					}//complete data
				}// if opt_pin
			}// pin_options

		}elseif(trim($publish) == ''){//no instant publish + no scheduled images
			
			//check if bot mode enabled 
			$pin_options=get_option('wp_pinterest_options',array());
			
			if(in_array('OPT_BOT', $pin_options)){

			// now it will be either manually scheduled or posted with a bot 
			$manual = get_post_meta($post_id,'pin_manual',1);
			
			if(trim($manual) != ''){
				//now it is not manually scheduled i.e a bot post
				
				 
			}else{
				
				 	
					//get post variables like title and content 
					$post=get_post($post_id);
			 
					$wp_pinterest_types=get_option('wp_pinterest_types',array('post','page'));
					
					
					$post_type=$post->post_type;
					

					//process post from bots with type post only (no custom post types)
					if( in_array($post_type ,$wp_pinterest_types)){

						//now a bot post let's record it 
						update_post_meta($post_id, 'wp_pinterest_automatic_bot', 1);
						return;

						
					}//post only
					
				 
					 				
			}//bot post
			
		   }//bot mode ?
		}//no instant publish 
		
	}//not scheduled	
	
}//end function

function wp_pinterest1_save_meta_data( $post_id ) {


 

	if ( !wp_is_post_revision( $post_id ) ) {

/*
echo '<pre>';
print_r($_POST);
 omka();
*/
		$manual='';
		@$manual=$_POST['pin_manual'];
		 
		if(trim($manual) != '')
		update_post_meta($post_id,'pin_manual',$manual);

		
						

		//---------
		//return ;
		$publish='';
		@$publish=$_POST['post_status'];
			
		if( (trim($publish) == 'publish' &&  isset($_POST['post_date']) ) || $publish == 'draft'  || $publish == 'future'  ){


			$pin_options=$_POST['pin_options'];

			if(is_array($pin_options)){
					
	 		if( in_array('OPT_PIN',$pin_options) & is_array($_POST['pin_images']) ){
	 				
	 			//save pin variables
		 		$pin_text=$_POST['pin_text'];
		 		$pin_board=$_POST['PIN_BOARD'];
		 		$pin_images=$_POST['pin_images'];
		 		$post_title=$_POST['post_title'];
	 			$pin_alt=$_POST['wp_pinterest_alts'];
	 			@$images_index=$_POST['wp_pinterest_index'];
	 			
	 			require_once(str_replace('pinterest-auto.php','core.php',__FILE__));
	 			$pinterest=new pinterest();
	 			$pinterest->log('Scheduling','Scheduling '.count($pin_images).' pins for post with id {'.$post_id.'}');
		 		 
		 		update_post_meta($post_id,'pin_images',$pin_images);
		 		update_post_meta($post_id,'pin_text',$pin_text);
		 		update_post_meta($post_id,'pin_board',$pin_board);
		 		update_post_meta($post_id,'pin_alt',$pin_alt);
		 		update_post_meta($post_id,'pin_index',$images_index);
		 		update_post_meta($post_id,'pin_try',0);
		 		
		 		//building image trials array 
			 	foreach($pin_images as $pin_image){
			 		$images_try [md5($pin_image)] = 0 ; 
			 	}	
			 	
			 	update_post_meta($post_id,'images_try',$images_try);
		 		
		 		return;
		 		 

		 		}//foreach
		 		 
	 		}//if pin_opt
			}//is_array

			//echo 'schedule';
		}else{

			//echo 'ignonre';
		}
			
			


			
			
			
		//omak();

	 
}// end function


/**
 * custom request for fetch boards
 */
function wp_pinterest_automatic_parse_request($wp) {

	// only process requests with "my-plugin=ajax-handler"
	if (array_key_exists('wp_pinterest_automatic', $wp->query_vars)) {
		 
		if($wp->query_vars['wp_pinterest_automatic'] == 'boards'){

			require_once('core.php');
			exit;

		}elseif($wp->query_vars['wp_pinterest_automatic'] == 'settings'){

			require_once('process_form.php');
			exit;

		}elseif($wp->query_vars['wp_pinterest_automatic'] == 'cron'){
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

//support widget 
require_once('widget.php');

//rating 
require_once('rating.php');

//update 
require_once('updated.php');


/* ------------------------------------------------------------------------*
 * Add Table when First activation
 * ------------------------------------------------------------------------*/
register_activation_hook( __FILE__, 'create_table_wp_automatic_pinterest' );
/* ------------------------------------------------------------------------*
 *Create a new table Comments
 * ------------------------------------------------------------------------*/
function create_table_wp_automatic_pinterest()
{
	global $wpdb;
	//comments table
	if(!exists_table_wp_automatic_pinterest('wp_pinterest_automatic')){
		$querys="SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";
			CREATE TABLE IF NOT EXISTS `wp_pinterest_automatic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL,
  `data` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `camp` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=483 ;
			
			";
		//executing quiries
		$que=explode(';',$querys);
		foreach($que  as $query){
			if(trim($query)!=''){
				$wpdb->query($query);
			}
		}
	}
}

function exists_table_wp_automatic_pinterest($table){
	global $wpdb;
	$rows = $wpdb->get_row('show tables like "'.$table.'"', ARRAY_N);
	return (count($rows)>0);
}

//Log 
require_once 'plog.php';

//license
require_once 'license.php';
