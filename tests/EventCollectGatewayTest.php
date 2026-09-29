<?php

namespace Omnipay\EventCollect;

use Omnipay\EventCollect\Message\PurchaseRequest;
use Omnipay\EventCollect\Message\RefundRequest;
use Omnipay\Tests\GatewayTestCase;

class EventCollectGatewayTest extends GatewayTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase(): void
    {
        $request = $this->gateway->purchase([
            'amount' => 10,
        ]);

        $this->assertInstanceOf(PurchaseRequest::class, $request);
    }

    public function testRefund(): void
    {
        $request = $this->gateway->refund([
            'amount' => 10,
        ]);

        $this->assertInstanceOf(RefundRequest::class, $request);
    }
}
