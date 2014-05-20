<?php namespace SammyK;

use Facebook\FacebookHttpable;

class FacebookStreamHttpClient implements FacebookHttpable {

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
    
    $options = array(
      'http' => array(
        'method' => $method,
        'timeout' => 60,
        'ignore_errors' => true
      ),
      'ssl' => array(
        'verify_peer' => true,
        'cafile' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fb_ca_chain_bundle.crt',
      ),
    );

    if ($parameters) {
      $options['http']['content'] = http_build_query($parameters);

      $this->addRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    }

    $options['http']['header'] = $this->compileHeader();

    $context = stream_context_create($options);
    $this->rawResponse = file_get_contents($url, false, $context);

    if ($this->rawResponse === false || empty($http_response_header)) {
      $this->clientErrorCode = 660;
      $this->clientErrorMessage = 'Stream returned an empty response';
      return false;
    }

    $this->responseHeaders = self::formatHeadersToArray($http_response_header);
    $this->responseHttpStatusCode = self::getStatusCodeFromHeader($this->responseHeaders['http_code']);

    return $this->rawResponse;
  }

  /**
   * Formats the headers for use in the stream wrapper
   *
   * @return string
   */
  public function compileHeader() {
    $header = [];
    foreach($this->requestHeaders as $k => $v) {
      $header[] = $k . ': ' . $v;
    }

    return implode("\r\n", $header);
  }

  /**
   * Converts array of headers returned from the wrapper into
   * something standard
   *
   * @param array $rawHeaders
   *
   * @return array
   */
  public static function formatHeadersToArray(array $rawHeaders) {
    $headers = array();

    foreach ($rawHeaders as $line) {
      if (strpos($line, ':') === false)
      {
        $headers['http_code'] = $line;
      }
      else
      {
        list ($key, $value) = explode(': ', $line);
        $headers[$key] = $value;
      }
    }

    return $headers;
  }

  /**
   * Pulls out the HTTP status code from a response header
   *
   * @param string $header
   *
   * @return int
   */
  public static function getStatusCodeFromHeader($header) {
    preg_match('|HTTP/\d\.\d\s+(\d+)\s+.*|', $header, $match);
    return (int) $match[1];
  }

}
