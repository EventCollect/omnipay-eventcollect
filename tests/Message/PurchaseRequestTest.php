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

    public function testDataWithImplicitItems(): void
    {
        $this->request->setAmount(9.99);

        $expected = [
            [
                'description' => 'Default item',
                'quantity' => 1,
                'amount' => 999,
            ],
        ];
        [ 'items' => $actual ] = $this->request->getData();

        $this->assertEquals($expected, $actual);
    }

    public function testDataWithExplicitItems(): void
    {
        $this->request->setAmount(9.99);
        $this->request->setItems([
            [
                'name' => 'name',
                'description' => 'description',
                'quantity' => 1,
                'price' => 999,
            ],
            [
                'name' => 'name',
                'description' => 'description',
                'quantity' => 2,
                'price' => 999,
            ],
        ]);

        $expected = [
            [
                'description' => 'description',
                'quantity' => 1,
                'amount' => 999,
            ],
            [
                'description' => 'description',
                'quantity' => 2,
                'amount' => 999,
            ],
        ];
        [ 'items' => $actual ] = $this->request->getData();

        $this->assertEquals($expected, $actual);
    }
}
