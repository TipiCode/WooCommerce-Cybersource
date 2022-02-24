<?php

/**
 * Order statuses for CyberSource Online Gateway.
 *
 * @package Abzer
 */
defined('ABSPATH') || exit;

return array(
	array(
		'status' => 'wc-pending',
		'label' => 'CyberSource Pending',
	),
	array(
		'status' => 'wc-completed',
		'label' => 'CyberSource Complete',
	),
	array(
		'status' => 'wc-error',
		'label' => 'CyberSource Error',
	),
	array(
		'status' => 'wc-reject',
		'label' => 'CyberSource Reject',
	),
	array(
		'status' => 'wc-review',
		'label' => 'CyberSource Review',
	),
	array(
		'status' => 'wc-failed',
		'label' => 'CyberSource Failed',
	),
	array(
		'status' => 'wc-declined',
		'label' => 'CyberSource Declined',
	),
);
