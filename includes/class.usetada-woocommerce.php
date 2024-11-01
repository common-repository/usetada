<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

class USETADA_WC {
	
	public static function init() {
	
		/* Set billing phone number as required */
		add_filter( 'woocommerce_billing_fields', array( 'USETADA_WC', 'billing_fields' ), 99 );

		/* Send API on order complete */
		add_action( 'woocommerce_order_status_completed', array( 'USETADA_WC', 'order_completed' ), 99 );

		/* Register metabox */
		add_action( 'add_meta_boxes', array( 'USETADA_WC', 'order_metabox' ) );

	}

	/**
	 * Filter WooCommerce billing fields 
	 * 
	 * @return array of fields
	 */	
	public static function billing_fields( $fields ){
		$fields['billing_phone']['required'] 		= true;
		$fields['billing_phone']['placeholder']	= __( 'E.g.: +628123...', 'usetada' );
		return $fields;
	}

	/**
	 * Send order detail to API when order status changed to completed
	 * 
	 * @return static
	 */
	public static function order_completed( $order_id ){
		$order_topup = self::order_topup( $order_id );
	}

	/**
	 * Send order topup API request
	 */
	public static function order_topup( $order_id ){
		$decimals 				= get_option( 'woocommerce_price_num_decimals' );
		$status					= get_post_meta( $order_id, 'usetada_topup_status', true );
		$order 					= wc_get_order( $order_id );
		$phone_number			= $order->get_billing_phone();

		// If topup status already success or order already completed, stop process
		if( $status == 'success' )
			return false;

		$order_total 		= $order->get_subtotal() - $order->get_discount_total();
		$payment_method	= $order->get_payment_method();

		$order_items		= $order->get_items();
		if( count( $order_items ) > 0 ){
			foreach( $order_items as $order_item ){
				$product = $order_item->get_variation_id() ? wc_get_product( $order_item->get_variation_id() ) : wc_get_product( $order_item->get_product_id() );
				$items[] = array(
					'sku'			=> $product->get_sku(),
					'itemName'	=> $order_item->get_name(),
					'quantity'	=> $order_item->get_quantity(),
					'price'		=> number_format( $order_item->get_total(), $decimals, '.', '' )
				);
			}
		} else {
			$items = '';
		}

		$topup = USETADA_API::topup_by_phone( $phone_number, $order_id, $order_total, $payment_method, $items );

		$topup_obj = json_decode( $topup );

		if( $topup ){

			$status = 'success';

			if( property_exists( $topup_obj, 'error' ) ){
				$status = 'failed';
			}

			update_post_meta( $order_id, 'usetada_topup_items', $items );
			update_post_meta( $order_id, 'usetada_topup_last_process', current_time( 'timestamp' ) );
			update_post_meta( $order_id, 'usetada_topup_status', $status );
			update_post_meta( $order_id, 'usetada_topup_last_api_response', $topup );
			return $status;
		} else {
			return false;
		}
	}

	/**
	 * Get order topup status html
	 */
	public static function order_topup_status_html( $order_id = null ){
		$order_id 	= empty( $order_id ) ? get_the_ID() : $order_id;
		$status 		= get_post_meta( get_the_ID(), 'usetada_topup_status', true );
		if( $status == 'failed' ){
			return '<strong style="color: #DD303B;">' . __( 'Failed', 'usetada' ) . '</strong>';
		} elseif( $status == 'success' ){
			return '<strong style="color: #4DDA61;">' . __( 'Success', 'usetada' ) . '</strong>';
		} else {
			return '<strong style="color: #E9BF54;">' . __( 'Pending', 'usetada' ) . '</strong>';
		}
	}

	/**
	 * Register metabox to WooCommerce order
	 */
	public static function order_metabox(){
		add_meta_box( 'usetada-order', __( 'TADA Topup', 'usetada' ), array( 'USETADA_WC', 'display_order_metabox' ), 'shop_order', 'side' );
	}

	/**
	 * Display order metabox
	 */
	public static function display_order_metabox(){ ?>
		<?php 
		$order_id				= get_the_ID();
		$order					= wc_get_order( $order_id ); 
		$last_process 			= get_post_meta( $order_id, 'usetada_topup_last_process', true );
		$status					= get_post_meta( $order_id, 'usetada_topup_status', true );
		$last_api_response	= get_post_meta( $order_id, 'usetada_topup_last_api_response', true );
		?>
		<div class="usetada-metabox">
			
			<div style="font-size: 16px;"><?php echo __( 'Status', 'usetada' ); ?>: <?php echo self::order_topup_status_html(); ?></div>
			
			<?php if( ! empty( $last_process ) ){ ?>
				<p class="small"><?php echo sprintf( __( 'Last processed on %s at %s', 'usetada' ), date( 'F d, Y', $last_process ), date( 'H:i:s', $last_process ) ); ?></p>
			<?php } else { ?>
				<p class="small"><?php echo __( 'Topup will be automatically processed when the order marked as completed.', 'usetada' ); ?></p>
			<?php } ?>
			
			<?php if( $status == 'failed' && $order->status == 'completed' ){ ?>
				<p><a href="javascript:void(0);" data-order-id="<?php echo $order_id; ?>" data-security="<?php echo wp_create_nonce( 'usetada_retry_topup' ); ?>" class="usetada-retry-topup button button-primary"><?php echo __( 'Retry', 'usetada' ); ?></a></p>
			<?php } ?>
			
			<?php if( ! empty( $last_api_response ) ){ ?>
				<p><hr/></p>
				<strong><?php echo __( 'Last API Response', 'usetada' ); ?></strong>
				<pre style="white-space: pre-wrap;background: #eee;padding: 10px;border-radius: 6px;border: 1px  solid #ccd0d4;height: 100px;overflow: scroll;word-break:break-word;"><?php echo $last_api_response; ?></pre>
			<?php } ?>

		</div>
	<?php }

	/**
	 * Ajax retry topup
	 */
	public static function retry_topup(){
		// Verify nonce
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'usetada_retry_topup' ) ){
			wp_send_json( array(
				'success'		=> false,
				'message'		=> __( 'Invalid request, please try again or contact support.', 'usetada' ),
			) );
		}

		// Process topup
		$order_topup = self::order_topup( sanitize_text_field( $_POST['order_id'] ) );

		if( $order_topup == 'failed' ){
			wp_send_json( array(
				'success'		=> false,
				'message'		=> __( 'Topup failed, please make sure the details are correct or see the API response in the "Last Response" box in your order detail page.', 'usetada' ),
			) );
		}

		wp_send_json( array( 
			'success'	=> true,
			'message'	=> __( 'Topup successful.', 'usetada' )
		) );
	}

}