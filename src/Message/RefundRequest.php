<?php

namespace Omnipay\EventCollect\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class RefundRequest extends AbstractRequest
{
    protected function getEndpoint(): string
    {
        return sprintf('%s/refunds', parent::getEndpoint());
    }

    /**
     * @inheritDoc
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('amount', 'transactionReference');

        return [
            'amount' => $this->getAmountInteger(),
            'for' => $this->getTransactionReference(),
        ];
    }

    protected function createResponse(array $data = []): Response
    {
        return new RefundResponse($this, $data);
    }
}
