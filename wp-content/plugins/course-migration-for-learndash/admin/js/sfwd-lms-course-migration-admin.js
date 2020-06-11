(function ($) {
	'use strict';

	function generateKey(length) {
		var result = '';
		var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		var charactersLength = characters.length;
		for (var i = 0; i < length; i++) {
			result += characters.charAt(Math.floor(Math.random() * charactersLength));
		}
		return result;
	}
	jQuery(document).ready(function () {



		var $toggle = false;
		var $course_id = 0;

		jQuery('#ldcm_generate_key').click(function () {

			jQuery('#ldcm_security_key').attr('value', generateKey(20));
		});
		jQuery('#ldcm_add_entry').click(function () {
			// jQuery('.ldcm_whitelist').append(

			// );
			jQuery("<div class='form-group'><input class='form-control' type='text' name='ldcm_whitelist[]' value='' /></div>").insertBefore('#ldcm_add_entry');
		});

		jQuery("select[name='course_id']").on('change', function () {
			$course_id = this.value;
			console.log($course_id);
			jQuery.ajax({
				method: 'post',
				url: ajaxurl,
				data: {
					'course_id': $course_id,
					'action': 'ldcm_fetch_course_data',
					'_ajax_nonce': ajaxNonce.nonce
				}
				,
				success: function ($response) {
					jQuery('.ldcm_course').empty();
					jQuery('.ldcm_course').append($response);
				}
				,
				error: function () {
					console.log('error');
				}
			});

		});

		jQuery('#ldcm_toggle').click(function () {
			// var $checkboxes = jQuery('.ldcm_course').children('input');
			var $checkboxes = jQuery('.ldcm_course input[type="checkbox"]');
			$toggle = $toggle ? false : true;
			Array.prototype.forEach.call($checkboxes, $checkbox => {
				if ($toggle) {
					jQuery($checkbox).prop('checked', 'checked');
				} else {
					jQuery($checkbox).prop('checked', false);
				}
			});
		});

		jQuery('#ldcm_migrate').click(function () {


			jQuery('.ldcm-loader').attr('src', ldcm_icons.loading);
			jQuery('.ldcm-loader').show();
			// migrate button click

			var $url = $('#ldcm_receiver_url').attr('value') + ldcm_urls.endpoint;
			var $isValid = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/.test($url);

			if (!$isValid) {
				alert('Please enter a valid url');
				throw new Error('Invalid Url supplied');
			}
			// console.log($url);

			// var $checkboxes = jQuery('.ldcm_course').children('input');
			var $checkboxes = jQuery('.ldcm_course input[type="checkbox"]');
			var $checked = [];
			$checked.push(jQuery('.ldcm_course input[type="hidden"]').prop('value'));



			jQuery.each($checkboxes, function ($key, $value) {

				if (jQuery($value).prop('checked')) {
					// do the task here itself
					$checked.push(jQuery($value).prop('value'));
				}

			});

			var $count = 0;

			var $securityKey = MD5(jQuery('#ldcm_receiver_key').val());

			jQuery.each($checked, function ($key, $value) {
				//async false to reduce browser load
				// show loading icon 

				jQuery.ajax({
					method: 'post',
					url: ajaxurl,
					// async: false,
					data: {
						'post_id': $value,
						'action': 'ldcm_fetch_post_data',
						'_ajax_nonce': ajaxNonce.nonce
					}
					,
					success: function ($response) {
						// console.log($response);

						// $response contains the wordpress post to be sent to the client
						jQuery('');

						// create a div for each step and show status
						console.log('response')
						console.log($response)
						console.log('url')
						console.log($url)
						var data = {
							json: JSON.stringify({ 'postdata': $response, 'ldcm_security': $securityKey }),
							action: 'send_data_to_client',
							url: $url 
						};
						// send the fetched post to receiver
						jQuery.ajax({
							method: 'post',
							// url: $url,
							url: ajaxurl,
							data: data,
							// contentType: "application/json; charset=utf-8",
							// as sending data to other server
							// crossDomain: true,
							// async: false,
							// dataType: "json",
							

							success: function ($response) {
								jQuery('.ldcm-loader-' + $value).attr('src', ldcm_icons.complete);
								// display $response to dom
								console.log('json'); 
								console.log($response);

							}
							,
							error: function () {
								$('.ldcm-loader-' + $value).attr('src', ldcm_icons.error);
							}
						});
					}
					,
					error: function () {
					}
				});

			});

			//save to code notes : recursive ajax
			//rethink, recursive vs forloop...
		});
	});

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})(jQuery);
