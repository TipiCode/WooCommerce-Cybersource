<?php

/**
 * Settings for CyberSource Online Gateway.
 *
 * @package Abzer
 */
defined('ABSPATH') || exit;

return array(
	'enabled' => array(
		'title' => __('Enable/Disable', 'woocommerce'),
		'label' => __('Enable CyberSource Payment Gateway', 'woocommerce'),
		'type' => 'checkbox',
		'default' => 'no',
	),
	'title' => array(
		'title' => __('Title', 'woocommerce'),
		'type' => 'text',
		'description' => __('The title which the user sees during checkout.', 'woocommerce'),
		'default' => __('CyberSource Payment Gateway', 'woocommerce'),
	),
	'description' => array(
		'title' => __('Description', 'woocommerce'),
		'type' => 'textarea',
		'css' => 'width: 400px;height:60px;',
		'description' => __('The description which the user sees during checkout.', 'woocommerce'),
		'default' => __('You will be redirected to payment gateway.', 'woocommerce'),
	),
	'environment' => array(
		'title' => __('Environment', 'woocommerce'),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'options' => array(
			'sandbox' => __('Sandbox', 'woocommerce'),
			'live' => __('Live', 'woocommerce'),
		),
		'default' => 'sandbox',
	),
	
	'order_status' => array(
		'title' => __('Status of new order', 'woocommerce'),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'options' => array(
			'cybersource_pending' => __('CyberSource Pending', 'woocommerce'),
		),
		'default' => 'cybersource_pending',
	),
	'profile_id' => array(
		'title' => __('Profile ID', 'woocommerce'),
		'type' => 'text',
	),
	'access_key' => array(
		'title' => __('Access Key', 'woocommerce'),
		'type' => 'text',
	),
	'secret_key' => array(
		'title' => __('Secret Key', 'woocommerce'),
		'type' => 'textarea',
		'css' => 'width: 400px;height:50px;',
	),
	'debug' => array(
		'title' => __('Debug Log', 'woocommerce'),
		'type' => 'checkbox',
		'label' => __('Enable logging', 'woocommerce'),
		/* translators: %s: file path */
		'description' => sprintf(__('Log file will be %s', 'woocommerce'), '<code>' . WC_Log_Handler_File::get_log_file_path('cybersource') . '</code>'),
		'default' => 'no',
	),
	'transaction_response_url' => array(
		'type' => 'hidden',
		'title' => __('Response URL *', 'woocommerce'),
		'description' => "<b><u>NOTE:</u> Please add the below URL's in the CyberSource Portal (Customer Response)</b></br><br>
                         Transaction Response URL: <code>" . site_url() . '/wc-api/cybersourceonline </code> </br>
                         Custom Cancel Response URL:  <code>' . site_url() . '/wc-api/cybersourceonline </code> </br>',
	),
);
