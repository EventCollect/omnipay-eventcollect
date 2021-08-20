<?php

namespace League\EventCollect\Test\Message;

use Omnipay\Common\CreditCard;
use Omnipay\EventCollect\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    private PurchaseRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'card' => new CreditCard($this->getValidCard()),
        ]);
    }

    public function testSendSuccess(): void
    {
        $this->request->setAmount(9.99);

        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('b6ae47cd-7113-442b-a7e5-34b8b6138636', $response->getTransactionReference());
    }

    public function testSendFailure(): void
    {
        $this->request->setAmount(9.99);
        $this->request->setCurrency('USD');

        $this->setMockHttpResponse('PurchaseFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('The selected currency (USD) is invalid for this transaction.', $response->getMessage());
    }
/*
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('9.99', $data['amount']);
    }
*/
}
