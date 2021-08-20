<?php

namespace Omnipay\EventCollect;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\EventCollect\Message\PurchaseRequest;

/**
 * @method NotificationInterface acceptNotification(array $options = array())
 * @method RequestInterface authorize(array $options = array())
 * @method RequestInterface completeAuthorize(array $options = array())
 * @method RequestInterface capture(array $options = array())
 * @method RequestInterface completePurchase(array $options = array())
 * @method RequestInterface refund(array $options = array())
 * @method RequestInterface fetchTransaction(array $options = [])
 * @method RequestInterface void(array $options = array())
 * @method RequestInterface createCard(array $options = array())
 * @method RequestInterface updateCard(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'EventCollect';
    }

    /**
     * @inheritDoc
     */
    public function getDefaultParameters(): array
    {
        return [
            'api_key' => '',
        ];
    }

    public function getApiKey(): ?string
    {
        return $this->getParameter('api_key');
    }

    public function setApiKey($value): Gateway
    {
        return $this->setParameter('api_key', $value);
    }

    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method NotificationInterface acceptNotification(array $options = array())
        // TODO: Implement @method RequestInterface authorize(array $options = array())
        // TODO: Implement @method RequestInterface completeAuthorize(array $options = array())
        // TODO: Implement @method RequestInterface capture(array $options = array())
        // TODO: Implement @method RequestInterface completePurchase(array $options = array())
        // TODO: Implement @method RequestInterface refund(array $options = array())
        // TODO: Implement @method RequestInterface fetchTransaction(array $options = [])
        // TODO: Implement @method RequestInterface void(array $options = array())
        // TODO: Implement @method RequestInterface createCard(array $options = array())
        // TODO: Implement @method RequestInterface updateCard(array $options = array())
        // TODO: Implement @method RequestInterface deleteCard(array $options = array())
    }
}
