<?php if (!defined('ABSPATH')) {
	die('Forbidden');
}

class USETADA_API
{

	/**
	 * Request token
	 */
	public static function request_token()
	{

		$key 		= USETADA_Settings::get('apikey');
		$secret 	= USETADA_Settings::get('apisecret');

		$response = wp_remote_post('https://api.gift.id/oauth/token', array(
			'body'    => json_encode(array(
				'username'		=> USETADA_Settings::get('username'),
				'password'		=> USETADA_Settings::get('password'),
				'grant_type'	=> 'password',
				'scope'			=> 'offline_access'
			)),
			'headers' => array(
				'Content-Type'		=> 'application/json',
				'Authorization' 	=> 'Basic ' . base64_encode($key . ':' . $secret),
			),
		));

		if (is_wp_error($response)) {
			wp_die($response);
		} else {
			$retrieve = json_decode($response['body']);
			return $retrieve->access_token;
		}
	}

	/**
	 * Topup by phone number
	 */
	public static function topup_by_phone($phone_number, $order_id, $order_total, $payment_method = 'cash', $order_items)
	{

		if (!self::check_user_data() || USETADA_Settings::get('enabled')  == '0')
			return false;

		$token = self::request_token();
		$cashier_pin	= USETADA_Settings::get('cashier_pin');
		$wallet_id		= USETADA_Settings::get('wallet_id');

		$param = array(
			'phone'				=>	self::format_phone_number($phone_number),
			'amount'				=> (int)$order_total,
			'programId'			=> USETADA_Settings::get('program_id'),
			'billNumber'		=> (string)$order_id,
			'paymentMethod'	=> $payment_method,
			'items'				=> $order_items
		);

		$headers			= array(
			'Content-Type'			=> 'application/json',
			'Authorization'		=> 'Bearer ' . $token
		);

		if (!empty($cashier_pin)) {
			$param['cashierPin'] = (int)$cashier_pin;
		}

		if (!empty($wallet_id)) {
			$param['walletId'] = $wallet_id;
		}

		// Add decimals header if decimals enable in setting
		$decimals 		= get_option('woocommerce_price_num_decimals');
		if ($decimals)
			$headers['x-vnd-app-use-decimal'] 	= '1';

		$response = wp_remote_post('https://api.gift.id/v1/pos/phone/topup', array(
			'body'		=> json_encode($param),
			'headers'	=> $headers
		));

		if (is_wp_error($response)) {
			wp_die($response);
		} else {
			return $response['body'];
		}
	}

	/**
	 * Check if user information are complete
	 * 
	 * @return bool	whether required data is completed or no
	 */
	public static function check_user_data()
	{
		$settings = get_option('usetada_settings', USETADA_Settings::$default);
		if (empty($settings['username']) || empty($settings['password']) || empty($settings['apikey']) || empty($settings['apisecret'])) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Format phone number
	 */
	public static function format_phone_number($number)
	{
		$countryCode = '62';

		$malaysiaPhoneCode = array("01", "60");
		$isCountryCodeOfNumberIsMalaysia = (in_array(substr($number, 0, 2), $malaysiaPhoneCode));

		if ($isCountryCodeOfNumberIsMalaysia) $countryCode = '60';

		$internationalNumber = preg_replace('/^0/', '+' . $countryCode . '', $number);
		$internationalNumber = preg_replace('/^' . $countryCode . '/', '+' . $countryCode . '', $internationalNumber);

		// Remove all spaces in string.
		$internationalNumber = preg_replace('/\s+/', '', $internationalNumber);

		return $internationalNumber;
	}
}
