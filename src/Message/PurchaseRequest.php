<?php

namespace Omnipay\EventCollect\Message;

class PurchaseRequest extends AbstractRequest
{
    protected function getEndpoint(): string
    {
        return sprintf('%s/charges', parent::getEndpoint());
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $this->validate('amount');

        $data = [
            'amount' => $this->getAmountInteger(),
            'card' => $this->getCardDetails(),
            'currency' => $this->getCurrency(),
        ];

        $this->addBillingData($data);

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
     * @param array $data
     */
    protected function addBillingData(array &$data): void
    {
        /** @var CreditCard $creditCard */
        if ($creditCard = $this->getCard()) {
            // A card is present, so include billing details
            if ($creditCard->getEmail()) {
                $data['email'] = $creditCard->getEmail();
            }

            if ($creditCard->getBillingFirstName()) {
                $data['first'] = $creditCard->getBillingFirstName();
            }

            if ($creditCard->getBillingLastName()) {
                $data['last'] = $creditCard->getBillingLastName();
            }

            if ($creditCard->getBillingCompany()) {
                $data['company'] = $creditCard->getBillingCompany();
            }

            if ($creditCard->getBillingAddress1()) {
                $data['address']['line1'] = $creditCard->getBillingAddress1();
            }

            if ($creditCard->getBillingAddress2()) {
                $data['address']['line1'] = $creditCard->getBillingAddress2();
            }

            if ($creditCard->getBillingCity()) {
                $data['address']['city'] = $creditCard->getBillingCity();
            }

            if ($creditCard->getBillingPostcode()) {
                $data['address']['postal_code'] = $creditCard->getBillingPostcode();
            }

            if ($creditCard->getBillingState()) {
                $data['address']['state'] = $creditCard->getBillingState();
            }

            if ($creditCard->getBillingCountry()) {
                $data['address']['country'] = $creditCard->getBillingCountry();
            }

            if ($creditCard->getBillingPhone()) {
                $data['phone'] = $creditCard->getBillingPhone();
            }

            if ($creditCard->getBillingCountry()) {
                $data['address']['country'] = $creditCard->getBillingCountry();
            }
        }
    }
}
