<?php
/**
 * Payment Gateway class for CyberSource Online
 *
 * @package Abzer
 */
if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__FILE__) . '/config/class-cybersource-gateway-config.php';
require_once dirname(__FILE__) . '/http/class-cybersource-gateway-http-abstract.php';

/**
 * Cybersource_Gateway class.
 */
class Cybersource_Gateway extends WC_Payment_Gateway {


	/**
	 * Whether or not logging is enabled
	 *
	 * @var bool
	 */
	public static $log_enabled = false;

	/**
	 * Logger instance
	 *
	 * @var WC_Logger
	 */
	public static $log = false;

	/**
	 * Singleton instance
	 *
	 * @var Cybersource_Gateway
	 */
	private static $instance;

	/**
	 * Notice variable
	 *
	 * @var string
	 */
	private $message;

	/**
	 * Get instance of Cybersource_Gateway
	 *
	 * Returns a new instance of self, if it does not already exist.
	 *
	 */
	public static function get_instance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {

		$this->id = 'cybersource';
		$this->cybersource_icon = $this->get_option('cybersource_icon');
		// $this->icon = ( !empty($this->cybersource_icon) ) ? $this->cybersource_icon : apply_filters('cybersource_icon', plugins_url('assets/images/cybersource.png', __FILE__)); // displayed on checkout page near your gateway name.
		$this->has_fields = false; // in case you need a custom credit card form.
		$this->method_title = 'CyberSource Payment Gateway';
		$this->method_description = 'Payment Gateway from Abzer';
		// will be displayed on the options page
		// gateways can support subscriptions, saved payment methods.
		$this->supports = array(
			'products',
		);

		// Method with all the options fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		$this->title = $this->get_option('title');
		$this->description = $this->get_option('description');
		$this->enabled = $this->get_option('enabled');
		$this->environment = $this->get_option('environment');
		$this->order_status = $this->get_option('order_status');
		$this->profile_id = $this->get_option('profile_id');
		$this->access_key = $this->get_option('access_key');
		$this->secret_key = $this->get_option('secret_key');
		$this->debug = 'yes' === $this->get_option('debug', 'no');
		self::$log_enabled = $this->debug;
	}

	/**
	 * Plug-in options
	 */
	public function init_form_fields() {
		$this->form_fields = include 'settings-cybersource.php';
	}

	/**
	 * Initilize module hooks
	 */
	public function init_hooks() {
		add_action('woocommerce_receipt_cybersource', array($this, 'process_payment_page'));

		add_action('woocommerce_api_cybersourceonline', array($this, 'update_cybersource_response'));
		if (is_admin()) {
			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
			add_action('add_meta_boxes', array($this, 'cybersource_online_meta_boxes'));
			add_action('save_post', array($this, 'cybersource_online_actions'));
		}
	}

	/**
	 * Add notice query variable
	 *
	 * @param  string $location Location.
	 * @return string
	 */
	public function add_notice_query_var( $location) {
		remove_filter('redirect_post_location', array($this, 'add_notice_query_var'), 99);
		return add_query_arg(array('message' => false), $location);
	}

	/**
	 * Processing order
	 *
	 * @global object $woocommerce
	 * @param  int $order_id Order ID.
	 * @return array|null
	 */
	public function process_payment( $order_id) {

		$order = wc_get_order($order_id);
		$pay_url = add_query_arg(array(
			'key' => $order->get_order_key(),
			'pay_for_order' => false,
				), $order->get_checkout_payment_url());

		return array(
			'result' => 'success',
			'redirect' => $pay_url
		);
	}

