<?php

namespace Omnipay\EventCollect\Exception;

use Exception;
use Omnipay\Common\Exception\OmnipayException;

/**
 * Invalid Bank Account Exception
 *
 * Thrown when a bank account is invalid or missing required fields.
 */
class InvalidBankAccountException extends Exception implements OmnipayException
{
}
