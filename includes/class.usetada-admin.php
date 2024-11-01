<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

class USETADA_Admin {

	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	private static function init_hooks() {
		self::$initiated = true;
		add_action( 'admin_menu', array( 'USETADA_Admin', 'register_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( 'USETADA_Admin', 'enqueue' ), 90 );

		add_action( 'wp_ajax_save_usetada_settings', array( 'USETADA_Settings', 'save_settings' ) );
		add_action( 'wp_ajax_usetada_retry_topup', array( 'USETADA_WC', 'retry_topup' ) );

	}

	/**
	 * Register admin menu
	 * 
	 * @static
	 */
	public static function register_admin_menu(){
		add_menu_page(
			__( 'TADA Settings', 'usetada' ),
			__( 'TADA', 'usetada' ),
			'manage_options',
			'usetada',
			array( 'USETADA_Settings', 'settings_page' ),
			'data:image/svg+xml;base64,PHN2ZyBpZD0ic2lkZWJhci1pY29uIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNC4wMDEiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxNC4wMDEgMTQiPgogIDxwYXRoIGlkPSJTdWJ0cmFjdGlvbl8yIiBkYXRhLW5hbWU9IlN1YnRyYWN0aW9uIDIiIGQ9Ik0tNDI1NC02NjloLTEwYTIsMiwwLDAsMS0yLTJ2LTEwYTIsMiwwLDAsMSwyLTJoMTBhMiwyLDAsMCwxLDIsMnYxMEEyLDIsMCwwLDEtNDI1NC02NjlabS4yMTUtOC42MTVoLS43NDdsLTEuMzYyLDMuMjNoMS40NjFWLTY3NWgtLjQ3MmwuMDI3LS4wNjcuMDIxLS4wNTIuMjkzLS43MzguNC0xLjAwNS4zLjc0MS40MSwxLC4wMTQuMDMxLjAzNi4wODloLS40Mzh2LjYxM2gxLjQ2MVptLTcuMDUyLDBoLS43NDhsLTEuMzYxLDMuMjNoMS40NjFWLTY3NWgtLjQ3MmwuMDI3LS4wNjcuMDIxLS4wNTIuMjkzLS43MzguNC0xLjAwNS4zLjc0MS40MSwxLC4wMTQuMDMxLjAzNi4wODloLS40Mzl2LjYxM2gxLjQ2MlptMS43NzEsMHYzLjIzaDEuMzI4YTIuMzM5LDIuMzM5LDAsMCwwLC42MjUtLjA2NywxLjI4MSwxLjI4MSwwLDAsMCwuNS0uMjU4LDEuNDA5LDEuNDA5LDAsMCwwLC4zNzQtLjU3MSwxLjk3MSwxLjk3MSwwLDAsMCwuMTA4LS42ODcsMi4yNTksMi4yNTksMCwwLDAtLjExMy0uNzY0LDEuMzk0LDEuMzk0LDAsMCwwLS4zMzUtLjUyMiwxLjE3OCwxLjE3OCwwLDAsMC0uNTI3LS4zLDIuNzcyLDIuNzcyLDAsMCwwLS42NjUtLjA2MVptLTUuMzgyLjU0NnYyLjY4M2guNzE2di0yLjY4M2gxLjA1di0uNTQ2aC0yLjgxOXYuNTQ2Wm02LjYxNCwyLjE0aC0uNTI3di0yLjEzOWguMzE3YTMuNDg0LDMuNDg0LDAsMCwxLC41NzkuMDMuNzA2LjcwNiwwLDAsMSwuMzI4LjE1Mi43MzcuNzM3LDAsMCwxLC4yLjMxNCwxLjc5MSwxLjc5MSwwLDAsMSwuMDcyLjU3NCwxLjk2LDEuOTYsMCwwLDEtLjA3Mi41OTIuNjc5LjY3OSwwLDAsMS0uMTg1LjMxMy42NzEuNjcxLDAsMCwxLS4yODUuMTM0QTIuMDEyLDIuMDEyLDAsMCwxLTQyNTcuODMzLTY3NC45MjlaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg0MjY2IDY4MykiIGZpbGw9IiNhMGE1YWEiLz4KPC9zdmc+Cg==',
			80
		);
	}

	/**
	 * Enqueue scripts
	 * 
	 * @static
	 */
	public static function enqueue(){
		$screen = get_current_screen();

		wp_enqueue_style( 'usetada-admin', USETADA_PLUGIN_URI . 'admin/css/admin.css', array(), '1.0', 'all' );

		// Call scripts only in TADA page
		if( $screen->id == 'toplevel_page_usetada' || $screen->id == 'shop_order' ){

			remove_all_actions( 'admin_notices' );

			// Load WP Media
			wp_enqueue_media();

			// Load WP Color Picker
			wp_enqueue_style( 'wp-color-picker' ); 
			wp_enqueue_script( 'wp-color-picker' ); 

			// Google fonts
			wp_enqueue_style( 'usetada-google-fonts', 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700&display=swap', array(), '1.0', 'all' );

			// Load jQuery UI
			wp_enqueue_script( 'usetada-jquery-validation', USETADA_PLUGIN_URI . 'admin/js/jquery.validate.min.js', array(), '1.19.1', true );
			wp_enqueue_script( 'usetada-admin', USETADA_PLUGIN_URI . 'admin/js/admin.js', array(), '1.0', true );

			$vars['ajaxurl']			= admin_url( 'admin-ajax.php' );
			$vars['choose_image']	= __( 'Choose Image', 'usetada' );
			$vars['save']				= __( 'SAVE CHANGES', 'usetada' );
			$vars['saving']			= __( 'Saving...', 'usetada' );
			$vars['button_icon']		= USETADA_PLUGIN_URI . '/public/images/giftbox.png';
			$vars['confirm_retry']	= __( 'Are you sure want to retry the topup process? This action cannot be undone.', 'usetada' );
			wp_localize_script( 'usetada-admin', 'usetada', $vars );
		
		}
	}

}