<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

class USETADA {
	
	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	private static function init_hooks() {
		self::$initiated = true;

		USETADA_Widget::init();

		/**
		 * Load WooCommerce functionality if WooCommerce is active
		 */
		if( self::is_plugin_active( 'woocommerce/woocommerce.php' ) ){
			if( USETADA_Settings::get('enabled')  == '1' ){
				USETADA_WC::init();
			}
		}
	}

	/**
	 * Run on plugin activation
	 * 
	 * @static
	 */
	public static function plugin_activation(){
		// Reserved for next features
	}

	/**
	 * Run on plugin uninstall
	 * 
	 * @static
	 */
	public static function plugin_uninstall(){
		// Delete plugin options
		delete_option( 'usetada_settings' );
	}

	public static function is_plugin_active( $plugin ){
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
	}

}