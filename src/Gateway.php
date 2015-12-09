<?php

namespace Omnipay\Paguei;

use Omnipay\Common\AbstractGateway;

 

/**
 * Paguei.Online Payment Gateway
 *
 * @link https://paguei.online/docs/
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Paguei';
    }

    public function getDefaultParameters()
    {
        return array(
           
            'testMode' => false,
        );
    }
    
	public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paguei\Message\CompletePurchaseRequest', $parameters);
    }
}
