<?php

namespace League\EventCollect\Test\Message;

use Omnipay\Common\CreditCard;
use Omnipay\EventCollect\Message\SourceUpdateRequest;
use Omnipay\Tests\TestCase;

class SourceUpdateRequestTest extends TestCase
{
    private $card;
    private $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new SourceUpdateRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->card = new CreditCard($this->getValidCard());

        $this->request->initialize([
            'card' => $this->card,
            'source' => 'fe3f7dfc-3612-49f9-96b6-d2d1c75cf75b',
        ]);
    }

    public function testEndpoint(): void
    {
        $expected = 'https://api.eventcollect.io/sources/fe3f7dfc-3612-49f9-96b6-d2d1c75cf75b';
        $actual = $this->request->getEndpoint();
        $this->assertSame($expected, $actual);
    }

    public function testGetData(): void
    {
        $expected = [
            'first' => $this->card->getBillingFirstName(),
            'last' => $this->card->getBillingLastName(),
            'card' => [
                'exp_month' => $this->card->getExpiryMonth(),
                'exp_year' => $this->card->getExpiryYear(),
                'cvc' => $this->card->getCvv(),
                'number' => $this->card->getNumber(),
            ],
        ];
        $actual = $this->request->getData();
        $this->assertSame($expected, $actual);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('SourceUpdateSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('df1692de-096c-41c4-bce3-aa9d3e3e27cd', $response->getTransactionReference());
    }
}
