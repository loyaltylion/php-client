# LoyaltyLion php-client

## Usage

``` php
require('loyaltylion/main.php');

$lion = new LoyaltyLion_Client($token, $secret);

// track an activity
$response = $lion->activities->track('$signup', array(
  'customer_id' => $customer->id,
  'customer_email' => $customer->email,
  'date' => $customer->created_at,
));

if (!$response->success) {
  echo $response->error;
}

// update the state of an activity (e.g. approve a review)
$response = $lion->activities->track('$review', array(
  'merchant_id' => 23523,
  'customer_id' => $customer->id,
  'customer_email' => $customer->email,
  'date' => $customer->created_at,
));

$lion->activities->update('$review', 23523, array(
  'state' => 'approved'
));

// track an order
$response = $lion->orders->create(array(
  'merchant_id' => 929385923,
  'customer_id' => 50295,
  'customer_email' => 'julia@example.com',
  'total' => '399.99',
  'total_shipping' => '0.00',
  'payment_status' => 'not_paid',
));

// full idempotent order update
// - this is the recommended method to update orders as you can call it any
//   time an order's state changes to ensure LoyaltyLion's order is in sync
$response = $lion->orders->update($order->id, array(
  'payment_status' => 'paid',
  'cancellation_status' => 'not_cancelled',
  'refund_status' => 'not_refunded',
  'total_paid' => '399.99',
  'total_refunded' => 0,
));

// if you'd rather only update when a specific state change happens (e.g.
// order is cancelled, paid, etc) you can use these methods instead

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

**2016-03-21**

* Update documentation to reflect correct activity namespaces

**2015-04-22**

* removed deprecated `getCustomerAuthToken` method

**2014-03-02**

* removed namespaces to support older PHP versions
* now only supports LoyaltyLion v2 API
