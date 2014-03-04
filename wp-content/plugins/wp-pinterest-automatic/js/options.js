jQuery(document)
		.ready(
				function() {

					// ctb add

					jQuery(document).on(
							'click',
							'.ctb .ctb_add',
							function() {
								jQuery('.ctb-contain').append(
										'<div class="ctb">'
												+ jQuery(this).parent()
														.parent().html()
												+ '</div>');
								return false;

							});

					jQuery(document).on(
							'click',
							'.ctb .ctb_remove',
							function() {
								if (jQuery('.ctb_add').length > 1) {
									jQuery(this).parent().parent().fadeOut(
											'slow', function() {

												jQuery(this).remove();

											});

								} else {
									jQuery('#field-ctb_enable-1').trigger(
											'click');
								}

								return false;

							});

					jQuery('#get_boards')
							.click(
									function() {

										jQuery
												.ajax({
													url : jQuery(this).parent()
															.attr('href'),
													type : 'POST',
													data : {
														action : "boards",
														user : jQuery(
																'#field-wp_pinterest_user')
																.val(),
														pass : jQuery(
																'#field-wp_pinterest_pass')
																.val()
													},

													success : function(data) {
														jQuery(
																'#ajax-loadingimg')
																.addClass(
																		'ajax-loading');

														var res = jQuery
																.parseJSON(data);
														if (res['status'] == 'success') {
															// console.log(data);

															jQuery(
																	'#field1zz option')
																	.remove();

															var ids = res['ids'];
															var titles = res['titles'];

															for (var i = 0; i < ids.length; i++) {

																jQuery('#field1zz').append('<option value="'
																						+ ids[i]
																						+ '">'
																						+ titles[i]
																						+ '</option>');

															
																//CTB BOARDS UPDATE
																if(jQuery('select[name="wp_pinterest_automatic_pinterest_category[]"] option[value="'+ids[i]+'"]').length == 0 ){
																	//append it 
																	jQuery('select[name="wp_pinterest_automatic_pinterest_category[]"]').append('<option value="'
																			+ ids[i]
																			+ '">'
																			+ titles[i]
																			+ '</option>');
																	
																	
																}
															
															}

														} else if (res['status'] == 'fail') {

															alert('Can not login, check log for more details . ');
															return false;

														}

													},

													beforeSend : function() {

														jQuery(
																'#ajax-loadingimg')
																.removeClass(
																		'ajax-loading');

													}

												});
										return false;
									});

					// data controler
					jQuery('input[data-controls]').each(
							function() {

								slider(this, '#'
										+ jQuery(this).attr('data-controls'));

								jQuery(this).change(
										function() {
											slider(this, '#'
													+ jQuery(this).attr(
															'data-controls'));
										});

							});

					// slider function
					function slider(id, slide) {

						if (jQuery(id).attr("checked")) {
							// call the function to be fired
							// when your box changes from
							// unchecked to checked
							jQuery(slide).slideDown();
						} else {
							// call the function to be fired
							// when your box changes from
							// checked to unchecked
							jQuery(slide).slideUp();
						}

					}

					jQuery(".hndle").on('click', function() {
						if (jQuery(this).parent().hasClass("closed")) {
							jQuery(this).parent().removeClass("closed");
						} else {
							jQuery(this).parent().addClass("closed");
						}

					});

				});