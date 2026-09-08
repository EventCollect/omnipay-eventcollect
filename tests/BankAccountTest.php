<?php

namespace League\EventCollect\Test;

use Omnipay\EventCollect\BankAccount;
use Omnipay\EventCollect\Exception\InvalidBankAccountException;
use Omnipay\Tests\TestCase;

class BankAccountTest extends TestCase
{
    public function testConstructWithParameters(): void
    {
        $bankAccount = new BankAccount($this->getValidBankAccount());

        $this->assertSame('123456789', $bankAccount->getAccountNumber());
        $this->assertSame('021000021', $bankAccount->getRoutingNumber());
        $this->assertSame('checking', $bankAccount->getAccountType());
        $this->assertSame('individual', $bankAccount->getAccountHolderType());
    }

    public function testConstructWithoutParameters(): void
    {
        $bankAccount = new BankAccount();

        $this->assertNull($bankAccount->getAccountNumber());
        $this->assertNull($bankAccount->getRoutingNumber());
        $this->assertNull($bankAccount->getAccountType());
        $this->assertNull($bankAccount->getAccountHolderType());
    }

    public function testSetters(): void
    {
        $bankAccount = (new BankAccount())
            ->setAccountNumber('987654321')
            ->setRoutingNumber('011000015')
            ->setAccountType(BankAccount::ACCOUNT_TYPE_SAVINGS)
            ->setAccountHolderType(BankAccount::HOLDER_TYPE_BUSINESS);

        $this->assertSame('987654321', $bankAccount->getAccountNumber());
        $this->assertSame('011000015', $bankAccount->getRoutingNumber());
        $this->assertSame('savings', $bankAccount->getAccountType());
        $this->assertSame('business', $bankAccount->getAccountHolderType());
    }

    /**
     * @dataProvider validBankAccounts
     */
    public function testValidateSuccess(array $overrides, ?string $currency): void
    {
        $this->expectNotToPerformAssertions();

        $this->getBankAccount($overrides)->validate($currency);
    }

    public function validBankAccounts(): array
    {
        return [
            'us routing number' => [[], 'USD'],
            'canadian routing number' => [['routingNumber' => '000400001'], 'CAD'],
            'shortest account number' => [['accountNumber' => '12345'], null],
            'longest account number' => [['accountNumber' => '12345678901234567'], null],
            'savings account' => [['accountType' => BankAccount::ACCOUNT_TYPE_SAVINGS], null],
            'business account holder' => [['accountHolderType' => BankAccount::HOLDER_TYPE_BUSINESS], null],
            // A Canadian routing number fails the ABA check digit, so an
            // unknown currency has to skip the checksum entirely.
            'unknown currency skips the checksum' => [['routingNumber' => '000400001'], null],
        ];
    }

    /**
     * @dataProvider invalidBankAccounts
     */
    public function testValidateFailure(array $overrides, ?string $currency, string $message): void
    {
        $this->expectException(InvalidBankAccountException::class);
        $this->expectExceptionMessage($message);

        $this->getBankAccount($overrides)->validate($currency);
    }

    public function invalidBankAccounts(): array
    {
        $accountNumber = 'Account number should have 5 to 17 digits';
        $routingNumber = 'Routing number should have 9 digits';
        $accountType = 'Account type should be one of: checking, savings';
        $holderType = 'Account holder type should be one of: individual, business';
        $usRoutingNumber = 'Routing number is not a valid US routing number';
        $caRoutingNumber = 'Routing number is not a valid Canadian routing number';

        return [
            'missing account number' => [['accountNumber' => null], null, 'The account number is required'],
            'missing routing number' => [['routingNumber' => null], null, 'The routing number is required'],
            'missing account type' => [['accountType' => null], null, 'The account type is required'],
            'missing holder type' => [['accountHolderType' => null], null, 'The account holder type is required'],
            'short account number' => [['accountNumber' => '1234'], null, $accountNumber],
            'long account number' => [['accountNumber' => '123456789012345678'], null, $accountNumber],
            'non numeric account number' => [['accountNumber' => '1234567x'], null, $accountNumber],
            'short routing number' => [['routingNumber' => '02100002'], null, $routingNumber],
            'long routing number' => [['routingNumber' => '0210000210'], null, $routingNumber],
            'unsupported account type' => [['accountType' => 'chequing'], null, $accountType],
            'unsupported holder type' => [['accountHolderType' => 'corporate'], null, $holderType],
            'bad us check digit' => [['routingNumber' => '021000022'], 'USD', $usRoutingNumber],
            'canadian without leading zero' => [['routingNumber' => '123456789'], 'CAD', $caRoutingNumber],
        ];
    }

    private function getBankAccount(array $overrides = []): BankAccount
    {
        return new BankAccount(array_merge($this->getValidBankAccount(), $overrides));
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
