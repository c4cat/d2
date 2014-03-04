//timer for collecting imgs

var postID;
var wp_pinterest_automatic_selector;
var wp_pinterest_automatic_cf = 'dummyonehere';
function timedCount() {


    jQuery('#content_ifr'+ wp_pinterest_automatic_selector).contents().find('img').each(
        function () {
            var original = this;
            if (jQuery('#pin-images img[src="' + jQuery(this).attr('src') + '"]').attr('src') != jQuery(original).attr('src')) {
                jQuery('#img_template img').attr('src', jQuery(this).attr('src'));
                jQuery('#img_template input:checkbox').val(jQuery(this).attr('src'));

                jQuery('#pin-images').append(jQuery('#img_template').html());



                jQuery('#field-pin_options-container').append('<input name="wp_pinterest_alts[]" type="hidden" value="' + jQuery(this).attr('alt') + '"/>');
                jQuery('#field-pin_options-container').append('<input name="wp_pinterest_index[]" type="hidden" value="' + jQuery(this).attr('src') + '"/>');

                jQuery.each(var_pinned, function (index, value) {
                    if (value != '') {
                        jQuery('#pin-images input:checkbox[value="' + value + '"]').parent().addClass('pinned');
                    }
                });
            }
        });

    jQuery('#set-post-thumbnail img').each(
        function () {

            var src = jQuery(this).attr('src');

            srcs = src.split('-');

            console.log(srcs);

            var lastIndex = (srcs.length - 1);

            var lastItem = srcs[lastIndex];

            console.log(lastItem);

            var lastItemParts = lastItem.split('.');
            //console.log(lastItemParts);
            var extention = lastItemParts[lastItemParts.length - 1];
            //console.log(extention);

            var i = 0;

            imgsrc = '';
            jQuery.each(srcs, function (index, value) {
                if (index == srcs.length - 1) {
                    if (lastItem.search("x") != '-1') {
                        imgsrc = imgsrc + '.' + extention;
                    } else {
                        imgsrc = imgsrc + '-' + lastItem;
                    }
                } else if (index != 0) {
                    imgsrc = imgsrc + '-' + value;
                } else {
                    imgsrc = value;
                }



            });


            if (jQuery('#pin-images img[src="' + imgsrc + '"]').attr('src') != imgsrc) {
                jQuery('#img_template img').attr('src', imgsrc);
                jQuery('#img_template input:checkbox').val(imgsrc);
                jQuery('#pin-images').append(jQuery('#img_template').html());



                jQuery.each(var_pinned, function (index, value) {
                    if (value != '') {
                        jQuery('#pin-images input:checkbox[value="' + value + '"]').attr('checked', '').parent().addClass('pinned');
                    }
                });


            }
        });
    
    //custom filed image

    if( jQuery('input[value="'+ wp_pinterest_automatic_cf +'"]').length > 0 ){
        var fkey = (jQuery('input[value="'+ wp_pinterest_automatic_cf +'"]').attr('name').replace('key','value'));
        console.log(fkey);
        fkey=fkey.replace('meta[','');
        fkey=fkey.replace('][value]','');
        
       imgsrc = (jQuery('#meta\\['+fkey+'\\]\\[value\\]').val());
       
       if (jQuery('#pin-images img[src="' + imgsrc + '"]').attr('src') != imgsrc) {
           jQuery('#img_template img').attr('src', imgsrc);
           jQuery('#img_template input:checkbox').val(imgsrc);
           jQuery('#pin-images').append(jQuery('#img_template').html());

           jQuery.each(var_pinned, function (index, value) {
               if (value != '') {
                   jQuery('#pin-images input:checkbox[value="' + value + '"]').attr('checked', '').parent().addClass('pinned');
               }
           });


       }
  
    }

    //update pinned images 
    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',

        data: {
            action: 'pinterest_automatic',
            pid: postID
        },

        success: function (data) {
            //remove ajax icon

            var res = jQuery.parseJSON(data);

            jQuery.each(res, function (index, value) {
                if (value != '') {
                    jQuery('#pin-images .scheduled input:checkbox[value="' + value + '"]').removeAttr('checked').parent().addClass('wp-pinterest-automatic-yellow').fadeOut('slow', function () {
                        jQuery(this).removeClass('wp-pinterest-automatic-yellow').removeClass('scheduled').addClass('pinned').fadeIn('slow');
                    });
                }
            });

        },

        beforeSend: function () {

        }
    });


    t = setTimeout("timedCount()", 5000);
}

timedCount();


jQuery(document).ready(function () {

	//SELECT ALL PINS
    jQuery('#wp_pinterest_automatic_all')
        .click(
            function () {

                if (jQuery(this).attr('checked') == 'checked') {
                    jQuery(
                        'input.pin_check')
                        .attr('checked', 'true');
                } else {
                    jQuery(
                        'input.pin_check')
                        .removeAttr('checked');
                }
            });

    //close link
    function activate_close() {
        jQuery('.close').click(function () {

            jQuery(this).parent().fadeOut('slow');

        });

    }


     //slider function
    function slider(id, slide) {
       if (jQuery(id).attr("checked")) {
            jQuery(slide).slideDown();
        } else {
            jQuery(slide).slideUp();
        }
    }

    //slider function
    function slider(id, slide) {

        if (jQuery(id).attr("checked")) {
            jQuery(slide).slideDown();
        } else {
            jQuery(slide).slideUp();
        }
    }

    //slider function
    function slider_hider(id, slide, hide) {

        if (jQuery(id).attr("checked")) {
            jQuery(hide).slideUp('fast', function () {
                jQuery(slide).slideDown();
            });
        } else {
            jQuery(hide).slideDown();
            jQuery(slide).slideUp();
        }

    }
    slider('#field-pin_options-1', '#pin-contain');

    jQuery("#field-pin_options-1").change(function () {

        slider('#field-pin_options-1', '#pin-contain');

    });

    //slider
    slider('#field-pin_options-2', '#pin_vars');

    jQuery("#field-pin_options-2").change(function () {

        slider('#field-pin_options-2', '#pin_vars');

    });



    // Check all and de select all check  
    jQuery('#wp_pinterest_automatic_all')
        .click(
            function () {
                if (jQuery(this).attr('checked') == 'checked') {
                    jQuery(
                        '#pin-contain input.pin_check')
                        .attr('checked', 'true');
                } else {
                    jQuery(
                        '#pin-contain input.pin_check')
                        .removeAttr('checked');
                }
            });
});