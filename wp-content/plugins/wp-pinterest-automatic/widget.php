<?php

if(! function_exists('deandev_add_dashboard_widgets')){

	add_action( 'wp_dashboard_setup', 'deandev_add_dashboard_widgets' );
	
	
	function deandev_add_dashboard_widgets() {

			add_meta_box('deandev_dashboard_widget', 'DeanDev Support & News', 'deandev_dashboard_widget_function', 'dashboard', 'side', 'high');
		
	}
	
		
	/**
	 * custom request for subscribtion
	 */
	function wp_deandev_parse_request($wp) {
	
		// only process requests with "my-plugin=ajax-handler"
		if (array_key_exists('wp_deandev', $wp->query_vars)) {
				if($wp->query_vars['wp_deandev'] == 'subscribe'){
					
					// data fetched
					$mail=$_POST['mail'];
					$original_mail=$_POST['original_mail'];
					$original_uri=$_POST['original_uri'];
					$original_name=$_POST['original_name'];
					
					//posting data to deandev
					
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
					
					//curl post
					$curlurl="http://deandev.com/me/?wp_deandev_ext=subscribe";
					$curlpost="mail=$mail&original_mail=$original_mail&original_uri=$original_uri&original_name=$original_name";
					curl_setopt($ch, CURLOPT_URL, $curlurl);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $curlpost); 
					$x='error';
					
					while (trim($x) != ''  ){
						$exec=curl_exec($ch);
						$x=curl_error($ch);
					}
					
					if(stristr($exec,'font-family')){
						//subscribtion called 
						update_option('wp_deandev_subscribed',1);
					}
					
					$res['res']=$exec;
					
					echo json_encode($res);
					exit;
					
					
					
				}
		}
	}
	
	add_action('parse_request', 'wp_deandev_parse_request');
	
	function wp_deandev_query_vars($vars) {
		$vars[] = 'wp_deandev';
		return $vars;
	}
	
	add_filter('query_vars', 'wp_deandev_query_vars');
	
	
	
	/**
	 * Create the function to output the contents of our Dashboard Widget.
	 */
	function deandev_dashboard_widget_function() {
	
		?>
		
		<table>

<tbody><tr>
<td><img src="http://deandev.com/wordpresspinteresttowordpress/img/help.png"></td>
<td>

<p>The plugin include free support . Got a problem ? don’t worry we have a help desk setup just for your help . submit a <a href="http://deandev.com/me/support">support ticket</a> and we always happy to help make you happy choosing our plugin </p><p>
<a href="http://deandev.com/me/support" class="button"> Open Support Ticket Now  </a>
 </p>
 
</td>
</tr>

<tr>
<td>
</td><td><p>
</p><div class="more-work">
        <div>
        
        <p>our beloved collection</p>

         <a href="http://codecanyon.net/item/wordpress-auto-spinner-articles-rewriter/4092452?ref=DeanDev"><img width="80" height="80" border="0" data-tooltip="Wordpress Auto Spinner - Articles Rewriter" alt="Wordpress Auto Spinner - Articles Rewriter - CodeCanyon Item for Sale" class="landscape-image-magnifier preload no_preview" data-item-author="DeanDev" data-item-category="WordPress / SEO" data-item-cost="16" data-item-name="Wordpress Auto Spinner - Articles Rewriter" data-preview-height="" data-preview-url="http://3.s3.envato.com/files/48892304/preview.jpg" data-preview-width="" src="http://1.s3.envato.com/files/48892303/icon.jpg" title=""></a>
        
  <a href="http://codecanyon.net/item/wordpress-automatic-plugin/1904470?ref=DeanDev"><img width="80" height="80" border="0" alt="Wordpress Automatic Plugin - CodeCanyon Item for Sale" class="landscape-image-magnifier preload no_preview" data-item-author="DeanDev" data-item-category="WordPress" data-item-cost="15" data-item-name="Wordpress Automatic Plugin" data-preview-height="" data-preview-url="http://1.s3.envato.com/files/26595898/preview.png" data-preview-width="" src="http://3.s3.envato.com/files/26595897/icon.png" title="Wordpress Automatic Plugin"></a>

  <a href="http://codecanyon.net/item/pinterest-automatic-pin-wordpress-plugin/2203314?ref=DeanDev"><img width="80" height="80" border="0" data-tooltip="Pinterest Automatic Pin Wordpress Plugin" alt="Pinterest Automatic Pin Wordpress Plugin - CodeCanyon Item for Sale" class="landscape-image-magnifier preload no_preview" data-item-author="DeanDev" data-item-category="WordPress / Social Networking" data-item-cost="5" data-item-name="Pinterest Automatic Pin Wordpress Plugin" data-preview-height="" data-preview-url="http://2.s3.envato.com/files/25603959/preview.jpg" data-preview-width="" src="http://2.s3.envato.com/files/25603958/icon.jpg" title=""></a>



  <a href="http://codecanyon.net/item/wordpress-rocket-writer/2017395?ref=DeanDev"><img width="80" height="80" border="0" data-tooltip="Wordpress Rocket Writer" alt="Wordpress Rocket Writer - CodeCanyon Item for Sale" class="landscape-image-magnifier preload no_preview" data-item-author="DeanDev" data-item-category="WordPress" data-item-cost="15" data-item-name="Wordpress Rocket Writer" data-preview-height="" data-preview-url="http://2.s3.envato.com/files/25551734/preview-rocket.jpg" data-preview-width="" src="http://3.s3.envato.com/files/25551732/icon-rocket.jpg" title=""></a>

    <a href="http://codecanyon.net/item/wordpress-keyword-tool-plugin/2840111?ref=DeanDev"><img width="80" height="80" border="0" data-tooltip="Wordpress Keyword Tool Plugin" alt="Wordpress Keyword Tool Plugin - CodeCanyon Item for Sale" class="landscape-image-magnifier preload no_preview" data-item-author="DeanDev" data-item-category="WordPress / SEO" data-item-cost="15" data-item-name="Wordpress Keyword Tool Plugin" data-preview-height="" data-preview-url="http://3.s3.envato.com/files/57630012/preview.jpg" data-preview-width="" src="http://3.s3.envato.com/files/57630010/icon.jpg" title=""></a>
 
  <a href="http://codecanyon.net/item/pinterest-to-wordpress-plugin-/5304915?ref=DeanDev"><img width="80" height="80" border="0" title="" src="http://1.s3.envato.com/files/63163503/icon.jpg" data-preview-width="" data-preview-url="http://3.s3.envato.com/files/63163504/preview.jpg" data-preview-height="" data-item-name="Pinterest to wordpress plugin " data-item-cost="14" data-item-category="WordPress / Galleries" data-item-author="DeanDev" class="landscape-image-magnifier preload no_preview" alt="Pinterest to wordpress plugin  - CodeCanyon Item for Sale" data-tooltip="Pinterest to wordpress plugin "></a>
          
  <a href="http://codecanyon.net/item/wordpress-responsive-3d-gallery-plugin/5053806?ref=DeanDev"><img width="80" height="80" border="0" data-tooltip="Wordpress Responsive 3D Gallery Plugin" alt="Wordpress Responsive 3D Gallery Plugin - CodeCanyon Item for Sale" class="landscape-image-magnifier preload no_preview" data-item-author="DeanDev" data-item-category="WordPress / Galleries" data-item-cost="15" data-item-name="Wordpress Responsive 3D Gallery Plugin" data-preview-height="" data-preview-url="http://2.s3.envato.com/files/60204211/preview.jpg" data-preview-width="" src="http://0.s3.envato.com/files/60204209/icon.jpg" title=""></a>
          
  <a href="http://codecanyon.net/item/funny-facebook-popup-facebook-dislike-button/4812948?ref=DeanDev"><img width="80" height="80" border="0" data-tooltip="Funny Facebook Pop-up - Facebook Dislike Button" alt="Funny Facebook Pop-up - Facebook Dislike Button - CodeCanyon Item for Sale" class="landscape-image-magnifier preload no_preview" data-item-author="DeanDev" data-item-category="WordPress / Social Networking" data-item-cost="4" data-item-name="Funny Facebook Pop-up - Facebook Dislike Button" data-preview-height="" data-preview-url="http://1.s3.envato.com/files/57624083/preview.jpg" data-preview-width="" src="http://3.s3.envato.com/files/57624082/icon.jpg" title=""></a>

        </div>
        <div class="clear"><!-- --></div>
        <small><a href="http://codecanyon.net/user/DeanDev/portfolio?ref=DeanDev">More items by DeanDev</a></small>
        
        <p><input type="text" id="admin_email" name="admin_email" value="" class="regular-text ltr">- your email to get plugin updates 
        	<input type="hidden" id="original_admin_email" name="original_admin_email" value="<?php echo get_option('admin_email')?>">
        	<input type="hidden" id="original_uri" name="original_uri" value="<?php echo site_url('/') ?>">
        	<input type="hidden" id="wp_deandev_subscribed" name="wp_deandev_subscribed" value="<?php echo get_option('wp_deandev_subscribed',0) ?>">
        	
        	<?php global $current_user;
			      
        		  get_currentuserinfo();
					
			      $fname=$current_user->user_firstname;
			      $lname=$current_user->user_lastname;
			      
			      $name=$fname. ' '.$lname;    
			?>
			
			<input type="hidden" id="original_name" name="original_name" value="<?php echo $name ?>">
			
        </p>
        <p style="float: left;padding-left:0px" id="deandev_submit"><a  href="<?php echo site_url('/?wp_deandev=subscribe')  ?>" type="submit"   class="button button-primary"  id="deandev_admin_email" >Update email </a><img alt="" id="ajax-loadingimg" class="ajax-loading" src="images/wpspin_light.gif" style="float:right;margin:3px" ></p>
        
      </div>
<p></p></td>
</tr>

</tbody></table>
		
		<script type="text/javascript">
		jQuery(document).ready(function () {

			//ajax submit for subscribtion
			
		    jQuery('#deandev_admin_email').click(function () {
		        jQuery.ajax({
		            url: jQuery(this).attr('href'),
		            type: 'POST',

		            data: {
		                action: "subscribe",
		                mail: jQuery('#admin_email').val(),
		                original_mail: jQuery('#original_admin_email').val(),
		                original_uri: jQuery('#original_uri').val(),
		                original_name: jQuery('#original_name').val()

		            },

		            success: function (data) {
		                jQuery('#ajax-loadingimg').addClass('ajax-loading');
		                

		                if (data.substr(0, 1) == '{') {
		                    var res = jQuery.parseJSON(data);
		                    jQuery('#deandev_submit').prepend('<div class="updated">Successfully updated</dev>');
		                } else {
		                	jQuery('#deandev_submit').prepend('<div class="error">Problem occured</dev>');
		                }

		            },

		            beforeSend: function () {

		                jQuery('#ajax-loadingimg').removeClass('ajax-loading');

		            } // before send


		        }); //.ajax
		        return false;
		    }); //.click

			//trigger click if no subscribtion yet
		    if(jQuery('#wp_deandev_subscribed').val() == 0){
		        jQuery('#deandev_admin_email').trigger('click');
		    }
		    
		}); //dockready
		</script>
		
		<?php 
	}//function of the widget 

}//function exists
