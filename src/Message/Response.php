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
        if (empty($this->data['errors'])) {
            return null;
        }

        $firstErrorGroup = current($this->data['errors']);

        if ($firstErrorGroup === false) {
            return null;
        }

        return $firstErrorGroup[0];
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
