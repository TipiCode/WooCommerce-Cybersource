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
 * Cybersource_Gateway_Payment class.
 */
class Cybersource_Gateway_Payment {


	/**
	 * CyberSource Online states
	 */
	const CYBERSOURCE_STARTED = 'STARTED';
	const CYBERSOURCE_ACCEPT = 'ACCEPT';
	const CYBERSOURCE_CANCEL = 'CANCEL';
	const CYBERSOURCE_DECLINE = 'DECLINE';

	/**
	 * Order Status Variable
	 *
	 * @var string Order Status.
	 */
	protected $order_status;

	/**
	 * CyberSource State Variable
	 *
	 * @var string CyberSource Online state
	 */
	protected $cybersource_state;

	/**
	 * Gateway
	 *
	 * @var Cybersource_Gateway $gateway
	 */
	protected $gateway;

	/**
	 * Status
	 *
	 * @var string status
	 */
	protected $status_set_to;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->order_status = include dirname(__FILE__) . '/order-status-cybersource.php';
		$this->gateway = Cybersource_Gateway::get_instance();
	}

	/**
	 * Execute action.
	 *
	 * @param string $order_ref Order reference.
	 */
	//public function execute(string $order_ref) {
	public function execute( $params) {
		global $woocommerce;
		$log['path'] = __METHOD__;
		$redirect_url = $woocommerce->cart->get_checkout_url();

		$log['is_valid_ref'] = true;

		$order_item = reset($this->fetch_order_by_ref($params['req_reference_number']));
		$order = $this->process_order($params, $order_item);
		$redirect_url = $order->get_checkout_order_received_url();

		$log['redirected_to'] = $redirect_url;
		$this->gateway->debug($log);
		wp_safe_redirect($redirect_url);
		exit();
	}

	/**
	 * Process Order.
	 *
	 * @param  array  $payment_result Payment Results.
	 * @param  object $order_item Order Item.
	 * @return object
	 */
	public function process_order( $param, $order_item) {

		include_once dirname(__FILE__) . '/config/class-cybersource-gateway-config.php';
		include_once dirname(__FILE__) . '/validator/class-cybersource-gateway-validator-response.php';

		$gateway_obj = new Cybersource_Gateway();
		$config = new Cybersource_Gateway_Config($gateway_obj);
		$validator = new Cybersource_Gateway_Validator_Response();

		$data_table = array();
		$log['path'] = __METHOD__;
		if ($order_item->order_id) {
			$captured_amt = 0;
			$order = wc_get_order($order_item->order_id);

			if ($order && $order->get_id()) {
				$state = $params['decision'];
								$request = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				foreach ($request as $name => $value) {
					$params[$name] = $value;
				}
				$generated_sign = $validator->sign($params, $config->get_seceret_key());
				if (strcmp($params['signature'], $generated_sign) != 0) {
					$order->update_status($this->order_status[5]['status'], 'The transaction has been failed.');
					$order->update_status('failed');
					$this->status_set_to = $this->order_status[5]['status'];
				} else {
					$this->status_set_to = $params['decision'];
					switch ($params['decision']) {
						case self::CYBERSOURCE_ACCEPT:
							$this->order_transaction_mode($order, $params);
							break;
						case self::CYBERSOURCE_CANCEL:
							$order->update_status($this->order_status[5]['status'], 'The transaction has been failed.');
							$order->update_status('failed');
							$this->status_set_to = $this->order_status[5]['status'];
							$message = $params['message'];
							$order->add_order_note($message);
							$this->customer_failed_order_email($order, 'cancelled');
							break;
						case self::CYBERSOURCE_DECLINE:
							$order->update_status($this->order_status[6]['status'], 'The transaction has been failed.');
							$order->update_status('failed');
							$this->status_set_to = $this->order_status[6]['status'];
							$message = 'Unfortunately your order cannot be processed/Declined | ' . $params['message'];
							$order->add_order_note($message);
							$this->customer_failed_order_email($order, 'failed');
							break;
						default:
							$this->status_set_to = $this->order_status[0]['status'];
							break;
					}
					$data_table['status'] = substr($this->status_set_to, 3);
					$log['actions']['status_set_to'] = $data_table['status'];
					$data_table['transaction_id'] = $param['transaction_id'];
					$data_table['state'] = $params['decision'];
					$log['actions']['state'] = $params['decision'];
					$data_table['captured_amt'] = $param['req_amount'];

					$this->update_table($data_table, $order_item->cid);
					$this->gateway->debug($log);
					return $order;
				}
			} else {
				return new WP_Error('cybersource_error', 'Order Not Found');
			}
		}
	}
	
	/**
	 * Order Mail Cancelled/Declined/Failed
	 *
	 * @param type $order
	 */
	public function customer_failed_order_email( $order, $mode) {
		$emailer = new WC_Emails();
		if ('cancelled'  == $mode) {
			$email = $emailer->emails['WC_Email_Cancelled_Order'];
		} else {
			$email = $emailer->emails['WC_Email_Failed_Order'];
		}
		if (!is_object($order)) {
			$order = wc_get_order(absint($order));
		}
		$email->trigger($order->get_id(), $order);
	}
	
	/**
	 * Order transaction mode
	 *
	 * @param type $order
	 * @param type $params
	 * @return type
	 */
	public function order_transaction_mode( $order, $params) {
		$log['Path'] = __METHOD__;
		//Sale
		if ('sale' == $params['req_transaction_type']) {
			$transaction_id = $params['transaction_id'];

			$message = 'Captured Amount: ' . $order->get_formatted_order_total() . ' | Transaction ID: ' . $transaction_id;
			$order->payment_complete($transaction_id);
			$order->update_status($this->order_status[1]['status']);
			$this->status_set_to = $this->order_status[1]['status'];

			$order->add_order_note($message);
			$log['msg'] = $message;
			$emailer = new WC_Emails();
			$emailer->customer_invoice($order);
			$log['email_sent'] = true;
			$this->gateway->debug($log);
			return array($order->get_total(), $transaction_id);
		}
	}

	/**
	 * Fetch Order details.
	 *
	 * @param  string $order_ref Order Ref.
	 * @return object
	 */
	public function fetch_order_by_ref( $order_ref) {
		global $wpdb;
		return $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'cybersource WHERE `reference`=%s ORDER BY `cid` DESC', $order_ref)); // db call ok; no-cache ok.
	}

	/**
	 * Update Table.
	 *
	 * @param  array $data Data.
	 * @param  int   $cid CID.
	 * @return bool true
	 */
	public function update_table( $data, $cid) {
		global $wpdb;
		return $wpdb->update(CYBERSOURCE_TABLE, $data, array('cid' => $cid)); // db call ok; no-cache ok.
	}
}
