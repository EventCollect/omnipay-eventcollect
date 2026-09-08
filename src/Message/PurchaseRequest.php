<?php

namespace Omnipay\EventCollect\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\EventCollect\BankAccount;
use Omnipay\EventCollect\Exception\InvalidBankAccountException;
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
     * Get the bank account to charge.
     */
    public function getBankAccount(): ?BankAccount
    {
        return $this->getParameter('bankAccount');
    }

    /**
     * Sets the bank account to charge.
     *
     * @param BankAccount|array $value
     */
    public function setBankAccount($value): self
    {
        if ($value && ! $value instanceof BankAccount) {
            $value = new BankAccount($value);
        }

        return $this->setParameter('bankAccount', $value);
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
        } elseif ($this->getBankAccount()) {
            $data['bank_account'] = $this->getBankAccountDetails();
        } else {
            $data['card'] = $this->getCardDetails();
        }

        $data['billing'] = $this->addBillingData();

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
     * Builds the bank account payload.
     *
     * @throws InvalidBankAccountException|InvalidRequestException
     */
    private function getBankAccountDetails(): array
    {
        $this->validate('bankAccount');

        $bankAccount = $this->getBankAccount();
        $bankAccount->validate($this->getCurrency());

        return [
            'account_number' => $bankAccount->getAccountNumber(),
            'routing_number' => $bankAccount->getRoutingNumber(),
            'account_type' => $bankAccount->getAccountType(),
            'account_holder_type' => $bankAccount->getAccountHolderType(),
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

        if (! $creditCard) {
            return array_filter([
                'address' => array_filter([
                    'line1' => $this->getBillingAddress1(),
                    'line2' => $this->getBillingAddress2(),
                    'city' => $this->getBillingCity(),
                    'postal_code' => $this->getBillingPostcode(),
                    'state' => $this->getBillingState(),
                    'country' => $this->getBillingCountry(),
                ]),
                'company' => $this->getBillingCompany(),
                'email' => $this->getEmail(),
                'first' => $this->getBillingFirstName(),
                'last' => $this->getBillingLastName(),
                'phone' => $this->getBillingPhone(),
            ]);
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
