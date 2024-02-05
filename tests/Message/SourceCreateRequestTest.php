<?php

namespace League\EventCollect\Test\Message;

use Omnipay\EventCollect\Message\SourceCreateRequest;
use Omnipay\Tests\TestCase;

class SourceCreateRequestTest extends TestCase
{
    protected function setUp(): void
    {
        $this->request = new SourceCreateRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'card' => $this->getValidCard(),
            'customer' => 'fe3f7dfc-3612-49f9-96b6-d2d1c75cf75b',
        ]);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('SourceCreateSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2cc406f0-50af-47af-aeff-dfd2a0679a8c', $response->getTransactionReference());
    }
}
