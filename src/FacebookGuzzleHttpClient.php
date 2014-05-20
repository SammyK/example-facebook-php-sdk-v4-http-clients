<?php namespace SammyK;

use Facebook\FacebookHttpable;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;

class FacebookGuzzleHttpClient implements FacebookHttpable {

  /**
   * @var array The headers to be sent with the request
   */
  protected $requestHeaders = array();

  /**
   * @var array The headers received from the response
   */
  protected $responseHeaders = array();

  /**
   * @var int The HTTP status code returned from the server
   */
  protected $responseHttpStatusCode = 0;

  /**
   * @var string The client error message
   */
  protected $clientErrorMessage = '';

  /**
   * @var int The client error code
   */
  protected $clientErrorCode = 0;

  /**
   * @var string|boolean The raw response from the server
   */
  protected $rawResponse;

  /**
   * @var \GuzzleHttp\Client The Guzzle client
   */
  protected static $guzzleClient;

  public function __construct()
  {
    self::$guzzleClient = new Client();
  }

  /**
   * The headers we want to send with the request
   *
   * @param string $key
   * @param string $value
   */
  public function addRequestHeader($key, $value) {
    $this->requestHeaders[$key] = $value;
  }

  /**
   * The headers returned in the response
   *
   * @return array
   */
  public function getResponseHeaders() {
    return $this->responseHeaders;
  }

  /**
   * The HTTP status response code
   *
   * @return int
   */
  public function getResponseHttpStatusCode() {
    return $this->responseHttpStatusCode;
  }

  /**
   * The error message returned from the client
   *
   * @return string
   */
  public function getErrorMessage() {
    return $this->clientErrorMessage;
  }

  /**
   * The error code returned by the client
   *
   * @return int
   */
  public function getErrorCode() {
    return $this->clientErrorCode;
  }

  /**
   * Sends a request to the server
   *
   * @param string $url The endpoint to send the request to
   * @param string $method The request method
   * @param array  $parameters The key value pairs to be sent in the body
   *
   * @return string|boolean Raw response from the server or false on fail
   */
  public function send($url, $method = 'GET', $parameters = array()) {

    $options = [];
    if ($parameters) {
      $options = ['body' => $parameters];
    }

    $request = self::$guzzleClient->createRequest($method, $url, $options);

    foreach($this->requestHeaders as $k => $v) {
      $request->setHeader($k, $v);
    }

    try {
      $this->rawResponse = self::$guzzleClient->send($request);
    } catch (RequestException $e) {
      $this->rawResponse = $e->getResponse();
    } catch (TransferException $e) {
      $this->clientErrorCode = $e->getCode();
      $this->clientErrorMessage = $e->getMessage();
      return false;
    }

    $this->responseHttpStatusCode = $this->rawResponse->getStatusCode();
    $this->responseHeaders = $this->rawResponse->getHeaders();

    return $this->rawResponse->getBody();
  }

}
