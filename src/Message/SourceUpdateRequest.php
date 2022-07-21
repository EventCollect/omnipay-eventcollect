<?php

namespace Omnipay\EventCollect\Message;

class SourceUpdateRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return sprintf('%s/sources/%s', parent::getEndpoint(), $this->getSource());
    }

    protected function getHttpMethod(): string
    {
        return 'PUT';
    }

    /**
     * TODO
     */
    private function getCardDetails(): array
    {
        $card = $this->getCard();

        if (! $card) {
            return [];
        }

        return [
            'billing' => [
                'first' => $card->getBillingFirstName(),
                'last' => $card->getBillingLastName(),
            ],
            'card' => [
                'exp_month' => $card->getExpiryMonth(),
                'exp_year' => $card->getExpiryYear(),
                'cvc' => $card->getCvv(),
                'number' => $card->getNumber(),
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return array_filter(
            array_merge_recursive(
                [
                    'customer' => $this->getCustomer(),
                ],
                $this->getCardDetails()
            )
        );
    }
}
