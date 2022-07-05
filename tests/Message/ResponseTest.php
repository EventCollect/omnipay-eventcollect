<?php

namespace League\EventCollect\Test\Message;

use Omnipay\EventCollect\Message\Response;
use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testValidationErrorMessage(): void
    {
        $mockRequest = $this->getMockRequest();
        $mockData = $this->getMockHttpResponse('PurchaseProcessingFailure.txt');
        $data = json_decode($mockData->getBody(), true);

        $response = new Response($mockRequest, $data);

        $this->assertSame('b3bd9a0e-7c78-4c84-96b6-2899f60f3121', $response->getTransactionReference());
        $this->assertSame('Transaction declined: Total amount not approved', $response->getMessage());
    }

    public function testServerErrorMessage(): void
    {
        $mockRequest = $this->getMockRequest();
        $mockData = $this->getMockHttpResponse('NotFound.txt');
        $data = json_decode($mockData->getBody(), true);

        $response = new Response($mockRequest, $data);

        $this->assertEmpty($response->getTransactionReference());
        $this->assertSame('Not found', $response->getMessage());
    }
}
