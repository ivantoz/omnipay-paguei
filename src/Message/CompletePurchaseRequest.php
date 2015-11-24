<?php

namespace Omnipay\Paguei\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use GuzzleHttp\Client;
use OAuth2;

// require('Client.php');
// require('GrantType/IGrantType.php');
// require('GrantType/AuthorizationCode.php');


/**
 * Paguei Complete Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
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
	
    public function getData()
    {
		$clientId= $this->getClientId();
		$clientSecret = $this->getClientSecret();
		$merchantId = $this->getMerchantId();
		$amount = $this->getAmountInteger();
		$description = $this->getDescription();
		
		define("CLIENT_ID", $clientId);
		define("CLIENT_SECRET", $clientSecret);
		
		
		define("REDIRECT_URI", "http://localhost:8020/gateways/Bardo/completePurchase");
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
		 
		//In your app you must post some required information as I mentioned in STEP 1 above
			$id = "9";
			$amount = "1";
			$description = "Smokie";
			 
			//Please note you must urlencode the description above. Otherwise it will fail
			$description = urlencode($description);
			 
				
				
			//please note that in the following url escape the variables appropriately,
				//$response = $client->fetch('https://paguei.online/app/api/transfer/?$id/$amount/$description.json');
				
				//$response = $client->fetch('https://paguei.online/app/api/transfer/'.$id.'/'.$amount.'/'.$description.json);
				$response = $client->fetch('https://paguei.online/app/api/transfer/9/1/productname.json');
				
				
				
			// this shows the response from the site after successful authorization. The response could have a successful transaction or a failure. See step 3 above
				 //var_dump($response);
				//$data = $response;
			
			
			
			
			return $response;
			
		}


			
	}

    public function sendData($response)
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
        return $this->response = new CompletePurchaseResponse($this, $response);
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
