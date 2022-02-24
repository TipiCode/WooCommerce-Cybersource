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
 * Cybersource_Gateway_Http_Abstract class.
 */
abstract class Cybersource_Gateway_Http_Abstract {


	/**
	 * Cybersource Order status.
	 *
	 * @var array $order_status
	 */
	protected $order_status;

	/**
	 * Gateway Object
	 *
	 * @var Cybersource_Gateway $gateway
	 */
	protected $gateway;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->order_status = include dirname(__FILE__) . '/../order-status-cybersource.php';
		$this->gateway = Cybersource_Gateway::get_instance();
	}

	/**
	 * Places request to gateway.
	 *
	 * @param  TransferInterface $transfer_object Transafer Factory.
	 * @return array|null
	 * @throws Exception Exception.
	 */
	public function place_request( $requestArr) {
		$this->order_status = include dirname(__FILE__) . '/../order-status-cybersource.php';
		$log['path'] = __METHOD__;
		try {
			$log['response'] = $requestArr;
			$result = $this->post_process($requestArr);
			return $result;
		} catch (Exception $e) {
			return new WP_Error('error', $e->getMessage());
		} finally {
			$this->gateway->debug($log);
		}
	}

	/**
	 * Processing of API request body
	 *
	 * @param  array $data Data.
	 * @return string|array
	 */
	abstract protected function pre_process( array $data);

	/**
	 * Processing of API response
	 *
	 * @param  array $response Response.
	 * @return array|null
	 */
	abstract protected function post_process( $response);
}
