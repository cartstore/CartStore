<?php
ini_set("display_errors","on");
error_reporting(E_ALL);
/**
 * Social Runner Connector
 * Handles function call from Categories page to process new / updated products
 *
 * @package Social Runner Connector
 * @author Ian Stapeton <ian@social-runner.com>
 * @copyright Copyright (c) 2011 Social Runner
 */

//Define some operating parameters
define('SR_PRODUCT_NEW', 'Added');
define('SR_PRODUCT_UPDATED', 'Updated');
define('SR_PRODUCT_NEWIMG', 'NewImage');
define('SR_PRODUCT_REDUCED', 'NewPrice');
define('SR_PRODUCT_INSTOCK', 'InStock');
define('SR_PRODUCT_PUSH', 'PushItem');
define('SR_PROMOTION_PUSH', 'PushPromotion');
define('SR_PROMOTION_NEW', 'NewPromotion');


/**
 * Entry point for Connector.
 * Determines which API call to make, if any
 *
 * @global <int> $languages_id Current language in use
 * @global <array> $HTTP_GET_VARS Http Request params
 * @global <array> $sr_oldProductData If operation is update, contains pre-save fields, else null
 * @param <int> $pid Product ID (updated product or ID of new product)
 */
function SrBroadcast($pid) {
    //Ick
    global $languages_id, $HTTP_GET_VARS, $sr_oldProductData;

    //Only process if the plugin is installed and enabled
    if (defined('SR_ENABLED') && SR_ENABLED == true && tep_not_null($pid)) {



        //Double check what operation is being performed
        $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
        if (tep_not_null($action)) {
            //Load the product
            $product_query = tep_db_query("select pd.products_name, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$pid . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
            $product = tep_db_fetch_array($product_query);
            $product_url = tep_catalog_href_link('product_info.php', 'products_id=' . $pid);

            //Only perform any actions if the product is available
            if ($product['products_status'] == 1) {
                $apiMethod = null;
                switch ($action) {
                    case 'insert_product':
                        //Creating a new product
                        $apiMethod = SR_PRODUCT_NEW;
                        break;
                    case 'update_product':
                        //Decide what action is required
                        //New Price > In Stock > New Image > Updated

                        if ($sr_oldProductData) {
                            //Old product data is defined
                            $apiMethod = null;

                            //Price?
                            if ($product['products_price'] < $sr_oldProductData['products_price']) {
                                //Price has been reduced
                                $apiMethod = SR_PRODUCT_REDUCED;

                            } elseif ($sr_oldProductData['products_status'] == 0 && $product['products_status'] == 1) {
                                //Was out of stock, now back in stock
                                $apiMethod = SR_PRODUCT_INSTOCK;

                            } elseif (count($_FILES) > 0) {
                                //New or updated images
                                $apiMethod = SR_PRODUCT_NEWIMG;

                            } else {
                                //Something has been updaed
                                $apiMethod = SR_PRODUCT_UPDATED;
                            }
                        }
                        break;
                    case 'promote':
                        //Product push
                        $apiMethod = SR_PRODUCT_PUSH;
                        break;
                    case 'insert':
                        //New special
                        $apiMethod = SR_PROMOTION_NEW;
                        break;
                    case "promote_special":
                        //Promotion push
                        $apiMethod = SR_PROMOTION_PUSH;
                        break;
                 }
                 //And dispatch the broadcast
                 if ($apiMethod != null) {
                    sr_dispatchApiCall($apiMethod, $pid, $product['products_name'], $product_url );
                 }
            }
        }
    }
}

/**
 * Handles making SocialRunner API calls and processes response
 *
 * @param string $apiMethod
 * @param string $id
 * @param string $name
 * @param string $url
 */
function sr_dispatchApiCall($apiMethod, $id, $name, $url) {

    //This should be as silent as possible
    try {
        $apiClient = new Pest(SR_ENDPOINT);

        $repsonse = $apiClient->Post($apiMethod, array(
            'apiToken' => SR_APITOKEN,
            'apiSecret' => SR_APISECRET,
            'itemId' => $id,
            'itemName' => $name,
            'linkUrl' => $url
        ));
    }
    catch (Exception $ex) {
        error_log($ex->getMessage());
    }
}

/**
 * Pest is a REST client for PHP.
 *
 * See http://github.com/educoder/pest for details.
 *
 * This code is licensed for use, modification, and distribution
 * under the terms of the MIT License (see http://en.wikipedia.org/wiki/MIT_License)
 */
class Pest {
  public $curl_opts = array(
  	CURLOPT_RETURNTRANSFER => true,  // return result instead of echoing
  	CURLOPT_SSL_VERIFYPEER => false, // stop cURL from verifying the peer's certificate
  	CURLOPT_FOLLOWLOCATION => true,  // follow redirects, Location: headers
  	CURLOPT_MAXREDIRS      => 10     // but dont redirect more than 10 times
  );

  public $base_url;
  public $last_response;
  public $last_request;

  public function __construct($base_url) {
    if (!function_exists('curl_init')) {
  	    throw new Exception('CURL module not available! Pest requires CURL. See http://php.net/manual/en/book.curl.php');
  	}

    // if possible follow redirects, Location: headers
    if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
        $this->curl_opts[CURLOPT_FOLLOWLOCATION] = true;
    }

    $this->base_url = $base_url;
  }

  public function post($url, $data) {
    $data = (is_array($data)) ? http_build_query($data) : $data;

    $curl_opts = $this->curl_opts;
    $curl_opts[CURLOPT_POST] = true;
    $curl_opts[CURLOPT_HTTPHEADER] = array('Content-Length: '.strlen($data));
    $curl_opts[CURLOPT_POSTFIELDS] = $data;

    $curl = $this->prepRequest($curl_opts, $this->base_url . $url);
    $body = $this->doRequest($curl);

    $body = $this->processBody($body);

    return $body;
  }

  public function lastBody() {
    return $this->last_response['body'];
  }

  public function lastStatus() {
    return $this->last_response['meta']['http_code'];
  }

  public function processBody($body) {
    // Override this in classes that extend Pest.
    // The body of every GET/POST/PUT/DELETE response goes through
    // here prior to being returned.
    return $body;
  }

  public function processError($body) {
    // Override this in classes that extend Pest.
    // The body of every erroneous (non-2xx/3xx) GET/POST/PUT/DELETE
    // response goes through here prior to being used as the 'message'
    // of the resulting Pest_Exception
    return $body;
  }

  private function prepRequest($opts, $url) {
    $curl = curl_init($url);

    foreach ($opts as $opt => $val)
      curl_setopt($curl, $opt, $val);

    $this->last_request = array(
      'url' => $url
    );

    if (isset($opts[CURLOPT_CUSTOMREQUEST]))
      $this->last_request['method'] = $opts[CURLOPT_CUSTOMREQUEST];
    else
      $this->last_request['method'] = 'GET';

    if (isset($opts[CURLOPT_POSTFIELDS]))
      $this->last_request['data'] = $opts[CURLOPT_POSTFIELDS];

    return $curl;
  }

  private function doRequest($curl) {
    $body = curl_exec($curl);
    $meta = curl_getinfo($curl);

    $this->last_response = array(
      'body' => $body,
      'meta' => $meta
    );

    curl_close($curl);

    $this->checkLastResponseForError();

    return $body;
  }

  private function checkLastResponseForError() {
    $meta = $this->last_response['meta'];
    $body = $this->last_response['body'];

    if (!$meta)
      return;

    $err = null;
    switch ($meta['http_code']) {
      case 400:
        throw new Pest_BadRequest($this->processError($body));
        break;
      case 401:
        throw new Pest_Unauthorized($this->processError($body));
        break;
      case 403:
        throw new Pest_Forbidden($this->processError($body));
        break;
      case 404:
        throw new Pest_NotFound($this->processError($body));
        break;
      case 405:
        throw new Pest_MethodNotAllowed($this->processError($body));
        break;
      case 409:
        throw new Pest_Conflict($this->processError($body));
        break;
      case 410:
        throw new Pest_Gone($this->processError($body));
        break;
      case 422:
        // Unprocessable Entity -- see http://www.iana.org/assignments/http-status-codes
        // This is now commonly used (in Rails, at least) to indicate
        // a response to a request that is syntactically correct,
        // but semantically invalid (for example, when trying to
        // create a resource with some required fields missing)
        throw new Pest_InvalidRecord($this->processError($body));
        break;
      default:
        if ($meta['http_code'] >= 400 && $meta['http_code'] <= 499)
          throw new Pest_ClientError($this->processError($body));
        elseif ($meta['http_code'] >= 500 && $meta['http_code'] <= 599)
          throw new Pest_ServerError($this->processError($body));
        elseif (!$meta['http_code'] || $meta['http_code'] >= 600) {
          throw new Pest_UnknownResponse($this->processError($body));
        }
    }
  }
}


class Pest_Exception extends Exception { }
class Pest_UnknownResponse extends Pest_Exception { }
/* 401-499 */ class Pest_ClientError extends Pest_Exception {}
/* 400 */ class Pest_BadRequest extends Pest_ClientError {}
/* 401 */ class Pest_Unauthorized extends Pest_ClientError {}
/* 403 */ class Pest_Forbidden extends Pest_ClientError {}
/* 404 */ class Pest_NotFound extends Pest_ClientError {}
/* 405 */ class Pest_MethodNotAllowed extends Pest_ClientError {}
/* 409 */ class Pest_Conflict extends Pest_ClientError {}
/* 410 */ class Pest_Gone extends Pest_ClientError {}
/* 422 */ class Pest_InvalidRecord extends Pest_ClientError {}
/* 500-599 */ class Pest_ServerError extends Pest_Exception {}

