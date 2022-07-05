<?php

namespace Omnipay\EventCollect\Message;

use Omnipay\Common\Message\AbstractResponse;

class Response extends AbstractResponse
{

    /**
     * @inheritDoc
     */
    public function isSuccessful(): bool
    {
        return isset($this->data['data']['id']) && ! $this->getMessage();
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): ?string
    {
        $errors = $this->data['errors']
            ?? $this->data['data']['errors']
            ?? [];

        return current($errors)[0]
            ?? $this->data['message']
            ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return $this->data['data']['id'] ?? null;
    }

    public function getCustomerReference()
    {
        return $this->data['data']['id'] ?? null;
    }

    public function getSourceReference()
    {
        return $this->data['data']['id'] ?? null;
    }
}
