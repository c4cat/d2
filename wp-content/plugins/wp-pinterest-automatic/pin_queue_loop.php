<?php
$i = 1;
if ($the_query->have_posts ()) {
	
	while ( $the_query->have_posts () ) {
		
		$the_query->the_post ();
		$post_id = $post->ID;
		
		if (! in_array ( $post->ID, $posts_displayed )) {
			$posts_displayed[]=$post->ID;
			
			//display separator between published posts and other formats
			if(isset($view_separator) && $i == 1 ){
				echo '<tr style="background:#a3a3a3;color:#fff"><td  style="padding-left:150px;color:#fff" colspan="7" ><strong>Below posts are in the queue but will not be processed until published .  </td></tr>';
			}
			
			$pin_text = get_post_meta ( $post_id, 'pin_text', 1 );
			$pin_board = get_post_meta ( $post_id, 'pin_board', 1 );
			$pin_alt = get_post_meta ( $post_id, 'pin_alt', 1 );
			$images_index = get_post_meta ( $post_id, 'pin_index', 1 );
			$pin_images = get_post_meta ( $post_id, 'pin_images', 1 );
			$images_try =get_post_meta ( $post_id, 'images_try', 1 );
			$pin_try=get_post_meta ( $post_id, 'pin_try', 1 );
			if(trim($pin_try) == '') $pin_try =0;
			
		 
			
			foreach ( $pin_images as $pin_image ) {
				
				//get the image with lowest try 
				$image_try=$images_try[md5($pin_image)];
				
				if ($i % 2 == 1) {
					$cls = 'alternate';
				} else {
					$cls = '';
				}
				echo '<tr class="' . $cls . '">';
				echo ' <td>' . $i . '</td>';
				
				echo ' <td> <div class="pin_img_log" style="background-image:url(\'' . $pin_image . '\');"  ></div>';
				$ttl = $post->post_title;
				if (trim ( $ttl ) == '')
					$ttl = '(no title)';
				echo ' <td><a href="' . admin_url('post.php?post='.$post_id.'&action=edit')  . '">' . $ttl . '</a></td>';
				echo "<td>$pin_text</td>";
				echo "<td>$pin_board</td>";
				echo "<td>$post->post_status</td>";
				echo "<td>($pin_try) >> ($image_try)</td>";
				
				echo '</tr>';
				
				$i ++;
			}
		}//not displayed
	}
	
	
	
	
	
} else {
	// no posts found
	if(!  isset($view_separator) ){
		echo '<tr><td colspan="7"  ><strong>no posts waiting for pinning . </td></tr>';
	}
}

/* Restore original Post Data */
wp_reset_postdata ();

?>