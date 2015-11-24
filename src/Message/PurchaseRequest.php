<?php

namespace Omnipay\Bardo\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Pin Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
	protected $liveEndpoint = 'https://bardo.com/pay/payment.php';
	protected $testEndpoint = 'https://bardo.com/pay/payment.php';

	public function getShopId()
	{
		return $this->getParameter('shopId');
	}

	public function setShopId($value)
	{
		return $this->setParameter('shopId', $value);
	}

	public function getData()
	{
		$this->validate('amount', 'card');

		$data = array();
		$data['TRANSAC_AMOUNT'] = $this->getAmountInteger();
		$data['CURRENCY_CODE'] = strtoupper ($this->getCurrency());
		$data['PRODUCT_NAME'] = $this->getDescription();
		$data['CUSTOMER_IP'] = $this->getClientIp();
		$data['CUSTOMER_EMAIL'] = $this->getCard()->getEmail();
		$data['LANGUAGE_CODE'] = 'ENG';
		$data['SHOP_NUMBER'] = $this->getTransactionId(); 
		$data['URL_RETURN'] = $this->getReturnUrl();
		$data['URL_CANCEL'] = $this->getCancelUrl();
		$data['redirect_msg'] = 'Redirecting Now';
		$data['SHOP_ID'] = $this->getShopId();
		
		
		if ($this->getToken()) {
			$data['card_token'] = $this->getToken();
		}
		elseif($this->getCard()) {
			$data['CUSTOMER_FIRST_NAME'] = $this->getCard()->getFirstName();
			$data['CUSTOMER_LAST_NAME'] = $this->getCard()->getLastName();
			$data['CUSTOMER_ADDRESS'] = $this->getCard()->getAddress1();
			$data['CUSTOMER_CITY'] = $this->getCard()->getCity();
			$data['CUSTOMER_ZIP_CODE'] = $this->getCard()->getPostcode();
			$data['CUSTOMER_STATE'] = $this->getCard()->getState();
			//$data['CUSTOMER_COUNTRY'] = $this->getCard()->getCountry();
			$data['CUSTOMER_COUNTRY'] = "KE";
			$data['CUSTOMER_PHONE'] = $this->getCard()->getbillingPhone(); 
		}
		else{
			$data['CUSTOMER_FIRST_NAME'] = '';
			$data['CUSTOMER_LAST_NAME'] = '';
			$data['CUSTOMER_ADDRESS'] = '';
			$data['CUSTOMER_CITY'] = '';
			$data['CUSTOMER_ZIP_CODE'] = '';
			$data['CUSTOMER_STATE'] = '';
			$data['CUSTOMER_COUNTRY'] = '';
			$data['CUSTOMER_PHONE'] = '';
		}

		return $data;
	}

	public function sendData($data)
	{
		// don't throw exceptions for 4xx errors
		$this->httpClient->getEventDispatcher()->addListener(
			'request.error',
			function ($event) {
				if ($event['response']->isClientError()) {
					$event->stopPropagation();
				}
			}
		);
		
		
	
		$redirectUrl = $this->getEndpoint();
		
		return $this->response = new Response($this, $data, $redirectUrl);
	}

	public function getEndpoint()
	{
		return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
	}
	
}
