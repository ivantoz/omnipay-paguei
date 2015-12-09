<?php

namespace Omnipay\Paguei\Message;


 

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use GuzzleHttp\Client;
use OAuth2;

require_once('./lib/paguei/vendor/autoload.php');

/**
 * Paguei.Online Payment Gateway Complete Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
	public function getPath()
	{
		return $this->getParameter('path');
	}

	public function setPath($value)
	{
		return $this->setParameter('path', $value);
	}
	public function getClientId()
	{
		return $this->getParameter('clientId');
	}

	public function setClientId($value)
	{
		return $this->setParameter('clientId', $value);
	}
	public function getClientSecret()
	{
		return $this->getParameter('clientSecret');
	}

	public function setClientSecret($value)
	{
		return $this->setParameter('clientSecret', $value);
	}
	public function getMerchantId()
	{
		return $this->getParameter('merchantId');
	}

	public function setMerchantId($value)
	{
		return $this->setParameter('merchantId', $value);
	}
	public function getAmount()
	{
		return $this->getParameter('amount');
	}

	public function setAmount($value)
	{
		return $this->setParameter('amount', $value);
	}
	public function getDescription()
	{
		return $this->getParameter('description');
	}

	public function setDescription($value)
	{
		return $this->setParameter('description', $value);
	}
	public function getRedirectUrl()
	{
		return $this->getParameter('redirecturl');
	}

	public function setRedirectUrl($value)
	{
		return $this->setParameter('redirecturl', $value);
	}
	
	
    public function getData()
    {
		$clientId= $this->getClientId();
		$clientSecret = $this->getClientSecret();
		$merchantId = $this->getMerchantId();
		$amount = $this->getAmount();
		$description = $this->getDescription();
		$redirectUrl = $this->getRedirectUrl();
		$oauth2path = $this->getPath();
		
		define("CLIENT_ID", $clientId);
		define("CLIENT_SECRET", $clientSecret);
		
		
		define("REDIRECT_URI", $redirectUrl);
		define("AUTHORIZATION_ENDPOINT", "https://paguei.online/app/api/authorize");
		define("TOKEN_ENDPOINT", "https://paguei.online/app/api/token");
  
		
		$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);
		if (!isset($_GET['code']))
		{
			$auth_url = $client->getAuthenticationUrl(AUTHORIZATION_ENDPOINT, REDIRECT_URI);
			header('Location: ' . $auth_url);
			die('Redirect');
		}
		else
		{
			$params = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
			$response = $client->getAccessToken(TOKEN_ENDPOINT, 'authorization_code', $params);
			
			$info = $response['result'];
			$client->setAccessToken($info['access_token']);
			$id = $merchantId;
			$description = urlencode($description);
			
			$urlfetch = 'https://paguei.online/app/api/transfer';
			$urlfetch2 = $urlfetch.'/'.$id.'/'.$amount.'/'.$description.'.json';
			$response = $client->fetch($urlfetch2);
				
			return $response;
			
		}


			
	}

    public function sendData($response)
     {
		
        return $this->response = new CompletePurchaseResponse($this, $response);
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
