# Omnipay: EventCollect

**EventCollect payment processing driver for the Omnipay PHP payment processing library**

<!-- [![Latest Stable Version](https://poser.pugx.org/EventCollect/omnipay-eventcollect/version.png)](https://packagist.org/packages/EventCollect/omnipay-eventcollect)
[![Total Downloads](https://poser.pugx.org/EventCollect/omnipay-eventcollect/d/total.png)](https://packagist.org/packages/EventCollect/omnipay-eventcollect) -->

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Authorize.Net support for Omnipay.

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
$gateway->setMerchantId('[MERCHANT_ID]');
$gateway->setApiPasscode('[API_PASSCODE]');


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
