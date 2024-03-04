<?php

namespace League\EventCollect\Test\Message;

use Omnipay\EventCollect\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    protected function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'amount' => 1,
        ]);
    }

    public function testSendSuccess(): void
    {
        $this->request->setAmount(9.99);
        $this->request->setCard($this->getValidCard());

        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('b6ae47cd-7113-442b-a7e5-34b8b6138636', $response->getTransactionReference());
    }

    public function testSendFailure(): void
    {
        $this->request->setAmount(9.99);
        $this->request->setCard($this->getValidCard());
        $this->request->setCurrency('USD');

        $this->setMockHttpResponse('PurchaseFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('The selected currency (USD) is invalid for this transaction.', $response->getMessage());
    }

    public function testDataWithImplicitItems(): void
    {
        $this->request->setAmount(9.99);
        $this->request->setCard($this->getValidCard());

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
        $this->request->setCard($this->getValidCard());
        $this->request->setItems([
            [
                'name' => 'name',
                'description' => 'description',
                'quantity' => 1,
                'price' => 9.99,
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
                'amount' => 99900,
            ],
        ];
        [ 'items' => $actual ] = $this->request->getData();

        $this->assertEquals($expected, $actual);
    }

    public function testDataWithSource(): void
    {
        $this->request->setSource('source');

        $data = $this->request->getData();

        $this->assertSame('source', $data['source']);
    }

    public function testDataWithBilling(): void
    {
        $this->request->setSource('source');
        $this->request->setBillingFirstName('First');
        $this->request->setBillingAddress1('Address 1');

        $data = $this->request->getData();

        $this->assertSame('First', $data['billing']['first']);
        $this->assertSame('Address 1', $data['billing']['address']['line1']);
    }
}
