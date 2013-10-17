# LoyaltyLion php-client

## Usage

``` php
require('loyaltylion/main.php');

$lion = new \LoyaltyLion\Client($token, $secret);

// track an activity
$response = $lion->track('purchase', 23, 'customer@example.com', array(
  'merchant_id' => 58231,
  'total' => 24.95,
));

if (!$response->success) {
  echo $response->error;
}

```
