<?php

/**
 * LitePay Merchant v0.2 API PHP client
 */
include_once('messages/CreateOrderRequest.php');
include_once('messages/CreateOrderResponse.php');

class lp_MerchantClient
{

	private $merchantApiUrl;
	private $secretID;
	private $vendorId;
	private $debug;

	/**
	 * @param $merchantApiUrl
	 * @param $secretID
	 * @param $apiId
	 * @param bool $debug
	 */
	function __construct($merchantApiUrl, $secretID, $vendorId, $debug = false)
	{
		$this->merchantApiUrl = $merchantApiUrl;
		$this->secretID = $secretID;
		$this->vendorId = $vendorId;
		$this->debug = $debug;
	}

	##########################################################

	public function lp_createOrder(lp_CreateOrderRequest $request)
	{
		$payload = array(
			'vendor' => $this->vendorId,
			'secret' => $this->secretID,
			'invoice' => $request->lp_getOrderId(),
			'price' => $request->lp_getPayAmount(),
                         'currency' => $request->lp_getCurrency(),
                         'email' => urlencode($request->lp_getEmail()),
			'returnUrl' => $request->lp_getSuccessUrl(),
			'callbackUrl' => $request->lp_getCallbackUrl()
		);

		//send request
		if (!$this->debug) {
			$response = wp_remote_post($this->merchantApiUrl,array(
		    'method'      => 'POST',
		    'timeout'     => 5,
		    'httpversion' => '1.0',
		    'headers'     => array(),
		    'body'        => $payload,
		    )
		);
			if (!is_wp_error($response)) {
                            $body = json_decode(wp_remote_retrieve_body($response));

                            if ($body) {
                                if ($body->status === 'error') {
                                return new lp_CreateOrderResponse($request->lp_getOrderId(), $request->lp_getPayAmount(), '', $body->status, $body->message); 
                                } else {
                                return new lp_CreateOrderResponse($request->lp_getOrderId(), $request->lp_getPayAmount(), $body->url, $body->status, '');                                     
                                }
                            }
                    }
		} else {
			/* no debug */
		}
	}

}