	/**
	 * Payment processing page
	 *
	 * @param int $order_id
	 */
	public function process_payment_page( $order_id) {
		global $woocommerce;
		$log['path'] = __METHOD__;
		$log['is_configured'] = false;
		$order = wc_get_order($order_id);

		include_once dirname(__FILE__) . '/request/class-cybersource-gateway-request-sale.php';
		include_once dirname(__FILE__) . '/http/class-cybersource-gateway-http-sale.php';
		include_once dirname(__FILE__) . '/validator/class-cybersource-gateway-validator-response.php';

		$order = wc_get_order($order_id);
		$config = new Cybersource_Gateway_Config($this);

		if ($config->is_complete()) {
			$log['is_configured'] = true;

			$request_class = new Cybersource_Gateway_Request_Sale($config);
			$request_http = new Cybersource_Gateway_Http_Sale();
			
			$requestArr = $request_class->build($order);
			$validator = new Cybersource_Gateway_Validator_Response();

			$requestArr['sign'] = $validator->sign($requestArr, $config->gateway->settings['secret_key']);
			$requestArr['url'] = $config->get_api_url();
			$request_http->place_request($requestArr);

			$this->save_data($order);
			$woocommerce->cart->empty_cart();
			$log['action'] = 'Redirecting to payment gateway...';
			$this->debug($log);
			?>
			<p class="loading-payment-text">
				<?php echo 'Please do not refresh the page, the page will be redirected to payment gateway'; ?>
				<style>
					.lds-ring {
					  margin: 0 auto;
					  position: relative;
					  width: 80px;
					  height: 80px;
					}
					.lds-ring div {
					  box-sizing: border-box;
					  display: block;
					  position: absolute;
					  width: 64px;
					  height: 64px;
					  margin: 8px;
					  border: 8px solid #fff;
					  border-radius: 50%;
					  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
					  border-color: #000 #00000059 #0000000a transparent;
					}
					.lds-ring div:nth-child(1) {
					  animation-delay: -0.45s;
					}
					.lds-ring div:nth-child(2) {
					  animation-delay: -0.3s;
					}
					.lds-ring div:nth-child(3) {
					  animation-delay: -0.15s;
					}
					@keyframes lds-ring {
					  0% {transform: rotate(0deg);}
					  100% {transform: rotate(360deg);}
					}
				</style>
				<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
			</p>
			<form action="<?php echo esc_attr($requestArr['url']); ?>" method="post" name="cybersource_online_payment" id="cybersource_payment_form">
				<input type="hidden" value="<?php echo esc_attr($requestArr['transaction_type']); ?>" id="transaction_type" name="transaction_type"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['amount']); ?>" id="amount" name="amount"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['currency']); ?>" id="currency" name="currency"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['bill_to_forename']); ?>" id="bill_to_forename" name="bill_to_forename"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['bill_to_surname']); ?>" id="bill_to_surname" name="bill_to_surname"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['bill_to_email']); ?>" id="bill_to_email" name="bill_to_email"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['bill_to_address_line1']); ?>" id="bill_to_address_line1" name="bill_to_address_line1"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['bill_to_address_line2']); ?>" id="bill_to_address_line2" name="bill_to_address_line2"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['bill_to_address_city']); ?>" id="bill_to_address_city" name="bill_to_address_city"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['bill_to_address_postal_code']); ?>" id="bill_to_address_postal_code" name="bill_to_address_postal_code"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['bill_to_address_state']); ?>" id="bill_to_address_state" name="bill_to_address_state"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['bill_to_address_country']); ?>" id="bill_to_address_country" name="bill_to_address_country"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['reference_number']); ?>" id="reference_number" name="reference_number"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['signed_date_time']); ?>" id="signed_date_time" name="signed_date_time"/>
								<input type="hidden" value="<?php echo esc_attr($requestArr['locale']); ?>" id="locale" name="locale"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['customer_ip_address']); ?>" id="customer_ip_address" name="customer_ip_address"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['transaction_uuid']); ?>" id="transaction_uuid" name="transaction_uuid"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['access_key']); ?>" id="access_key" name="access_key"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['profile_id']); ?>" id="profile_id" name="profile_id"/>
				<input type="hidden" name="unsigned_field_names" value="">

				<input type="hidden" value="<?php echo esc_attr($requestArr['signed_field_names']); ?>" id="signed_field_names" name="signed_field_names"/>
				<input type="hidden" value="<?php echo esc_attr($requestArr['sign']); ?>" id="signature" name="signature"/>
			</form>

			<script type="text/javascript">
				setTimeout(function () {
					document.getElementById('cybersource_payment_form').submit();
				}, 2000);
			</script>
			<?php
		} else {
			wc_add_notice('Error! Invalid configuration.', 'error');
			return false;
		}
	}

	/**
	 * Save data
	 *
	 * @global object $wpdb
	 * @global object $wp_session
	 * @param  object $order Order.
	 */
	public function save_data( $order) {
		global $wpdb;
		global $wp_session;
		$wpdb->replace(
			CYBERSOURCE_TABLE,
			array_merge(
				$wp_session['cybersource'],
				array(
					'order_id' => $order->get_id(),
					'currency' => $order->get_currency(),
					'amount' => $order->get_total(),
						)
			)
		); // db call ok; no-cache ok.
	}

	/**
	 * Update data
	 *
	 * @global object $wpdb
	 * @param  array $data Data.
	 * @param  array $where Where condition.
	 */
	public function update_data( array $data, array $where) {
		global $wpdb;
		$wpdb->update(CYBERSOURCE_TABLE, $data, $where); // db call ok; no-cache ok.
	}

	/**
	 * Logging method.
	 *
	 * @param string $message Log message.
	 * @param string $level   Optional. Default 'info'. Possible values:
	 *                        emergency|alert|critical|error|warning|notice|info|debug.
	 */
	public static function log( $message, $level = 'debug') {
		if (self::$log_enabled) {
			if (empty(self::$log)) {
				self::$log = wc_get_logger();
			}
			self::$log->log($level, $message . "\r\n", array('source' => 'cybersource'));
		}
	}

	/**
	 * Debug method.
	 *
	 * @param array $message Log message.
	 */
	public function debug( array $message) {
		self::log(wp_json_encode($message), 'debug');
	}

	/**
	 * Processes and saves options.
	 * If there is an error thrown, will continue to save and validate fields, but will leave the error field out.
	 *
	 * @return bool was anything saved?
	 */
	public function process_admin_options() {
		$saved = parent::process_admin_options();

		if ('yes' === $this->get_option('enabled', 'no')) {
			if (empty($this->get_option('profile_id'))) {
				add_settings_error('cybersource_error', esc_attr('settings_updated'), __('Invalid Profile ID'), 'error');
			}
			if (empty($this->get_option('access_key'))) {
				add_settings_error('cybersource_error', esc_attr('settings_updated'), __('Invalid Access Key'), 'error');
			}
			if (empty($this->get_option('secret_key'))) {
				add_settings_error('cybersource_error', esc_attr('settings_updated'), __('Invalid Secret Key'), 'error');
			}
			add_action('admin_notices', 'Print_Errors_cybersource');
		}
		if ('yes' !== $this->get_option('debug', 'no')) {
			if (empty(self::$log)) {
				self::$log = wc_get_logger();
			}
			self::$log->clear('cybersource');
		}
		return $saved;
	}

	/**
	 * Catch response from CyberSource Online
	 */
	public function update_cybersource_response() {
				$request = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		foreach ($request as $name => $value) {
			$params[$name] = $value;
		}
		include plugin_dir_path(__FILE__) . '/class-cybersource-gateway-payment.php';
		$payment = new Cybersource_Gateway_Payment();
		$payment->execute($params);
		die;
	}

	/**
	 * Fetch Order details.
	 *
	 * @param  int $order_id Order ID.
	 * @return object
	 */
	public function fetch_order( $order_id) {
		global $wpdb;
		return $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'cybersource WHERE `order_id`=%d', $order_id)); // db call ok; no-cache ok.
	}

	/**
	 * CyberSource Online Meta Boxes
	 */
	public function cybersource_online_meta_boxes() {
		global $post;
		$order_id = $post->ID;
		$payment_method = get_post_meta($order_id, '_payment_method', true);
		if ($this->id === $payment_method) {
			add_meta_box(
				'cybersource-payment-actions',
				__('CyberSource Payment Gateway', 'woocommerce'),
				array($this, 'cybersource_online_meta_box_payment'),
				'shop_order',
				'side',
				'high'
			);
		}
	}

	/**
	 * Generate the CyberSource Online payment meta box and echos the HTML
	 */
	public function cybersource_online_meta_box_payment() {
		global $post;
		$order_id = $post->ID;
		$order = wc_get_order($order_id);

		if (!empty($order)) {
			$order_item = $this->fetch_order($order_id);

			try {
				$curency_code = $order_item->currency . ' ';

				echo '<table border="0" cellspacing="10">';
				echo '<tr>';
				echo '<td>' . esc_html('State:', 'woocommerce') . '</td>';
				echo '<td>' . esc_html($order_item->state, 'woocommerce') . '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>' . esc_html('TransactionID:', 'woocommerce') . '</td>';
				echo '<td>' . esc_html($order_item->transaction_id, 'woocommerce') . '</td>';
				echo '</tr>';

				echo '<tr>';
				echo '<td>' . esc_html('Captured:', 'woocommerce') . '</td>';
				echo '<td>' . esc_html($curency_code . number_format($order_item->captured_amt, 2), 'woocommerce') . '</td>';
				echo '</tr>';

				echo '</table>';
			} catch (Exception $e) {
				echo esc_html($e->getMessage(), 'woocommerce');
			}
		}
	}

	/**
	 * Handle actions on order page
	 *
	 * @param  int $post_id Post ID.
	 * @return null
	 */
	public function cybersource_online_actions( $post_id) {
		$this->message = '';
		WC_Admin_Notices::remove_all_notices();
		$order_item = $this->fetch_order($post_id);
		$order = wc_get_order($post_id);
		$this->message = 'Order #' . $post_id . ' not found.';
		WC_Admin_Notices::add_custom_notice('cybersource', $this->message);
		add_filter('redirect_post_location', array($this, 'add_notice_query_var'), 99);
		return true;
	}
}
