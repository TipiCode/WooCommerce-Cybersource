<?php
/**
 * Plugin Name: Cybersource Payment Gateway
 * Plugin URI: 
 * Description: Cybersource Payment gateway extension for WooCommerce.
 * Version:     1.0.1
 * Requires PHP: 7.2
 * Author:      tipi(code)
 * Author URI: https://codingtipi.com
 * License:     MIT
 * WC requires at least: 5.8.0
 * WC tested up to: 6.1.0
 *
 * @package CybersourceGateway
*/

/**
 * Function to register order statuses
 */
function Register_Cybersource_Order_status() {
	$statuses = include 'gateway/order-status-cybersource.php';
	foreach ($statuses as $status) {
		$label = $status['label'];
		register_post_status(
			$status['status'],
			array(
			'label' => $label,
			'public' => true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: count */
			'label_count' => array(
				$label . ' <span class="count">(%s)</span>', // NOSONAR.
				$label . ' <span class="count">(%s)</span>' // NOSONAR.
			),
				)
		);
	}
}

add_action('init', 'Register_Cybersource_Order_status');

/**
 * Function to register woocommerce order statuses
 *
 * @param array $order_statuses Order Statuses.
 */
function Cybersource_Order_status( $order_statuses) {
	$statuses = include 'gateway/order-status-cybersource.php';
	$id = get_the_ID();
	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
	if ('shop_order' === get_post_type() && $id && isset($action) && 'edit' === $action) {
		$order = wc_get_order($id);
		if ($order) {
			$current_status = $order->get_status();
			foreach ($statuses as $status) {
				if ('wc-' . $current_status === $status['status']) {
					$order_statuses[$status['status']] = $status['label'];
				}
			}
		}
	} else {
		foreach ($statuses as $status) {
			$order_statuses[$status['status']] = $status['label'];
		}
	}
	return $order_statuses;
}

add_filter('wc_order_statuses', 'Cybersource_Order_status');

global $wpdb;
define('CYBERSOURCE_TABLE', $wpdb->prefix . 'cybersource');

/**
 * Function to create table while activate the plugin
 */
function Cybersource_Table_install() {
	$sql = 'CREATE TABLE IF NOT EXISTS `' . CYBERSOURCE_TABLE . '` (
             `cid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "CyberSource Id",
             `order_id` varchar(55) NOT NULL COMMENT "Order Id",
             `amount` decimal(12,4) UNSIGNED NOT NULL COMMENT "Amount",
             `currency` varchar(3) NOT NULL COMMENT "Currency",
             `reference` text NOT NULL COMMENT "Reference",
             `action` varchar(20) NOT NULL COMMENT "Action",
             `state` varchar(20) NOT NULL COMMENT "State",
             `status` varchar(50) NOT NULL COMMENT "Status",
             `captured_amt` decimal(12,4) UNSIGNED NOT NULL COMMENT "Captured Amount",
             `transaction_id` text NOT NULL COMMENT "Transaction Id",
             `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Created At",
             PRIMARY KEY (`cid`),
             UNIQUE KEY `CYBERSOURCE_ONLINE_ORDER_ID` (`order_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT="CyberSource Online order table";';

	include_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta($sql);
}

register_activation_hook(__FILE__, 'Cybersource_Table_install');

/**
 * Function to add action links
 *
 * @param $links Links.
 */
function Plugin_Action_Links_cybersource( $links) {
	$plugin_links = array(
		'<a href="admin.php?page=wc-settings&tab=checkout&section=cybersource">' . esc_html__('Settings', 'woocommerce') . '</a>',
		'<a href="admin.php?page=cybersource-report">' . esc_html__('Report', 'woocommerce') . '</a>',
	);
	return array_merge($plugin_links, $links);
}

/**
 * Filter to add action links
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'Plugin_Action_Links_cybersource');

/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action('plugins_loaded', 'Cybersource_Init_Gateway_class');

/**
 * Function to register admin menu
 */
function Register_Cybersource_Report_page() {
	$hook = add_submenu_page('woocommerce', 'CyberSource Report', 'CyberSource Report', 'manage_options', 'cybersource-report', 'Cybersource_Page_callback');
	add_action("load-$hook", 'Add_Options_cybersource');
}

/**
 * Function to add screen options
 */
function Add_Options_cybersource() {
	global $cybersource_table;
	$option = 'per_page';
	$args = array(
		'label' => 'No. of records',
		'default' => 10,
		'option' => 'records_per_page',
	);
	add_screen_option($option, $args);
	include_once 'gateway/class-cybersource-gateway-report.php';
	$cybersource_table = new CybersourceGatewayReport();
}

add_action('admin_menu', 'Register_Cybersource_Report_page');

/**
 * Function for search box
 */
function Cybersource_Page_callback() {
	global $cybersource_table;
	echo '</pre><div class="wrap"><h2>CyberSource Report</h2>';
	$cybersource_table->prepare_items();
	?>
	<form method="post">
		<input type="hidden" name="page" value="cybersource_list_table">
		<?php
		$cybersource_table->search_box('search', 'cybersource_search_id');
		$cybersource_table->display();
		echo '</form></div>';
}

	/**
	 * Print admin errors
	 */
function Print_Errors_cybersource() {
	settings_errors('cybersource_error');
}

/**
 * Initialise the gateway class
 */
function Cybersource_Init_Gateway_class() {
	if (!class_exists('WC_Payment_Gateway')) {
		return;
	}
	include_once 'gateway/class-cybersource-gateway.php';
	Cybersource_Gateway::get_instance()->init_hooks();
}

// define the woocommerce_gateway_icon callback
function filter_woocommerce_cybersource_icon( $icon, $this_id ) {	
	if($this_id == "cybersource") {
		$icon = "<img style='max-width: 100px;' src='".plugins_url('gateway/assets/images/visaMaster.png', __FILE__)."' alt='cybersource icon' />";
	}
	return $icon;
}
apply_filters( 'woocommerce_gateway_icon', 'filter_woocommerce_cybersource_icon', 10, 2 );

/**
 * Add to woocommorce gateway list
 *
 * @param array $gateways Gateways.
 */
function Cybersource_Add_Gateway_class( $gateways) {
	$gateways[] = 'cybersource_gateway';
	return $gateways;
}

add_filter('woocommerce_payment_gateways', 'Cybersource_Add_Gateway_class');


	
