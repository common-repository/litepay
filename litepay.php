<?php
/*
Plugin Name: LitePay Merchant
Plugin URI:  https://litepay.ch/
Description: This module integrates LitePay Merchant API with Wordpress's Woocommerce plugin to accept Bitcoin/Litecoin payments.
Version:     0.2
Author:      Litepay
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

define( 'LP_REQUIRED_PHP_VERSION', '5.3' );
/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
function litepay_requirements_met() {
	global $wp_version;
	if ( version_compare( PHP_VERSION, LP_REQUIRED_PHP_VERSION, '<' ) ) {
		return false;
	}
	return true;
}

add_action( 'plugins_loaded', 'init_litepay_plugin' );

function init_litepay_plugin() {
	if ( litepay_requirements_met() ) {
		require_once( __DIR__ . '/class-wc-gateway-litepay.php' );

		if ( class_exists( 'WC_Gateway_Litepay' ) ) {
			add_filter( 'woocommerce_payment_gateways', 'litepay_gateway_class' );
		}
	} else {
		// TODO make message more informative
		trigger_error('LitePay plugin\'s requirements not met. Update you Wordpress or PHP');
	}
}

function litepay_gateway_class( $methods ) {
	$methods[] = 'WC_Gateway_Litepay'; 
	return $methods;
}