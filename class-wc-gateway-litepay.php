<?php

defined( 'ABSPATH' ) or exit;

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}

add_action( 'plugins_loaded', 'litepay_init');

	if (!class_exists('WC_Payment_Gateway')) {
		return;
	};

require_once __DIR__ . '/MerchantClient/MerchantClient.php';
/**
 * WC_Gateway_Litepay Class.
 */
class WC_Gateway_Litepay extends WC_Payment_Gateway {
	/** @var bool Whether or not logging is enabled */
	public static $log_enabled = true;
	/** @var WC_Logger Logger instance */
	public static $log = false;
	/** @var String */
	private static $callback_name = 'litepay_callback';
	/** @var MerchantClient */
	private $scClient;
	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id                 = 'litepay';
		$this->has_fields         = false;
		$this->order_button_text  = __( 'Pay with LitePay', 'woocommerce' );
		$this->method_title       = __( 'LitePay', 'woocommerce' );
		$this->method_description = __( 'Accept crypto payments trough Litepay.ch Merchant system.', 'woocommerce' );
		$this->supports           = array( 'products' );
                $this->title              = __( 'Pay with LitePay', 'woocommerce' );
                $this->description        = __( 'Pay with Crypto Currencies (Bitcoin / Litecoin)', 'woocommerce' );
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();
		// Define user set variables.
		$this->secretID 		= $this->get_option( 'secretID' );
		$this->vendorId 		= $this->get_option( 'vendorId' );
		$this->order_status     = $this->get_option( 'order_status' );
		$this->merchantApiUrl	= 'https://litepay.ch/p/';
		
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		
		if ( !$this->secretID ) {
			self::log( "Please add your secretID!" );

		} else if ( !$this->vendorId ) {
			self::log( "Please enter your secret passphrase!" );
		}  else {
			$this->scClient = NEW lp_MerchantClient(
				$this->merchantApiUrl,
				$this->secretID,
				$this->vendorId
			);
			add_action( 'woocommerce_api_' . self::$callback_name, array( &$this, 'callback' ) );

		}
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
	}
	/**
	 * Logging method.
	 * @param string $message
	 */
	public static function log( $message ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = new WC_Logger();
			}
			self::$log->add( 'litepay', $message );
		}
	}

	/**
	 * Get gateway icon.
	 * @return string
	 */
	public function get_icon() {
		$icon      = plugins_url( 'assets/images/litepay.png', __FILE__ );
		$icon_html = '<img src="' . esc_attr( $icon ) . '" width="130" alt="' . esc_attr__( 'LitePay logo', 'woocommerce' ) . '" />';
		return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
	}
        
	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	 
	     public function admin_options()
    {
      ?>
      <p><?php _e('<b><h3>LitePay</h3></b><br>Accept crypto through the Litepay.ch and receive payments in your wallet.. To register please use this url <a src="https://litepay.ch/merchant"/> https://litepay.ch/merchant/ </a> <br>
       Still have questions? Contact us via email: <a href="mailto:info@litepay.ch">info@litepay.ch</a><br>', 'woothemes'); ?></p>
      <table class="form-table">
        <?php $this->generate_settings_html(); ?>
      </table>
      <?php
    }
	
			public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable/Disable', 'woocommerce'),
					'type' => 'checkbox',
					'label' => __('Enable LitePay plugin', 'woocommerce'),
					'default' => 'yes'
				),
				'vendorId' => array(
					'title' => __('Vendor Id', 'woocommerce'),
					'type' => 'text',
					'desc_tip' => true,
					'description' => __('Vendor Id received when registering.', 'woocommerce'),
				),                            
				'secretID' => array(
					'title' => __('Secret Key', 'woocommerce'),
					'type' => 'text',
					'desc_tip' => true,
					'description' => __('This is a secret pass phrase that allows our system to confirm its you.', 'woocommerce')
				),		
				'order_status' => array(
					'title' => __('Order status'),
					'desc_tip' => true,
					'description' => __('Order status after payment has been received.', 'woocommerce'),
					'type' => 'select',
					'default' => 'pending',
					'options' => array(
						'pending' => __('pending', 'woocommerce'),
						'processing' => __('processing', 'woocommerce'),
						'completed' => __('completed', 'woocommerce'),
					),
				),

			);
		}
		
	/**
	 * Process the payment and return the result.
	 * @param  int $order_id
	 * @return array
	 */
	public function process_payment( $order_id ) {
		global $woocommerce;
		$order = wc_get_order( $order_id );
                if (!$order->get_prices_include_tax()) {
                    $total = $order->get_total();
                } else {
                    $total = $order->get_total() + $order->get_total_tax();
                }
                
                $currency = $order->get_currency();
                $email = $order->get_billing_email();                

		$request = $this->new_request( $order, $total, $currency, $email);
		$response = $this->scClient->lp_createOrder( $request );
                
                if ($response->lp_getStatus() == 'success') {
                    $order->update_status( 'on-hold', __( 'Waiting for LITEPAY callback payment', 'woocommerce' ) );
                    $woocommerce->cart->empty_cart();
                    return array(
                            'result'   => 'success',
                            'redirect' => $response->lp_getRedirectUrl()
                    );
                } elseif ($response->lp_getStatus() == 'error') {
                    $order->update_status( 'failed', __( 'Error message response from litepay.ch Merchant : '.$response->lp_getMessage(), 'woocommerce' ) );
                    $woocommerce->cart->empty_cart();
                    error_log( 'Error message response from litepay.ch Merchant : '.$response->lp_getMessage());
                } else {
                    error_log( 'Got no error message. No response from litepay.ch');
                }
	}


	private function new_request( $order, $total, $currency, $email ) {
		$callback = get_site_url( null, '?wc-api=' . self::$callback_name .'&secretid=' . $this->secretID .'&orderid='. $order->get_id());

		$successCallback = $this->get_return_url( $order );
		return new lp_CreateOrderRequest( $order->get_id() . '-' . $this->random_str( 5 ), $total, $currency, $email, $callback, $successCallback);
		
	}

	private function parse_order_id($order_id) {
		return explode('-', $order_id)[1];
	}
	private function random_str($length) {
		return substr(md5(rand(1, pow(2, 16))), 0, $length);
	}

	/**
	 * Used to process callbacks from Litepay
	 */

	public function callback() {
			
		if ( $this->enabled != 'yes' ) {
			return;
		}

		$callback = $_GET;
		if ( $callback ) {
                                if(isset($callback['orderid']) && is_numeric($callback['orderid']) && isset($callback['secretid']) ) { 
                                    if(isset($callback['value']) && is_numeric($callback['value'])){
                                      if(isset($callback['input_address']) && isset($callback['destination_address']) && isset($callback['transaction_hash']) && isset($callback['confirmations']) && is_numeric($callback['confirmations']) ){
                                          $order_id = $callback['orderid'];
                                          $order = wc_get_order($order_id);
                                          $amount = sanitize_text_field($callback['value']) / 100000000;
                                          $coin = sanitize_text_field($callback['coin']);
                                          $notes = "Received payment:
                                                   ".$coin." ". $amount ."
                                                   ".$coin." Generated address: ". sanitize_text_field($callback['input_address']) ." "
                                                  . "To: ". sanitize_text_field($callback['destination_address']) . " "
                                                  . "TxID: ". sanitize_text_field($callback['transaction_hash']) ." "
                                                  . "Confirmations: ". sanitize_text_field($callback['confirmations']) ." "
                                                  . "invoiceID: ". $order_id;

                                            if ($order) {
                                                $status = $order->get_status();
                                                    if ($status != $this->get_option( 'order_status' )) {
                                                            if ($this->secretID == sanitize_text_field($callback['secretid'])) {
                                                                    $order->update_status( $this->get_option( 'order_status' ), __( $notes, 'woocommerce' ) );  
                                                                    wc_reduce_stock_levels( $order->get_id() );
                                                            }
                                                            exit( "*ok*" );
                                                    } else { exit( "*ok*" ); } 
                                            } else self::log( "Order '{$order_id}' not found!" );
                                       } else self::log( "Issue with order data, values not set" );
                                    } else self::log( "Issue with order data, values not set" );
                                } else self::log( "Issue with order data, values not set" );
		} else self::log( "Sent callback is invalid" );	

	}
}
