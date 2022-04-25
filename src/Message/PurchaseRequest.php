<?php

namespace Omnipay\EventCollect\Message;

class PurchaseRequest extends AbstractRequest
{
    protected function getEndpoint(): string
    {
        return sprintf('%s/charges', parent::getEndpoint());
    }

    public function getSource(): ?string
    {
        return $this->getParameter('source');
    }

    public function setSource(string $value): self
    {
        return $this->setParameter('source', $value);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $this->validate('amount');

        $data = [
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency(),
        ];

        $source = $this->getSource();
        if ($source) {
            $data['source'] = $source;
        } else {
            $data['billing'] = $this->addBillingData();
            $data['card'] = $this->getCardDetails();
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    protected function createResponse(array $data = []): Response
    {
        return new PurchaseResponse($this, $data);
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
            'company' => $creditCard->getBillingCompany(),
            'email' => $creditCard->getEmail(),
            'first' => $creditCard->getBillingFirstName(),
            'last' => $creditCard->getBillingLastName(),
            'phone' => $creditCard->getBillingPhone(),
        ]);
    }
}
