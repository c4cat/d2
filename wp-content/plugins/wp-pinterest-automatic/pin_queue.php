<div class="wrap">
	<div id="icon-edit-comments" class="icon32">
		<br>
	</div>
	<h2>Pinterest automatic pinning queue</h2>
	<br>
	<?php $lastrun=get_option('wp_pinterest_last_run',1392146043); ?>
	<p>Current server time is  <strong><?php echo date( 'h:i:s') ?></strong> , Cron last run at <strong><?php echo date("h:i:s",$lastrun ) ?></strong> this is <strong><?php echo $timdiff = time('now') - $lastrun ?></strong> seconds ago and it runs every <strong>1000</strong> second to process one item from the queue so it should run again after <strong><?php echo( 1000 - $timdiff )  ?></strong> seconds.  
	
	<?php 
	
	//check we are deactivated
	$timenow=time();
	$deactive = get_option('wp_pinterest_automatic_deactivate', 5 );
	
	if($timenow < $deactive ) {
		echo '<p>Cron is deactivated for one hour and still active now as pinterest is flagging the account for spam . cron will be acivated after '. ($deactive - $timenow  ) / 60 .' minutes</p>';
	}
	
	
	?>
	
	<form action="" method="post">
		<table class="widefat fixed">
			<thead>
				<tr>
					<th class="column-date">I</th>
					<th class="column-response">Image</th>
					<th class="column-response">Post</th>
					<th class="column-response">Pin Text</th>
					<th class="column-response">Pin Board</th>
					<th class="column-response">Post Status</th>
					<th class="column-response">Pin trials</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="column-date">I</th>
					<th class="column-response">Image</th>
					<th class="column-response">Post</th>
					<th class="column-response">Pin Text</th>
					<th class="column-response">Pin Board</th>
					<th class="column-response">Post Status</th>
					<th class="column-response">Pin trials</th>

				</tr>
			</tfoot>
			<tbody>
			
			<?php
			// 'post_status'=>'publish',
			global $post;
			$posts_displayed = array ();
			
			$the_query = new WP_Query ( array (
					
					'posts_per_page' => 100,
					'post_status' => 'publish',
					'meta_query' => array (
							
							array (
									
									'key' => 'pin_images',
									'compare' => 'EXISTS' ,
										
 
							) 
					),
					
					'orderby' => 'meta_value_num',
					'meta_key' => 'pin_try',
					'order' => 'ASC',
					'post_type' => 'product' ,
					'ignore_sticky_posts' => true	 
			) );
			
			// The Loop
			require ('pin_queue_loop.php');
			
			// other than published
			$view_separator = 1;
			
			$the_query = new WP_Query ( array (
					
					'posts_per_page' => 100,
					
					'meta_query' => array (
							
							array (
									
									'key' => 'pin_images',
									'compare' => 'EXISTS' ,
									
							) 
					) ,
					'post_type' => 'product' ,
					'ignore_sticky_posts' => true
			) );
			
			// The Loop
			require ('pin_queue_loop.php');
			
			?>
			
			
				 
			</tbody>
		</table>

		<p>Below posts are posts that found to be posted automatically once they published we will check if we can pin them and add them to the pinning queue</p>

		<table class="widefat fixed">
			<thead>
				<tr>
					<th class="column-date">I</th>
					<th class="column-response">Post</th>
					<th class="column-response">Post Status</th>

				</tr>
			</thead>
			<tbody>
		
		
		<?php
		
		// display posts having the wp_pinterest_automatic_bot custom field
		$the_query = new WP_Query ( array (
				
				'posts_per_page' => 100,
				'meta_query' => array (
						
						array (
								
								'key' => 'wp_pinterest_automatic_bot',
								'compare' => 'EXISTS' 
						)
				) ,
				'post_type' => 'product' ,
				'ignore_sticky_posts' => true
		) );
		
		// loop
		$i = 1;
		if ($the_query->have_posts ()) {
			
			while ( $the_query->have_posts () ) {
				
				$the_query->the_post ();
				$post_id = $post->ID;
				
				echo '<tr>';
				
				echo '<td>' . $i . '</td>';
				
				$ttl = $post->post_title;
				if (trim ( $ttl ) == '')
					$ttl = '(no title)';
				echo ' <td><a href="' . admin_url ( 'post.php?post=' . $post_id . '&action=edit' ) . '">' . $ttl . '</a></td>';
				echo "<td>$post->post_status</td>";
				echo '</tr>';
				$i ++;
			}
		}else{
			echo '<td colspan="3" >No bots posts waiting</td>';
		}
		
		
		?>
	</tbody>
		</table>
	</form>
</div>