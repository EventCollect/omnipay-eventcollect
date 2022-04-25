<?php

namespace Omnipay\EventCollect\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class SourceCreateRequest extends AbstractRequest
{
    protected function getEndpoint(): string
    {
        return sprintf('%s/sources', parent::getEndpoint());
    }

    public function getCustomer(): string
    {
        return $this->getParameter('customer');
    }

    public function setCustomer(string $value): self
    {
        return $this->setParameter('customer', $value);
    }

    /**
     * TODO
     */
    private function getCardDetails(): array
    {
        $this->validate('card');

        $card = $this->getCard();

        return [
            'exp_month' => $card->getExpiryMonth(),
            'exp_year' => $card->getExpiryYear(),
            'cvc' => $card->getCvv(),
            'number' => $card->getNumber(),
        ];
    }

    /**
     * @return string[]
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('customer');

        return [
            'card' => $this->getCardDetails(),
            'customer' => $this->getCustomer(),
        ];
    }
}
