<?php

namespace Omnipay\EventCollect;

use Omnipay\Common\ParametersTrait;
use Omnipay\EventCollect\Exception\InvalidBankAccountException;

/**
 * Bank Account class
 *
 * Example:
 *
 * <code>
 *   $bankAccount = new BankAccount([
 *       'accountNumber' => '123456789',
 *       'routingNumber' => '021000021',
 *       'accountType' => BankAccount::ACCOUNT_TYPE_CHECKING,
 *       'accountHolderType' => BankAccount::HOLDER_TYPE_INDIVIDUAL,
 *   ]);
 * </code>
 *
 * Bank accounts cannot be tokenized as payment sources, so they may only be
 * used to make a purchase.
 */
class BankAccount
{
    use ParametersTrait;

    public const ACCOUNT_TYPE_CHECKING = 'checking';
    public const ACCOUNT_TYPE_SAVINGS = 'savings';

    public const HOLDER_TYPE_INDIVIDUAL = 'individual';
    public const HOLDER_TYPE_BUSINESS = 'business';

    /**
     * All supported account types.
     */
    protected array $supportedAccountTypes = [
        self::ACCOUNT_TYPE_CHECKING,
        self::ACCOUNT_TYPE_SAVINGS,
    ];

    /**
     * All supported account holder types.
     */
    protected array $supportedHolderTypes = [
        self::HOLDER_TYPE_INDIVIDUAL,
        self::HOLDER_TYPE_BUSINESS,
    ];

    /**
     * Create a new bank account using the specified parameters.
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct(array $parameters = [])
    {
        $this->initialize($parameters);
    }

    /**
     * Get the bank account number.
     */
    public function getAccountNumber(): ?string
    {
        return $this->getParameter('accountNumber');
    }

    /**
     * Sets the bank account number.
     */
    public function setAccountNumber($value): self
    {
        return $this->setParameter('accountNumber', $value);
    }

    /**
     * Get the routing number.
     */
    public function getRoutingNumber(): ?string
    {
        return $this->getParameter('routingNumber');
    }

    /**
     * Sets the routing number.
     */
    public function setRoutingNumber($value): self
    {
        return $this->setParameter('routingNumber', $value);
    }

    /**
     * Get the account type.
     */
    public function getAccountType(): ?string
    {
        return $this->getParameter('accountType');
    }

    /**
     * Sets the account type.
     */
    public function setAccountType($value): self
    {
        return $this->setParameter('accountType', $value);
    }

    /**
     * Get the account holder type.
     */
    public function getAccountHolderType(): ?string
    {
        return $this->getParameter('accountHolderType');
    }

    /**
     * Sets the account holder type.
     */
    public function setAccountHolderType($value): self
    {
        return $this->setParameter('accountHolderType', $value);
    }

    /**
     * Validate this bank account.
     *
     * This method is called internally by gateways to avoid wasting time with
     * an API call when the bank account is clearly invalid.
     *
     * The routing number is checked against the scheme used by the currency the
     * charge is made in, mirroring the API, which selects its own routing number
     * rule the same way. The check is skipped when the currency is unknown.
     *
     * @throws InvalidBankAccountException
     */
    public function validate(?string $currency = null): void
    {
        $requiredParameters = [
            'accountNumber' => 'account number',
            'routingNumber' => 'routing number',
            'accountType' => 'account type',
            'accountHolderType' => 'account holder type',
        ];

        foreach ($requiredParameters as $key => $label) {
            if (! $this->getParameter($key)) {
                throw new InvalidBankAccountException("The $label is required");
            }
        }

        if (! preg_match('/^\d{5,17}$/', $this->getAccountNumber())) {
            throw new InvalidBankAccountException('Account number should have 5 to 17 digits');
        }

        if (! preg_match('/^\d{9}$/', $this->getRoutingNumber())) {
            throw new InvalidBankAccountException('Routing number should have 9 digits');
        }

        if (! in_array($this->getAccountType(), $this->supportedAccountTypes, true)) {
            throw new InvalidBankAccountException(sprintf(
                'Account type should be one of: %s',
                implode(', ', $this->supportedAccountTypes)
            ));
        }

        if (! in_array($this->getAccountHolderType(), $this->supportedHolderTypes, true)) {
            throw new InvalidBankAccountException(sprintf(
                'Account holder type should be one of: %s',
                implode(', ', $this->supportedHolderTypes)
            ));
        }

        $this->validateRoutingNumber($currency);
    }

    /**
     * Validate the routing number against the scheme used by the given currency.
     *
     * @throws InvalidBankAccountException
     */
    private function validateRoutingNumber(?string $currency): void
    {
        $routingNumber = $this->getRoutingNumber();

        if ($currency === 'USD' && ! $this->hasValidAbaCheckDigit($routingNumber)) {
            throw new InvalidBankAccountException('Routing number is not a valid US routing number');
        }

        if ($currency === 'CAD' && strncmp($routingNumber, '0', 1) !== 0) {
            throw new InvalidBankAccountException('Routing number is not a valid Canadian routing number');
        }
    }

    /**
     * Verify the check digit of an ABA routing number.
     *
     * @link https://en.wikipedia.org/wiki/ABA_routing_transit_number#Check_digit
     */
    private function hasValidAbaCheckDigit(string $routingNumber): bool
    {
        $digits = str_split($routingNumber);

        $checksum = 3 * ($digits[0] + $digits[3] + $digits[6])
            + 7 * ($digits[1] + $digits[4] + $digits[7])
            + ($digits[2] + $digits[5] + $digits[8]);

        return $checksum % 10 === 0;
    }
}
