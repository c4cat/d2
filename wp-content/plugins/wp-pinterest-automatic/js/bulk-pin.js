jQuery(document).ready(function(){
	//ADD OPTION
	jQuery('select[name="action"]').append('<option value="wp_automatic_pin">Pin them</option>');
	
	
	//CLICH PIN
	jQuery('#doaction,#doaction2').click(function(){
	    if(jQuery('select[name="action"]').val() == 'wp_automatic_pin' ){
	        
	    	var itms='';
	    	var itms_count=0;

	    	jQuery('input[name="post[]"]:checked').each(
	    	    function(index,itm){
	    	        console.log(jQuery(itm).val());
	    	        itms=itms + ',' + jQuery(itm).val();
	    	        itms_count++;    
	    	    }
	    	    
	    	    
	    	);

	        jQuery.ajax({
	            url: ajaxurl,
	            type: 'POST',

	            data: {
	                action: 'pinterest_automatic_pin',
	                itms: itms
	            }	        
	        });

	    		
	    	alert(itms_count + ' items sent to the pin queue' );
	    	jQuery('input[name="post[]"]:checked').removeAttr('checked');

	    		
	    	
	    	 jQuery('select[name="action"]').val('-1');
	        return false;  
	    }
	});
 
});