<?php
namespace LoyaltyLion;

require('lib/connection.php');

class Client {

  private $token;
  private $secret;
  private $connection;
  private $base_uri = 'http://api.loyaltylion.com/v1';

  public function __construct($token, $secret) {
    $this->token = $token;
    $this->secret = $secret;

    if (empty($this->token) || empty($this->secret)) {
      throw new Exception("Please provide a valid token and secret (token: ${token}, secret: ${secret})");
    }

    $this->connection = new \LoyaltyLion\Connection($this->token, $this->secret, $this->base_uri);
  }

  /**
   * Track an activity, such as a purchase
   * 
   * @param  [type] $name             The activity name, as defined by you
   * @param  [type] $customer_id      The ID of the current logged in customer
   * @param  [type] $customer_email   The email of the current logged in customer
   * @param  array  $properties       Activity specific properties
   * @return object                   An object with information about the request. If the track 
   *                                  was successful, object->success will be true.
   */
  public function track($name, $customer_id, $customer_email, array $properties = array()) {
    $params = array(
      'name' => $name,
      'date' => date('c'),
      'customer_id' => $customer_id,
      'customer_email' => $customer_email,
      'properties' => $properties,
    );

    $response = $this->connection->post('/events', $params);

    if (isset($response->error)) {
      // this kind of error is from curl itself, e.g. a request timeout, so
      // just return that error
      return (object) array(
        'success' => false,
        'error' => $response->error,
      );
    }

    $result = array(
      'success' => (string) $response->status == '201'
    );

    if (!$result['success']) {
      // even if curl succeeded, it can still fail if the request was invalid - we
      // usually have the error as the body so just stick that in
      $result['error'] = $response->body;
    }

    return (object) $result;
  }

  public function getCustomerAuthToken($customer_id) {
    $params = array(
      'customer_id' => $customer_id,
    );

    $response = $this->connection->post('/customers/authenticate', $params);

    if (isset($response->error)) {
      echo "LoyaltyLion client error: " . $response->error;
    }
    
    // should have got json back
    if (empty($response->body)) return null;

    $json = json_decode($response->body);

    if ($json && $json->auth_token) {
      return $json->auth_token;
    } else {
      return null;
    }
  }

  /**
   * Makes a test (ping) connection to the API server and returns true if
   * the server responded successfully. Useful for sanity checking during development.
   * 
   * @return boolean True if could contact the API
   */
  public function test() {

  }
}