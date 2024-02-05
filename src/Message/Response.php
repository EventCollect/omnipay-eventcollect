<?php

namespace Omnipay\EventCollect\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{
    /**
     * @throws JsonException
     */
    public function __construct(RequestInterface $request, $data)
    {
        if (is_string($data)) {
            $data = json_decode($data, true, 16, JSON_THROW_ON_ERROR);
        }

        parent::__construct($request, $data);
    }

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
