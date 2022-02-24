<?php

/**
 * Payment Gateway class for CyberSource Online
 *
 * @package Abzer
 */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Cybersource_Gateway_Config class.
 */
class Cybersource_Gateway_Config {

	
	const SANDBOX_URL = 'https://testsecureacceptance.cybersource.com/pay';
	const LIVE_URL = 'https://secureacceptance.cybersource.com/pay';

	/**
	 * Pointer to gateway making the request.
	 *
	 * @var Cybersource_Gateway
	 */
	public $gateway;

	/**
	 * Token for gateway request
	 *
	 * @var string token
	 */
	private $token;

	/**
	 * Constructor.
	 *
	 * @param Cybersource_Gateway $gateway CyberSource Online gateway object.
	 */
	public function __construct( Cybersource_Gateway $gateway) {
		$this->gateway = $gateway;
	}

	/**
	 * Set token
	 *
	 * @param string $token Token.
	 */
	public function set_token( $token) {
		$this->token = $token;
	}

	/**
	 * Get token
	 *
	 * @return string Token
	 */
	public function get_token() {
		return $this->token;
	}

	/**
	 * Retrieve apikey and outletReferenceId empty or not
	 *
	 * @return bool
	 */
	public function is_complete() {
		return ( !empty($this->get_seceret_key()) && !empty($this->get_access_key()) && !empty($this->get_profile_id()) ) ? (bool) true : (bool) false;
	}

	/**
	 * Gets Identity Url.
	 *
	 * @return string
	 */
	public function get_identity_url() {
		switch ($this->get_environment()) {
			case 'sandbox':
				$value = self::SANDBOX_IDENTITY_URL;
				break;
			case 'live':
				$value = self::LIVE_IDENTITY_URL;
				break;
			default:
				break;
		}
		return $value;
	}

	/**
	 * Gets Environment.
	 *
	 * @return string
	 */
	public function get_environment() {
		return $this->gateway->get_option('environment');
	}

	
	public function get_api_url() {
		switch ($this->get_environment()) {
			case 'sandbox':
				$value = self::SANDBOX_URL;
				break;
			case 'live':
				$value = self::LIVE_URL;
				break;
			default:
				$value = null;
				break;
		}
		return $value;
	}
   
	
	/**
	 * Get Secret Key
	 *
	 * @return string
	 */
	public function get_seceret_key() {
		return $this->gateway->get_option('secret_key');
	}

	/**
	 * Get Access Key
	 *
	 * @return string
	 */
	public function get_access_key() {
		return $this->gateway->get_option('access_key');
	}

	/**
	 * Get Profile ID
	 *
	 * @return string
	 */
	public function get_profile_id() {
		return $this->gateway->get_option('profile_id');
	}


	/**
	 * Gets Order Request URL.
	 *
	 * @return string
	 */
	public function get_order_request_url() {
		return $this->get_api_url();
	}
}
