<?php
namespace LoyaltyLion;

class Connection {

  private $token;
  private $secret;
  private $auth;
  private $base_uri;
  private $timeout = 5;

  public function __construct($token, $secret, $base_uri) {
    $this->token = $token;
    $this->secret = $secret;
    $this->base_uri = $base_uri;

    $this->auth = array(
      'token' => $this->token,
      'secret' => $this->secret,
    );
  }

  public function post($path, $params) {
    return $this->request('POST', $path, $params);
  }

  private function request($method, $path, $params) {

    // merge in auth params
    $params += $this->auth;

    $query_string = http_build_query($params);

    $options = array(
      CURLOPT_URL => $this->base_uri . $path,
      CURLOPT_USERAGENT => 'loyaltylion-php-client-v1.0.0',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => $this->timeout,
      CURLOPT_HEADER => false,
    );
    
    if ($method == 'POST') {
      $options += array(
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $query_string,
      );
    }
    
    // now make the request
    $curl = curl_init();
    curl_setopt_array($curl, $options);

    $body = curl_exec($curl);
    $headers = curl_getinfo($curl);
    $error_code = curl_errno($curl);
    $error_msg = curl_error($curl);
    
    if ($error_code !== 0) {
      $response = array(
        'error' => $error_msg,
      );
    } else {
      $response = array(
        'status' => $headers['http_code'],
        'headers' => $headers,
        'body' => $body,
      );
    }

    return (object) $response;
  }
}