<?php

namespace League\EventCollect\Test\Message;

use Omnipay\Common\CreditCard;
use Omnipay\EventCollect\Message\CustomerCreateRequest;
use Omnipay\Tests\TestCase;

class CustomerCreateRequestTest extends TestCase
{
    private $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CustomerCreateRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'card' => new CreditCard($this->getValidCard()),
            'email' => 'developer@eventconnect.io',
        ]);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CustomerCreateSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('6d791c66-2336-4623-b5ea-cc03964ac9e5', $response->getTransactionReference());
    }
}
