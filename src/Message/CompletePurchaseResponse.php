<?php

namespace Omnipay\Bardo\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Bardo Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        if (!is_array($data)) {
            parse_str($data, $data);
        }

        parent::__construct($request, $data);
    }

    public function isSuccessful()
    {
        
		return isset($this->data['result']) &&  $this->data['code'] === 200 && $this->data['result']['result']['status'] === "1" && $this->data['result']['result']['source'] === "PAGUEI" ;
    }

    public function getTransactionReference()
    {
        return isset($this->data['result']['result']['receipt']) ? $this->data['result']['result']['receipt'] : null;
    }

    public function getMessage()
    {
        //return isset($this->data['ResponseMessage']) ? $this->data['ResponseMessage'] : null;
    }
	public function getTransactionDate()
    {
        return isset($this->data['result']['result']['created']) ? $this->data['result']['result']['created'] : null;
		
		
    }
	public function getTransactionAmount()
    {
		
        return isset($this->data['result']['result']['amount']) ? $this->data['result']['result']['amount'] : null;
    }
	
}
