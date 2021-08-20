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
        return isset($this->data['data']['id']);
    }

    public function getMessage()
    {
        if (!isset($this->data['errors'])) {
            return null;
        }

        return current($this->data['errors'])[0];
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return $this->data['data']['id'] ?? null;
    }
}
