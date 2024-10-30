<?php

class lp_CreateOrderResponse
{

	private $lp_orderId;
	private $lp_payAmount;
	private $lp_redirectUrl;
        private $lp_status;
        private $lp_message;

	/**
	 * @param $orderId
	 * @param $payAmount
	 * @param $redirectUrl
	 */
	function __construct($lp_orderId, $lp_payAmount, $lp_redirectUrl, $lp_status, $lp_message)
	{
		$this->lp_orderId = $lp_orderId;
		$this->lp_payAmount = $lp_payAmount;
                $this->lp_redirectUrl = $lp_redirectUrl;
                $this->lp_status = $lp_status;
                $this->lp_message = $lp_message;
                
	}

	/**
	 * @return String
	 */
	public function lp_getOrderId()
	{
		return $this->lp_orderId;
	}

	/**
	 * @return float
	 */
	public function lp_getPayAmount()
	{
		return $this->lp_payAmount;
	}
        
	/**
	 * @return String
	 */
	public function lp_getRedirectUrl()
	{
		return $this->lp_redirectUrl;
	}

 	/**
	 * @return String
	 */
	public function lp_getStatus()
	{
		return $this->lp_status;
	}
        
   	/**
	 * @return String
	 */
	public function lp_getMessage()
	{
		return $this->lp_message;
	}       

}