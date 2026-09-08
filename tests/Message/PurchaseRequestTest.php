<?php

namespace League\EventCollect\Test\Message;

use Omnipay\EventCollect\BankAccount;
use Omnipay\EventCollect\Exception\InvalidBankAccountException;
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

    public function testDataWithBankAccount(): void
    {
        $this->request->setCurrency('USD');
        $this->request->setBankAccount($this->getValidBankAccount());

        $data = $this->request->getData();

        $this->assertSame([
            'account_number' => '123456789',
            'routing_number' => '021000021',
            'account_type' => 'checking',
            'account_holder_type' => 'individual',
        ], $data['bank_account']);
        $this->assertArrayNotHasKey('card', $data);
    }

    public function testDataWithBankAccountObject(): void
    {
        $this->request->setBankAccount(new BankAccount($this->getValidBankAccount()));

        $data = $this->request->getData();

        $this->assertSame('021000021', $data['bank_account']['routing_number']);
    }

    public function testDataWithBankAccountBilling(): void
    {
        $this->request->setBankAccount($this->getValidBankAccount());
        $this->request->setBillingFirstName('First');
        $this->request->setBillingCompany('Acme Inc');

        $data = $this->request->getData();

        $this->assertSame('First', $data['billing']['first']);
        $this->assertSame('Acme Inc', $data['billing']['company']);
    }

    public function testDataWithSourceIgnoresBankAccount(): void
    {
        $this->request->setSource('source');
        $this->request->setBankAccount($this->getValidBankAccount());

        $data = $this->request->getData();

        $this->assertSame('source', $data['source']);
        $this->assertArrayNotHasKey('bank_account', $data);
    }

    public function testDataWithInvalidBankAccount(): void
    {
        $this->request->setCurrency('USD');
        $this->request->setBankAccount(array_merge($this->getValidBankAccount(), [
            'routingNumber' => '021000022',
        ]));

        $this->expectException(InvalidBankAccountException::class);

        $this->request->getData();
    }

    public function testSendBankAccountSuccess(): void
    {
        $this->request->setAmount('50.00');
        $this->request->setCurrency('USD');
        $this->request->setBankAccount($this->getValidBankAccount());

        $this->setMockHttpResponse('PurchaseBankAccountSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('8f4d1e02-5b7c-4a19-9e63-2c8f0a5d7b31', $response->getTransactionReference());
    }

    private function getValidBankAccount(): array
    {
        return [
            'accountNumber' => '123456789',
            'routingNumber' => '021000021',
            'accountType' => BankAccount::ACCOUNT_TYPE_CHECKING,
            'accountHolderType' => BankAccount::HOLDER_TYPE_INDIVIDUAL,
        ];
    }
}
