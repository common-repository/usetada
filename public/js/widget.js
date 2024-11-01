document.addEventListener('DOMContentLoaded', function () {
	if (document.getElementById('usetada-widget') !== null) {
		var usetada_cont = document.querySelector('.usetada-widget');
		var usetada_embed = document.querySelector('.usetada-embed');
		var usetada_toggle = document.querySelector('.usetada-button');
		var usetada_alt_close = document.querySelector('.usetada-alt-close');

		// Open message box on button click
		usetada_toggle.addEventListener('click', function () {
			usetada_toggle_box();
		});

		// Alternative close button
		usetada_alt_close.addEventListener('click', function () {
			usetada_toggle_box();
		});

		// Close box on click outside the box
		window.addEventListener('click', function (e) {
			if (usetada_cont.classList.contains('usetada-widget--opened') && !usetada_cont.contains(e.target)) {
				usetada_toggle_box();
			}
		});

		// Toggle message box
		function usetada_toggle_box() {
			usetada_cont.classList.toggle('usetada-widget--opened');

			if (usetada_cont.classList.contains('usetada-widget--opened')) {
				// Call Tada Wallet Widget.
				openTadaWalletWidget();
			} else {
				usetada_embed.classList.add('usetada_fadeOutDown');
				usetada_embed.classList.remove('usetada_fadeInUp');
				setTimeout(function () {
					usetada_embed.classList.add('usetada-embed--hidden');
				}, 510);
			}
		}

		function openTadaWalletWidget() {
			var appUrl = document.querySelector('#tada-wallet-widget').getAttribute('app-url');

			TadaWallet.widget({
				appUrl,
				target: '#tada-wallet-widget',
			})
			.then(function() {
				usetada_embed.classList.add('usetada_fadeInUp');
				usetada_embed.classList.remove('usetada_fadeOutDown', 'usetada-embed--hidden');
			})
			.catch(function() {
				usetada_cont.classList.toggle('usetada-widget--opened');
			});
		}
	}
});
