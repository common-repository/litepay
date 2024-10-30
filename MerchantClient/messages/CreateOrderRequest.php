<?php

class lp_CreateOrderRequest
{
	private $lp_orderId;
	private $lp_payAmount;
        private $lp_currency;
        private $lp_email;
	private $lp_callbackUrl;
	private $lp_successUrl;


	/**
	 * @param $orderId
	 * @param $callbackUrl
	 * @param $successUrl
	 * @param $failureUrl
	 */
	function __construct($lp_orderId, $lp_payAmount, $lp_currency, $lp_email, $lp_callbackUrl, $lp_successUrl)
	{
		$this->lp_orderId = $lp_orderId;
		$this->lp_payAmount = $lp_payAmount;
                $this->lp_currency = $lp_currency;
                $this->lp_email = $lp_email;
		$this->lp_callbackUrl = $lp_callbackUrl;
		$this->lp_successUrl = $lp_successUrl;

	}

	/**
	 * @return string
	 */
	public function lp_getPayAmount()
	{
		return $this->lp_payAmount == null ? '' : $this->lp_payAmount;
	}

	/**
	 * @return string
	 */
	public function lp_getOrderId()
	{
		return $this->lp_orderId == null ? '' : $this->lp_orderId;
	}
	
	/**
	 * @return string
	 */
	public function lp_getCallbackUrl()
	{
		return $this->lp_callbackUrl == null ? '' : $this->lp_callbackUrl;
	}

	/**
	 * @return string
	 */
	public function lp_getSuccessUrl()
	{
		return $this->lp_successUrl == null ? '' : $this->lp_successUrl;
	}
	/**
	 * @return string
	 */
	public function lp_getCurrency()
	{
		return $this->lp_currency == null ? '' : $this->lp_currency;
	}
	/**
	 * @return string
	 */
	public function lp_getEmail()
	{
		return $this->lp_email == null ? '' : $this->lp_email;
	}


}