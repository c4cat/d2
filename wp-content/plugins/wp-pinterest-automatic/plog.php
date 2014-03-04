<?php

function wppinterestautomatic(){

	//print_r($_POST);
	global $wpdb;
	$csv = plugins_url().'/wp-pinterest-automatic/inc/csv.php';


	$filter='';
		
	/* campaign filter only for camps
	 if(isset($_POST['id']) && $_POST['id'] != 'all'){
	 $id=$_POST['id'];
	 $filter = " where action like '%$id' ";
	 } */
		
	// action type posted , Approved
		
	if( isset( $_POST['action_type']) ){
		$act=$_POST['action_type'];
		if ($act == 'Error' ){
			$action=" action like '%Error%' ";
		}elseif($act == 'approved'){
			$action = " action like 'Comment approved%'";
		}
	}else{
		$action='';
			
	}

	if ($action != ''){
		if($filter == ''){
			$filter=" where $action";
		}else{
			$filter.=" and $action";
		}
	}

	// records number
	if(isset($_POST['number'])){
		$num=$_POST['number'];
	}else{
		$num='999';
	}
		
	// define limit
	$limit='';
	if (is_numeric($num)) $limit=" limit $num ";
		
	$qdate='';
	// finally date filters `date` >= str_to_date( '07/03/11', '%m/%d/%y' )
	if(isset($_POST['from']) && $_POST['from'] !='' ){
		$from=$_POST['from'];
		$qdate=" `date` >= str_to_date( '$from', '%m/%d/%y' )";
	}
		
	if(isset($_POST['to']) && $_POST['to'] !=''){
		$to=$_POST['to'];
		if($qdate == ''){
			$qdate.=" `date` <= str_to_date( '$to', '%m/%d/%y' )";
		}else{
			$qdate.=" and `date` <= str_to_date( '$to', '%m/%d/%y' )";
		}
	}
		
	if($qdate != ''){
		if($filter == ''){
			$filter=" where $qdate";
		}else{
			$filter.="and $qdate";
		}
	}
	//echo $filter;
	$query="SELECT * FROM wp_pinterest_automatic $filter ORDER BY id DESC $limit";
	//echo $query;
	$res=$wpdb->get_results( $query);

	?>


<style>
.ttw-date {
	width: 81px;
}
</style>
<div class="wrap">
	<div class="icon32" id="icon-edit-comments">
		<br>
	</div>
	<h2>Pinterest automatic action log</h2>
	<form method="post" action="">
		<div class="tablenav top">

		<?php /*

		<div class="alignleft actions">
		<select name="id">
		<option value="all">All Campaigns </option>
		<?php
		foreach ($rows as $row){
		if(get_post_status( $row->camp_id ) == 'publish'){
		echo '<option ';
		opt_selected($id,$row->camp_id);
		echo ' value="'.$row->camp_id.'">'.get_the_title($row->camp_id).'</option>';
		}
		}
			
		?>
		</select>
		</div>

		*/ ?>

			<div class="alignleft actions">
				<select name="number">
					<option <?php wp_pinterest_automatic_opt_selected($num,'50') ?> value="999">Records Number</option>
					<option <?php wp_pinterest_automatic_opt_selected($num,'100') ?> value="100">100</option>
					<option <?php wp_pinterest_automatic_opt_selected($num,'500') ?> value="500">500</option>
					<option <?php wp_pinterest_automatic_opt_selected($num,'1000') ?> value="1000">1000</option>
					<option <?php wp_pinterest_automatic_opt_selected($num,'all') ?> value="all">All</option>
				</select> <select name="action_type">
					<option <?php @wp_pinterest_automatic_opt_selected($act,'') ?> value="">Show all actions</option>
					<option <?php @wp_pinterest_automatic_opt_selected($act,'Error') ?> value="Error">Error</option>

				</select>
			</div>
			<div class="clear"></div>
			<div class="alignleft actions" style="margin: 11px 0 11px 0">

				<label for="field1"> From Date: <small><i>(optional)</i> </small>
				</label> <input class="ttw-date date" name="from" id="field1" type="date" autocomplete="off"> <label for="field2"> To Date: </label> <input class="ttw-date date" name="to" id="field2" type="date" autocomplete="off"> <input type="submit" value="Filter" class="button-secondary" id="post-query-submit" name="submit">
				<button id="clear_log" style="float:right" class="button">Clear Log</button>
			</div>




		</div>

		<table class="widefat fixed">
			<thead>
				<tr>
					<th class="column-date">Index</th>
					<th class="column-response">Date</th>
					<th class="column-response">Type of action</th>
					<th>Data Processed</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>index</th>
					<th>Date</th>
					<th>Type of action</th>
					<th>Data Processed</th>
				</tr>
			</tfoot>
			<tbody>

			<?php
			$i=1;
			foreach ($res as $rec){
				$action=$rec->action;
				//filter the data strip keyword
				$datas=explode(';',$rec->data);
				$data=$datas[0];


				if (stristr($action , 'Posted:')){
					$url = plugins_url().'/wp-pinterest-automatic';
					$action = 'New Post';
					//restoring link

				}elseif(stristr($action , 'Processing')){
					$action = 'Processing Campaign';
				}
				
				if(stristr($data,'html')){
					 $data='<textarea>'.htmlspecialchars( ($data) ).'</textarea>';
				}else{
					//$data=htmlspecialchars( ($data) );
				}
				

				echo'<tr class="'.$rec->action.'"><td class="column-date">'.$i.'</td><td  class="column-response" style="padding:5px">'.urldecode($rec->date).'</td><td  class="column-response" style="padding:5px">'. $action.'</td><td  style="padding:5px">' .urldecode($data).' </td></tr>';
				$i++;
			}


			?>
			</tbody>
		</table>

</div>



<?php
}