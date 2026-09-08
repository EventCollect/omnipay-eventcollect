# Omnipay: EventCollect

**EventCollect payment processing driver for the Omnipay PHP payment processing library**

<!-- [![Latest Stable Version](https://poser.pugx.org/EventCollect/omnipay-eventcollect/version.png)](https://packagist.org/packages/EventCollect/omnipay-eventcollect)
[![Total Downloads](https://poser.pugx.org/EventCollect/omnipay-eventcollect/d/total.png)](https://packagist.org/packages/EventCollect/omnipay-eventcollect) -->

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements EventCollect support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply require `league/omnipay` and `EventCollect/omnipay-eventcollect` with Composer:

```
composer require league/omnipay EventCollect/omnipay-eventcollect
```

## Basic Usage

The following gateways are provided by this package:

* EventCollect

```php
$gateway = Omnipay::create('EventCollect');
$gateway->setApiKey('[API_KEY]');

try {
    $params = array(
        'amount'                => 10.00,
        'card'                  => $card,
        'payment_method'        => 'card'
    );

    $response = $gateway->purchase($params)->send();

    if ($response->isSuccessful()) {
        // successful
    } else {
        throw new ApplicationException($response->getMessage());
    }
} catch (ApplicationException $e) {
    throw new ApplicationException($e->getMessage());
}
```

### ACH / bank account payments

Bank accounts are charged through the same `purchase()` request — pass a `bankAccount`
instead of a `card`:

```php
$response = $gateway->purchase([
    'amount' => 50.00,
    'currency' => 'USD',
    'bankAccount' => [
        'accountNumber' => '123456789',
        'routingNumber' => '021000021',
        'accountType' => 'checking',          // checking | savings
        'accountHolderType' => 'individual',  // individual | business
    ],
    'billingFirstName' => 'Jane',
    'billingLastName' => 'Doe',
    'billingAddress1' => '1 Main St',
    'billingCity' => 'Boston',
    'billingPostcode' => '02101',
])->send();
```

`bankAccount`, `card` and `source` are mutually exclusive — a request carries exactly one
of them. Malformed bank account details throw an `InvalidBankAccountException` before the
request is sent.

Two further rules are enforced by the API rather than the driver, and surface through
`$response->getMessage()`:

* ACH is available for `USD` and `CAD` only, and the currency must be enabled for bank
  account payments on your API key.
* `billingCompany` is required when `accountHolderType` is `business`.

Bank accounts cannot be stored as payment sources, so `createCard()` and `updateCard()`
remain card-only.

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release announcements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/EventCollect/omnipay-eventcollect/issues),
or better yet, fork the library and submit a pull request.
