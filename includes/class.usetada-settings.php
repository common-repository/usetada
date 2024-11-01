<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

class USETADA_Settings {

	/**
	 * Default settings
	 */
	public static $default = array(
		'enabled'				=> '0',
		'username'				=> '',
		'password'				=> '',
		'program_id'			=> '',
		'cashier_pin'			=> '',
		'wallet_id'				=> '',
		'page_url'				=> '',
		'button_icon'			=> USETADA_PLUGIN_URI . '/public/images/giftbox.png',
		'button_color'			=> '#F96D01',
		'button_text'			=> 'Get Rewards',
		'button_text_color'	=> '#FFFFFF',
		'button_position'		=> 'left',
		'apikey'					=> '',
		'apisecret'				=> ''
	);

	/**
	 * Settings Page
	 * 
	 * @static
	 */
	public static function settings_page(){ ?>

		<?php 
		// Check capabilities
		if( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>

		<div class="usetada wp-clearfix">
		 	<div class="usetada__left">
				 
		 		<h1><?php echo __( 'YOUR CREDENTIALS', 'usetada' ); ?></h1>

				<form action="save_usetada_settings" method="post" id="usetada-form" class="usetada-form">
					<?php self::settings_fields(); ?>
					<button type="submit" class="usetada-submit">
						<?php echo __( 'SAVE CHANGES', 'usetada' ); ?>
					</button>
				</form>

				<div class="usetada-notice">
					<div>
						<img src="<?php echo USETADA_PLUGIN_URI . '/admin/images/success.png'; ?>" alt="<?php echo __( 'Success icon', 'usetada' ); ?>">
						<div class="usetada-notice__title"><?php echo __( 'Congratulations!', 'usetada' ); ?></div>
						<div class="usetada-notice__message"><?php echo __( 'Your changes has been saved.', 'usetada' ); ?></div>
						<a class="usetada-notice__close" href="javascript:void(0);"><?php echo __( 'Awesome', 'usetada' ); ?></a>
					</div>
				</div>

			</div>
			<div class="usetada__right">
		 		<div class="usetada-logo">
		 			<img src="<?php echo USETADA_PLUGIN_URI . '/admin/images/logo-tada-white.png' ?>" alt="TADA - Customer retention platform">
				</div>
				<div class="usetada-tagline">TADA - Customer Retention Platform</div>
				<div class="usetada-video">
		 			<div class="usetada-video-fluid">
						<iframe width="560" height="349" src="//www.youtube.com/embed/-HZqprZDKHU?rel=0&hd=1&modestbranding=1&controls=0" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
				<div class="usetada-description">
					<?php echo __( 'Double your profit per customer via membership, loyalty, subscription and referral.', 'usetada' ); ?>
				</div>
				<a class="usetada-cta" href="https://usetada.com" target="_blank"><?php echo __( 'LEARN MORE', 'usetada' ); ?></a>
			</div>
		</div>

	<?php }

	/**
	 * Setting fields
	 */
	public static function settings_fields() { ?>
		<?php 
		$settings = get_option( 'usetada_settings', self::$default );
		?>
		<?php wp_nonce_field( 'save_usetada_settings', 'security' ); ?>
		
		<div class="usetada-field usetada-field-switch wp-clearfix">
			<input type="hidden" name="usetada_settings[enabled]" value="0">
			<label class="usetada-switch">
				<?php echo __( 'Switch your program to on/off', 'usetada' ); ?>
				<input type="checkbox" name="usetada_settings[enabled]" value="1" <?php checked( $settings['enabled'], '1' ); ?>>
				<span>
					<span></span>
				</span>
			</label>
		</div>

		<div class="usetada-field">
			<input type="text" name="usetada_settings[apikey]" id="usetada-apikey" value="<?php echo $settings['apikey']; ?>" placeholder="<?php echo __( 'Enter your API key', 'usetada' ); ?>">
			<label for="usetada-apikey" class="usetada-field__label"><?php echo __( 'API Key', 'usetada' ); ?></label>
		</div>

		<div class="usetada-field usetada-field-apisecret">
			<label class="usetada-show-password">
				<input type="checkbox" name="show-apisecret" id="show-apisecret">
				<span></span>
			</label>
			<input type="password" name="usetada_settings[apisecret]" id="usetada-apisecret" value="<?php echo $settings['apisecret']; ?>" placeholder="<?php echo __( 'Enter your API secret', 'usetada' ); ?>">
			<label for="usetada-apisecret" class="usetada-field__label"><?php echo __( 'API Secret', 'usetada' ); ?></label>
		</div>

		<div class="usetada-field">
			<input type="text" name="usetada_settings[username]" id="usetada-username" value="<?php echo $settings['username']; ?>" placeholder="<?php echo __( 'Enter your username', 'usetada' ); ?>">
			<label for="usetada-username" class="usetada-field__label"><?php echo __( 'Username', 'usetada' ); ?></label>
		</div>

		<div class="usetada-field usetada-field-password">
			<label class="usetada-show-password">
				<input type="checkbox" name="show-password" id="show-password">
				<span></span>
			</label>
			<input type="password" name="usetada_settings[password]" id="usetada-password" value="<?php echo $settings['password']; ?>" placeholder="<?php echo __( 'Enter your password', 'usetada' ); ?>">
			<label for="usetada-password" class="usetada-field__label"><?php echo __( 'Password', 'usetada' ); ?></label>
		</div>

		<div class="usetada-field">
			<input type="number" name="usetada_settings[program_id]" id="usetada-program_id" value="<?php echo $settings['program_id']; ?>" placeholder="<?php echo __( 'Enter your program ID', 'usetada' ); ?>">
			<label for="usetada-program_id" class="usetada-field__label"><?php echo __( 'Program ID', 'usetada' ); ?></label>
		</div>

		<div class="usetada-field">
			<input type="number" name="usetada_settings[cashier_pin]" id="usetada-cashier_pin" value="<?php echo $settings['cashier_pin']; ?>" placeholder="<?php echo __( 'Enter your cashier PIN', 'usetada' ); ?>">
			<label for="usetada-cashier_pin" class="usetada-field__label"><?php echo __( 'Cashier PIN', 'usetada' ); ?></label>
		</div>

		<div class="usetada-field">
			<input type="text" name="usetada_settings[wallet_id]" id="usetada-wallet_id" value="<?php echo $settings['wallet_id']; ?>" placeholder="<?php echo __( 'Enter your wallet ID', 'usetada' ); ?>">
			<label for="usetada-wallet_id" class="usetada-field__label"><?php echo __( 'Wallet ID', 'usetada' ); ?></label>
		</div>

		<div class="usetada-field">
			<input type="url" name="usetada_settings[page_url]" id="usetada-page_url" value="<?php echo $settings['page_url']; ?>" placeholder="<?php echo __( 'E.g.: https://mypage.com', 'usetada' ); ?>">
			<label for="usetada-page_url" class="usetada-field__label"><?php echo __( 'Page URL', 'usetada' ); ?></label>
		</div>

		<div class="usetada-field">
			<p>&nbsp;</p>
			<strong><?php echo __( 'Select the button icon and button color', 'usetada' ); ?></strong>
		</div>

		<div class="wp-clearfix">
			<div class="usetada-field usetada-field-image half">
				<label for="usetada-button_icon"><?php echo __( 'Button Icon', 'usetada' ); ?></label>
				<div class="usetada-select-image">
					<div class="usetada-select-image__menu">
						<div>
							<a class="usetada-select-image-select" href="javascript:void(0);"></a>
							<a class="usetada-select-image-delete" href="javascript:void(0);"></a>
						</div>
					</div>
					<img src="<?php echo $settings['button_icon']; ?>" alt="<?php echo __( 'Button Icon', 'usetada' ); ?>">
				</div>
				<span class="hint"><?php echo __( 'Best ratio 1:1, 500Kb.', 'usetada' ); ?></span>
				<input type="hidden" name="usetada_settings[button_icon]" id="usetada-button_icon" value="<?php echo $settings['button_icon']; ?>" >
			</div>
			<div class="usetada-field usetada-field-color half">
				<label for="usetada-button_color"><?php echo __( 'Button Color', 'usetada' ); ?></label>
				<input type="text" id="usetada-button_color" class="usetada-color-picker" name="usetada_settings[button_color]" value="<?php echo $settings['button_color']; ?>" data-default-color="#F96D01">
			</div>
		</div>
		

		<div class="usetada-field">
			<br/>
			<strong><?php echo __( 'Add text and color to your button', 'usetada' ); ?></strong>
		</div>
		
		<div class="wp-clearfix">
			<div class="usetada-field usetada-field-button_text">
				<input type="text" name="usetada_settings[button_text]" id="usetada-button_text" value="<?php echo $settings['button_text']; ?>" placeholder="<?php echo __( 'E.g.: Grab Your Rewards', 'usetada' ); ?>">
				<label for="usetada-button_text" class="usetada-field__label"><?php echo __( 'Button Text', 'usetada' ); ?></label>
			</div>

			<div class="usetada-field usetada-field-color usetada-field-button_text_color">
				<label for="usetada-button__text_color"><?php echo __( 'Button Text Color', 'usetada' ); ?></label>
				<input type="text" id="usetada-button_text_color" class="usetada-color-picker" name="usetada_settings[button_text_color]" value="<?php echo $settings['button_text_color']; ?>" data-default-color="#FFFFFF">
			</div>
		</div>

		<div class="usetada-field">
			<br/>
			<strong><?php echo __( 'Select the position of the rewards button on your site', 'usetada' ); ?></strong>
		</div>

		<div class="usetada-field usetada-field-radio">
			<label for="usetada-button_position_left" class="usetada-radio">
				<input type="radio" name="usetada_settings[button_position]" id="usetada-button_position_left" value="left" <?php checked( $settings['button_position'], 'left' ); ?>>
				<span></span>
				<?php echo __( 'Bottom Left', 'usetada' ); ?>
			</label>
			<label for="usetada-button_position_right" class="usetada-radio">
				<input type="radio" name="usetada_settings[button_position]" id="usetada-button_position_right" value="right" <?php checked( $settings['button_position'], 'right' ); ?>>
				<span></span>
				<?php echo __( 'Bottom Right', 'usetada' ); ?>
			</label>
		</div>

	<?php }

	/**
	 * Save settings
	 * 
	 * @return JSON
	 */
	public static function save_settings(){

		// Verify nonce
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'save_usetada_settings' ) ){
			wp_send_json( array(
				'success'		=> false,
				'message'		=> __( 'Invalid request, please try again or contact support.', 'usetada' ),
			) );
		}

		// Sanitize input
		$hygine_setting = self::sanitize( $_POST['usetada_settings'] );
		update_option( 'usetada_settings', $hygine_setting );

		wp_send_json( array( 
			'success'	=> true,
		) );
	}
	
	/**
	 * Sanitize settings input
	 * 
	 * @var $input 
	 */
	public static function sanitize( $input ){
		$input = array_map( array( 'USETADA_Settings', 'sanitize_field' ), $input );
		return $input;
	}

	/**
	 * Sanitize each field
	 */
	public static function sanitize_field( $value ){
		$value = sanitize_text_field( $value );
		return $value;
	}

	/**
	 * Get settings
	 * 
	 * @return mixed	value of the setting if key is specified
	 * @return array	all settings if key not specified
	 */
	public static function get( $key = null ){
		$settings = get_option( 'usetada_settings', self::$default );
		return empty( $key ) ? $settings : $settings[$key];
	}

}