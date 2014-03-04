jQuery(document).ready(function(){
	jQuery('#clear_log').click(function(){
		jQuery.ajax({
            url: ajaxurl,
            type: 'POST',

            data: {
                action: 'pinterest_automatic_clear',
                
            }	        
        });
		jQuery('tr').remove();
		jQuery('tbody').append('<p>Log Cleared .. </p>');
		return false;
		
	});
});