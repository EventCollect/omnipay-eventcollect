<?php

namespace Omnipay\EventCollect\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class CustomerCreateRequest extends AbstractRequest
{
    protected function getEndpoint(): string
    {
        return sprintf('%s/customers', parent::getEndpoint());
    }

    public function getEmail(): string
    {
        return $this->getParameter('email');
    }

    public function setEmail(string $value): self
    {
        return $this->setParameter('email', $value);
    }

    public function getFirstName(): string
    {
        return $this->getParameter('first_name');
    }

    public function setFirstName(string $value): self
    {
        return $this->setParameter('first_name', $value);
    }

    public function getLastName(): string
    {
        return $this->getParameter('last_name');
    }

    public function setLastName(string $value): self
    {
        return $this->setParameter('last_name', $value);
    }

    /**
     * @return string[]
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('email', 'first_name', 'last_name');

        return [
            'email' => $this->getEmail(),
            'first' => $this->getFirstName(),
            'last' => $this->getLastName(),
        ];
    }
}
