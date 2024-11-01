/**
 * Function to replace textNode
 */

(function ($) {

	$(document).ready(function () {

		/**
		 * Detect form changes
		 */
		$('#usetada-form :input').change(function () {
			$('#usetada-form').data('changed', true).attr('data-changed', true);
		});

		/**
		 * Show hide password
		 */
		$('.usetada-show-password input[type="checkbox"]').click(function () {
			$(this).is(':checked') ? $(this).parent().siblings('input').attr('type', 'text') : $(this).parent().siblings('input').attr('type', 'password');
		});

		/**
		 * Select / upload icon
		 */
		var mediaUploader;
		$(document).on('click', '.usetada-select-image-select', function (e) {
			e.preventDefault();
			var imgPreview = $(this).parents('.usetada-select-image__menu').siblings('img');
			var imgInput = $(this).parents('.usetada-select-image').siblings('input');
			if (mediaUploader) {
				mediaUploader.open();
				return;
			}
			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: usetada.choose_image,
				button: {
					text: usetada.choose_image
				}, multiple: false
			});
			mediaUploader.on('select', function () {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				imgPreview.attr('src', attachment.url);
				imgInput.val(attachment.url);
				$('#usetada-form :input').trigger('change');
			});
			mediaUploader.open();
		});

		/**
		 * Remove selected image and add default image
		 */
		$(document).on('click', '.usetada-select-image-delete', function (e) {
			e.preventDefault();
			var imgPreview = $(this).parents('.usetada-select-image__menu').siblings('img');
			var imgInput = $(this).parents('.usetada-select-image').siblings('input');
			imgPreview.attr('src', usetada.button_icon);
			imgInput.val(usetada.button_icon);
			$('#usetada-form :input').trigger('change');
		});

		/**
		 * WP color picker
		 */
		$('.usetada-color-picker').wpColorPicker();

		/**
		 * Form validation
		 */
		$('#usetada-form').validate({
			errorPlacement: function () {
				return false;
			},
			submitHandler: function (form) {

				// Show loading animation
				$('.usetada-submit').text(usetada.saving).addClass('loading');

				var data = $(form).serialize();

				$.ajax({
					type: 'POST',
					url: usetada.ajaxurl,
					data: data + '&action=save_usetada_settings',
					dataType: 'json',
					success: function (response) {
						if (response.success) {
							$('.usetada-notice').css('display', 'flex').hide().fadeIn();
							$('.usetada-submit').text(usetada.save).removeClass('loading');
							$("html, body").animate({
								scrollTop: 0
							}, 500);
							setTimeout(function () {
								$('.usetada-notice').fadeOut();
							}, 3000);
						} else {
							alert(response.message);
							location.reload();
						}
					}
				});
				return false; // Prevent default action
			}
		});

	});

	/**
	 * Close notice
	 */
	$('.usetada-notice__close').click(function (e) {
		e.preventDefault();
		$('.usetada-notice').fadeOut();
	});

	/**
	 * Retry topup
	 */
	$(document).on('click', '.usetada-retry-topup', function (e) {
		e.preventDefault();
		if (confirm(usetada.confirm_retry)) {
			$.ajax({
				type: 'POST',
				url: usetada.ajaxurl,
				data: {
					action: 'usetada_retry_topup',
					order_id: $(this).data('order-id'),
					security: $(this).data('security')
				},
				dataType: 'json',
				success: function (response) {
					if (response.success) {
						alert(response.message);
					} else {
						alert(response.message);
					}
					$('.usetada-metabox').load(location.href + ' .usetada-metabox>*', '');
				}
			});
		}
		return false;
	});

})(jQuery);