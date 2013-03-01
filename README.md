# LoyaltyLion php-client

## Usage

``` php
require('loyaltylion/main.php');

$lion = new \LoyaltyLion\Client($token, $secret);

// track an activity
$response = $lion->track('purchase', 23, 'customer@example.com', 'a4ed', array(
  'order_id' => 58231,
  'total' => 24.95,
));

if (!$response->success) {
  echo $response->error;
}

// get auth token for a customer
$auth_token = $lion->getCustomerAuthToken(23);

```