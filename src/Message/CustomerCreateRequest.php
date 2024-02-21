<?php

namespace Omnipay\EventCollect\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class CustomerCreateRequest extends AbstractRequest
{
    protected function getEndpoint(): string
    {
        return sprintf('%s/customers', parent::getEndpoint());
    }

    /**
     * @return string[]
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('email');

        $creditCard = $this->getCard();

        return [
            'billing' => $this->addBillingData(),
            'email' => $this->getEmail(),
            'first' => $creditCard->getBillingFirstName(),
            'last' => $creditCard->getBillingLastName(),
        ];
    }

    /**
     * Adds the billing data.
     *
     * @return array
     */
    protected function addBillingData(): array
    {
        $creditCard = $this->getCard();
        if (!$creditCard) {
            return [];
        }

        return array_filter([
            'address' => array_filter([
                'line1' => $creditCard->getBillingAddress1(),
                'line2' => $creditCard->getBillingAddress2(),
                'city' => $creditCard->getBillingCity(),
                'postal_code' => $creditCard->getBillingPostcode(),
                'state' => $creditCard->getBillingState(),
                'country' => $creditCard->getBillingCountry(),
            ]),
        ]);
    }
}
