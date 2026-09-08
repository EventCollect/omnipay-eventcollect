<?php

namespace Omnipay\EventCollect\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\EventCollect\ItemBag;
use Omnipay\EventCollect\Message\Traits\HasBillingData;

class PurchaseRequest extends AbstractRequest
{
    use HasBillingData;

    protected function getEndpoint(): string
    {
        return sprintf('%s/charges', parent::getEndpoint());
    }

    public function setItems($items): self
    {
        if ($items && !$items instanceof ItemBag) {
            $items = new ItemBag($items);
        }

        return $this->setParameter('items', $items);
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
            'description' => $this->getDescription(),
        ];

        if ($source = $this->getSource()) {
            $data['source'] = $source;
        } else {
            $data['card'] = $this->getCardDetails();
        }

        $data['billing'] = $this->getBillingData();

        if ($items = $this->getItems()) {
            foreach ($items as $item) {
                $data['items'][] = [
                    'description' => $item->getDescription(),
                    'quantity' => $item->getQuantity(),
                    'amount' => $item->getPriceInteger(),
                ];
            }
        } else {
            $data['items'][] = [
                'description' => 'Default item',
                'quantity' => 1,
                'amount' => $data['amount'],
            ];
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
     * Get the card data.
     *
     * @throws InvalidRequestException
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
     * Get the billing data.
     */
    protected function getBillingData(): array
    {
        /** @var CreditCard|self $billingSource */
        $billingSource = $this->getCard() ?? $this;

        return array_filter([
            'address' => array_filter([
                'line1' => $billingSource->getBillingAddress1(),
                'line2' => $billingSource->getBillingAddress2(),
                'city' => $billingSource->getBillingCity(),
                'postal_code' => $billingSource->getBillingPostcode(),
                'state' => $billingSource->getBillingState(),
                'country' => $billingSource->getBillingCountry(),
            ]),
            'company' => $billingSource->getBillingCompany(),
            'email' => $billingSource->getEmail(),
            'first' => $billingSource->getBillingFirstName(),
            'last' => $billingSource->getBillingLastName(),
            'phone' => $billingSource->getBillingPhone(),
        ]);
    }
}
