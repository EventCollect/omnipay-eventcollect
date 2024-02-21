<?php

namespace Omnipay\EventCollect\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    protected $liveEndpoint = 'https://api.eventcollect.io';
    protected $testEndpoint = 'https://apitest.eventcollect.io';

    public function getApiKey(): ?string
    {
        return $this->getParameter('api_key');
    }

    public function setApiKey($value): AbstractRequest
    {
        return $this->setParameter('api_key', $value);
    }

    protected function getEndpoint(): string
    {
        if ($this->getTestMode()) {
            return $this->testEndpoint;
        }

        return $this->liveEndpoint;
    }

    protected function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getCustomer(): ?string
    {
        return $this->getParameter('customer');
    }

    public function setCustomer(string $value): self
    {
        return $this->setParameter('customer', $value);
    }

    public function getEmail(): ?string
    {
        return $this->getParameter('email');
    }

    public function setEmail(string $value): self
    {
        return $this->setParameter('email', $value);
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
    public function sendData($data): Response
    {
        $endpoint = $this->getEndpoint();
        $authHeader = sprintf('Bearer %s', $this->getApiKey());
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => $authHeader,
            'Content-Type' => 'application/json',
        ];

        $response = $this->httpClient->request($this->getHttpMethod(), $endpoint, $headers, json_encode($data));

        $responseBody = json_decode($response->getBody(), true);

        return $this->createResponse($responseBody);
    }

    /**
     * TODO
     *
     * @param array $data
     * @return Response
     */
    protected function createResponse(array $data = []): Response
    {
        return new Response($this, $data);
    }
}
