<?php 
$dir = WP_PLUGIN_URL.'/wp-pinterest-automatic/'; 

$pin_options=get_option('wp_pinterest_options',array());
$pin_options=implode('|',$pin_options);


$pin_text=get_option('wp_pinterest_default','{awesome|nice|cool} [post_title]');
$wp_pinterest_boards=get_option('wp_pinterest_boards',array('ids'=>array() ,  'titles'=> array() ));
$wp_pinterest_boards_ids=$wp_pinterest_boards['ids'];
$wp_pinterest_boards_titles=$wp_pinterest_boards['titles'];
$wp_pinterest_board=get_option('wp_pinterest_board','');


?>



<div class="wp-pinterest-automatic-container">
      <div class="wp-pinterest-automatic" style="width:250px !important">
      
      <?php
		 
                     
       ?>
      
      <div id="field-pin_options-container" class="field f_100" >
           <div class="option clearfix">
               <input     name="pin_options[]" id="field-pin_options-1" value="OPT_PIN" type="checkbox">     
                <span class="option-title">
       			 Pin this post ?
                </span>
           </div>
           
           <input type="hidden" value="" name="wp_pinterest_alts[]">
           <input type="hidden" value="" name="wp_pinterest_index[]">
           
      </div> 
      
      <div class="clear"></div>
      
      
      <div id="pin-contain">
      
      <div id="pin-images"> <!-- pin images here --> 
      
      <?php // images in queue 
       global $post;
        
       @$pin_images=get_post_meta($post->ID,'pin_images',1);
       	$pins=get_post_meta($post->ID,'pins',1);
       	if(! is_array($pins)) $pins=array();
	     
        //print_r($pin_images);
         
         
      	if(is_array($pin_images)){
      		$pin_text=get_post_meta($post->ID,'pin_text',1);
      		$wp_pinterest_board=get_post_meta($post->ID,'pin_board',1);
      		
      		
      		foreach ($pin_images as $pin_img){
      			//@$pin_img=$pin_img[0];
      			if(trim($pin_img) != ''){
      				?>
      			
      			  <div class="pin_img_contain scheduled">
			      	<input   type="checkbox"   name="pin_images[]"value="<?php echo $pin_img ?>" checked="checked"  class="pin_check">
			      	
			      	<img src="<?php echo trim($pin_img) ?>" class="pin_img" />      	
			      </div>
			      				
      			<?php 
      			}
      		}
      	}
      
      ?>
      
      </div>
      
      <div class="clear"></div>

      <div   class="field f_100" style="padding-bottom:0" >
           <div class="option clearfix">
               <input  id="wp_pinterest_automatic_all"   name="pin_options[]"    type="checkbox">     
                <span class="option-title">
       			 select/deselect all 
                </span>
           </div>
      </div>      
      
      <div id="field-pin_options-container" class="field f_100" >
           <div class="option clearfix">
               <input     name="pin_options[]" id="field-pin_options-2" value="OPT_PIN_VAR" type="checkbox">     
                <span class="option-title">
       			 Modify Pin Variables
                </span>
           </div>
      </div>
      
      <div id="pin_vars"><!-- pin vars -->
      <div id="field-pin_text-container" class="field f_100">
      	<label for="field-pin_text">
      	    Pin Text 
      	</label>
      	<input  class="widefat"  value="<?php echo $pin_text  ?>" name="pin_text" id="field-pin_text" required="required" type="text">
      	<input value="automatic" name="pin_manual"  type="hidden">
      </div>
      
      <br class="clear">
      
      <div id="field-PIN_BOARD-container" class="field f_100" >
      	<label for="field-PIN_BOARD">
      		Pin Board 
      	</label>

		<select class="widefat" name="PIN_BOARD"  >
      		
      		<?php
      		$i=0;

      		foreach($wp_pinterest_boards_ids as $id){ ?>
      		
			<option  value="<?php echo $id ?>"  <?php wp_pinterest_automatic_opt_selected( $id ,$wp_pinterest_board) ?> ><?php echo $wp_pinterest_boards_titles[$i]?></option>
      			
      		<?php
      			$i++; 	
      		}
      		
      		?> 
      	</select>
      </div>
      
      </div><!-- / pin vars -->
    
      </div><!-- pin contain -->
      
    
      
      <div class="clear"></div>
      </div><!--/TTWForm-->
      
      <!-- image template -->
      <div id="img_template" style="visibility:hidden;width:0px;height:0px;overflow:hidden	">
      <div class="pin_img_contain">
      	<input     name="pin_images[]"value="1" type="checkbox" class="pin_check">
      	<img src="http://www.veryicon.com/icon/png/Object/Magnets/Alarm%20clock.png" class="pin_img" />      	
      </div>
      </div>
      <!-- /image template -->
      
  
</div><!--/TTWForm-contain-->
<?php 
	@$pin_images =implode('|',$pin_images);
	@$pins = implode('|',$pins); //successfully pinned
?>
<script type="text/javascript">
	var postID = <?php echo $post->ID ?>;
	var wp_pinterest_automatic_selector = '<?php 
	
		$selector= get_option('wp_pinterest_automatic_selector','');

		if(trim($selector) != ''){
			echo ','.$selector;
		}
	
	?>';

	var wp_pinterest_automatic_cf = '<?php $key= get_option('wp_pinterest_automatic_cf','');echo $key ; if(trim($key) == '') echo 'dummyone'  ?>';
	
	var vals = '<?php echo  $pin_images ?>';
    val_arr = vals.split('|');

     jQuery('.TTWForm-container input:checkbox').removeAttr('checked');

     jQuery.each(val_arr, function (index, value) {
        if (value != '') {
            
            jQuery('input:checkbox[value="' + value + '"]').attr('checked', 'checked');
        }
    });

	var pinned='<?php echo  $pins ?>';
	var_pinned=pinned.split('|');
     
</script> 

<script type="text/javascript">
    var vals = '<?php echo  $pin_options ?>';
    val_arr = vals.split('|');
    var wp_pinterest_opts=val_arr;
    
    //jQuery('input:checkbox').removeAttr('checked');
    jQuery.each(val_arr, function (index, value) {
        if (value != '') {
            
            jQuery('input:checkbox[value="' + value + '"]').attr('checked', 'checked');
        }
    });
</script>