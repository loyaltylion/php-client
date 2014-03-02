# LoyaltyLion php-client

## Usage

``` php
require('loyaltylion/main.php');

$lion = new LoyaltyLion_Client($token, $secret);

// track an activity
$response = $lion->events->track('comment', 23, 'customer@example.com');

if (!$response->success) {
  echo $response->error;
}

// track an order
$response = $lion->orders->create(array(
  'merchant_id' => 929385923,
  'customer_id' => 50295,
  'customer_email' => 'julia@example.com',
  'total' => '399.99',
  'total_shipping' => '0.00',
  'payment_status' => 'not_paid',
));

// set order state
$lion->orders->setCancelled(929385923);
$lion->orders->setPaid(929385923);
$lion->orders->setRefunded(929385923);

// add partial payments and refunds
$lion->orders->addPayment(929385923, array(
  'amount' => '200.00',
));

$lion->orders->addRefund(929385923, array(
  'amount' => '100.00',
));

```

## Changelog

**2014-03-02**

* removed namespaces to support older PHP versions
* now only supports LoyaltyLion v2 API