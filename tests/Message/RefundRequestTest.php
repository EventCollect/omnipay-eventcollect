<?php

namespace League\EventCollect\Test\Message;

use Omnipay\EventCollect\Message\RefundRequest;
use Omnipay\EventCollect\Message\RefundResponse;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    protected function setUp(): void
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'amount' => '9.99',
            'transactionReference' => 'b6ae47cd-7113-442b-a7e5-34b8b6138636',
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame([
            'amount' => 999,
            'for' => 'b6ae47cd-7113-442b-a7e5-34b8b6138636',
        ], $data);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('RefundSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(RefundResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('f1a1ca36-a08d-40ca-adb0-bc6cf81f670e', $response->getTransactionReference());
    }

    public function testSendFailure(): void
    {
        $this->setMockHttpResponse('RefundFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('The amount field is required.', $response->getMessage());
    }
}
