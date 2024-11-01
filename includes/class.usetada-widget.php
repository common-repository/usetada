<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

class USETADA_Widget {

	public static function init(){

		$settings 	= get_option( 'usetada_settings', USETADA_Settings::$default ); 

		// Don't load widget if switched off
		if( $settings['enabled'] == '0' || empty( $settings['page_url'] ) )
			return false;

		add_action( 'wp_enqueue_scripts', array( 'USETADA_Widget', 'enqueue' ) );
		add_action( 'wp_footer', array( 'USETADA_Widget', 'html' ) );
	}

	public static function enqueue(){
		wp_enqueue_style( 'usetada', USETADA_PLUGIN_URI . '/public/css/widget.css', array(), '1.0', 'all' );

		$settings 	= get_option( 'usetada_settings', USETADA_Settings::$default ); 
		ob_start(); ?>

			.usetada-button { background: <?php echo $settings['button_color']; ?>; }
			.usetada-button__icon > span { color: <?php echo $settings['button_text_color']; ?>; }

		<?php 
		$dynamic_css = ob_get_clean();
		wp_add_inline_style( 'usetada', $dynamic_css );

		// Load snippet widget of tada wallet.
		$page_url = esc_url( trailingslashit( $settings['page_url'] ) );
		wp_enqueue_script( 'usetada-widget', $page_url . 'public/js/snippet-widget.min.js', array(), '1.2', false );

		wp_enqueue_script( 'usetada', USETADA_PLUGIN_URI . '/public/js/widget.js', array(), '1.2', true );
	}

	/**
	 * Widget html
	 * 
	 * @return string
	 */
	public static function html(){ ?>
		<?php 
		$settings 	= get_option( 'usetada_settings', USETADA_Settings::$default ); 

		// Widget classes
		$classes[]	= 'usetada-widget';
		$classes[]	= 'usetada-widget--' . $settings['button_position'];
		?>

		<div id="usetada-widget" class="<?php echo implode( ' ', $classes ); ?>">
			<div class="usetada-embed usetada-embed--hidden usetada_animated">
				<span class="usetada-alt-close"></span>
				<div id="tada-wallet-widget" app-url="<?php echo esc_url( $settings['page_url'] ); ?>"></div>
			</div>
			<div class="usetada-button">
				<div class="usetada-button__icon">
					<img src="<?php echo esc_url( $settings['button_icon'] ); ?>" alt="<?php echo esc_attr( $settings['button_text'] ); ?>">
					<span><?php echo esc_attr( empty( $settings['button_text'] ) ? USETADA_Settings::$default['button_text'] : $settings['button_text'] ); ?></span>
				</div>
			</div>
		</div>

	<?php }

}