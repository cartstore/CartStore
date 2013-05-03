<?php

	class realex {
var $code, $title, $description, $enabled, $responses;

// class constructor
    function realex() {
      $this->code = 'realex';
      $this->title = MODULE_PAYMENT_REALEX_TEXT_TITLE;
      $this->card =  MODULE_PAYMENT_REALEX_TEXT_SERVICE_DESCRIPTION;
      $this->description = MODULE_PAYMENT_REALEX_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_PAYMENT_REALEX_STATUS == 'True') ? true : false);
    }

// class methods
    // this method returns the javascript that will validate the form entry
    //** Checkout_Payment
    function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.realexpay_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.realexpay_cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_REALEX_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_REALEX_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

    // this method returns the html that creates the input form
        //** Checkout_Payment
	
    function selection() {
      global $order;

      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }
      $today = getdate(); 
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }
     
      $selection = array('id' => $this->code,
						 'module' => $this->title,
						 'card' => $this->card,
                        'fields' => array(array('title' => MODULE_PAYMENT_REALEX_TEXT_SERVICE_DESCRIPTION,
												'field' => MODULE_PAYMENT_REALEX_TEXT_SERVICE_DESCRIPTION2),
										  array('title' => MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_OWNER,
                                                'field' => tep_draw_input_field('realexpay_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                          array('title' => MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_NUMBER,
                                                'field' => tep_draw_input_field('realexpay_cc_number')),
                                          array('title' => CVN_NUMBER,
                                                'field' => tep_draw_input_field('cvn_number'). CVN_EXPLANATION),
                                          array('title' => MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_EXPIRES,
                                                'field' => tep_draw_pull_down_menu('realexpay_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('realexpay_cc_expires_year', $expires_year))));
      return $selection;
    }

    // this method is called before the data is sent to the credit card processor
    // here you can do any field validation that you need to do
    // we also set the global variables here from the form values
        //** Checkout_Payment
    function pre_confirmation_check() {
      global $HTTP_POST_VARS;

      include(DIR_WS_CLASSES . 'cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($HTTP_POST_VARS['realexpay_cc_number'], $HTTP_POST_VARS['realexpay_cc_expires_month'], $HTTP_POST_VARS['realexpay_cc_expires_year']);

      $error = '';
      switch ($result) {
        case -1:
          $error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($cc_validation->cc_number, 0, 4));
          break;
        case -2:
        case -3:
        case -4:
          $error = TEXT_CCVAL_ERROR_INVALID_DATE;
          break;
        case false:
          $error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
          break;
      }

      if ( ($result == false) || ($result < 1) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&realexpay_cc_owner=' . urlencode($HTTP_POST_VARS['realexpay_cc_owner']) . '&realexpay_cc_type=' . urlencode($HTTP_POST_VARS['realexpay_cc_type']) . '&realexpay_cc_expires_month=' . $HTTP_POST_VARS['realexpay_cc_expires_month'] . '&realexpay_cc_expires_year=' . $HTTP_POST_VARS['realexpay_cc_expires_year'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_owner = $HTTP_POST_VARS['realexpay_cc_owner']; 
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
      $this->cc_card_type = $cc_validation->cc_type;
	  $this->cvn_number = $HTTP_POST_VARS['cvn_number'];

      
    }

    // this method returns the data for the confirmation page
    //**check_confirmation
    function confirmation() {
      global $HTTP_POST_VARS;
      global $order;
      $confirmation = array('title' => $this->title,
                           'fields' => array(array('title' => MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_OWNER,
                                                   'field' => $HTTP_POST_VARS['realexpay_cc_owner']),
                       array('title' => MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_TYPE,
                                                   'field' =>  $this->cc_card_type),
                                             array('title' => MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_NUMBER,
                                                   'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                             array('title' => 'CVN Number', 'field' =>  $this->cvn_number),                                           array('title' => MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_EXPIRES,
                                                   'field' => strftime('%B, %Y', mktime(0,0,0,$HTTP_POST_VARS['realexpay_cc_expires_month'], 1, '20' . $HTTP_POST_VARS['realexpay_cc_expires_year']))))); 

      return $confirmation;
    }

    // this method performs the authorization by sending the data to the processor, and getting the result
    //** Written in checkout_confimration
    
    function process_button() {
      global $order;

      $process_button_string = tep_draw_hidden_field('merchantid', MODULE_PAYMENT_REALEX_MERCHANT_ID) .
                               tep_draw_hidden_field('amount', number_format(($order->info['total']*100), 0, '', '')) .
			       tep_draw_hidden_field('amount2',$order->info['total']) .
                               tep_draw_hidden_field('ponum', date('Ymdhis')) .
                               tep_draw_hidden_field('cvn', ($this->cvn_number)) .
		                       tep_draw_hidden_field('cctype', ($this->cc_card_type)) . 
   		                       tep_draw_hidden_field('ccowner',$this->cc_card_owner) .
			                   tep_draw_hidden_field('currency', ($order->info['currency'])) .
                               tep_draw_hidden_field('creditCard1', $this->cc_card_number) .
                               tep_draw_hidden_field('exdate1', $this->cc_expiry_month) .
                               tep_draw_hidden_field('exdate2', substr($this->cc_expiry_year, -2)) ;
		
      return $process_button_string;
    }

    // this method gets called after the processing is done but before the app server 
    // accepts the result.  It is used to check for errors.
    //** called by checkout_process

    function before_process() {
      global $HTTP_POST_VARS;

$timestamp = strftime("%Y%m%d%H%M%S");
mt_srand((double)microtime()*1000000);

//** These values passed in from function process_button

$merchantid = $HTTP_POST_VARS['merchantid'];
$amount = $HTTP_POST_VARS['amount'];
$ccnum =$HTTP_POST_VARS['creditCard1'];
$ccname = $HTTP_POST_VARS['ccowner'];

$ccctype = $HTTP_POST_VARS['currency'];
$amount2 = $HTTP_POST_VARS['amount2'];

$curr = $HTTP_POST_VARS['currency'];
$expdate = $HTTP_POST_VARS['exdate1'] . $HTTP_POST_VARS['exdate2'];


$cardtype = $HTTP_POST_VARS['cctype'];
if (ereg('^Master Card$',  $cardtype)) {
        $cardtype = 'MC';
}
if (ereg('^American Express$',  $cardtype)) {
        $cardtype = 'AMEX';
}

$cvn = $HTTP_POST_VARS['cvn'];
if (!empty($cvn))
{
 $presind = "1";
}
else
$presind = "4";




// I'm using a random number as the orderid - you probably have a better way.
$orderid = $timestamp."-".mt_rand(1, 999);
$secret = MODULE_PAYMENT_REALEX_SHARED_SECRET;
// creating the hash.
$tmp = "$timestamp.$merchantid.$orderid.$amount.$curr.$ccnum";
$md5hash = md5($tmp);
$tmp = "$md5hash.$secret";
$md5hash = md5($tmp);


// fire up the xml parser...  for curl compiled as a library
$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "cDataHandler");

//include('osxmlparser.php');
//$os_xmlparser = new osxmlparser();


$xml = "<request type='auth' timestamp='$timestamp'>
	<merchantid>$merchantid</merchantid>
	<account>internet</account>
	<orderid>$orderid</orderid>
	<amount currency='$curr'>$amount</amount>
	<card> 
		<number>$ccnum</number>
		<expdate>$expdate</expdate>
		<type>$cardtype</type> 
		<chname>$ccname</chname> 
	<cvn>
      <number>$cvn</number>
      <presind>$presind</presind>
    </cvn>

	</card> 
	<autosettle flag='1'/>
	<comments><comment id='1'>$amount2</comment></comments>
		<tssinfo>
		<address type='billing'>
			<country>ie</country>
		</address>
		<custnum>$ccctype</custnum>
		<prodid>$cccctype</prodid>
		<varref>$ccccctype</varref>
	</tssinfo>
	<md5hash>$md5hash</md5hash>
</request>";
    
    
    
$URL = MODULE_PAYMENT_REALEX_URL;

// send it to payandshop.com
$ch = curl_init();    
curl_setopt($ch, CURLOPT_URL, "https://epage.payandshop.com/epage-remote.cgi");
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_USERAGENT, "payandshop.com php version 0.9"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // this line makes it work under https
$response = curl_exec ($ch);     
curl_close ($ch); 

for ($i = 0; $i < count($return_message_array); $i++) {
    $response = $response.$return_message_array[$i];
}


// fix it up good.
$response = eregi_replace ( "[[:space:]]+", " ", $response );
$response = eregi_replace ( "[\n\r]", "", $response );

preg_match("/<result>(.*?)<\/result>/i",$response,$matches);
$XMLresult = $matches[1];

preg_match("/<message>(.*)<\/message>/i",$response,$matches);
$XMLmessage = $matches[1];

$resultString = $XMLresult."     ".$XMLmessage;

/*$filename ="/home/samba/payandshop/intranet/cathal/catalog/includes/modules/payment/abc2.htm";
$handle2= fopen($filename,"a");
fputs($handle2,$XMLmessage."\n\n");
fputs($handle2, $XMLresult."\n\n");
fclose($handle2);
*/

$realexResult = $XMLresult;

	if ($realexResult != "00"){
	
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . urlencode($resultString), 'SSL', true, false));
        }


}



    
//**called after process complete

    function after_process() {
          return false;
    }

    function get_error() {
      global $_GET;

      $msg = "";
      if (stripslashes(urldecode($_GET['response_text'])) != "")
        $msg = stripslashes(urldecode($_GET['response_text']));
      else if (stripslashes(urldecode($_GET['error'])) != "")
        $msg = stripslashes(urldecode($_GET['error']));
      $error = array('title' => MODULE_PAYMENT_REALEX_TEXT_ERROR,
                     'error' => $msg);
      return $error;
    }



    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_REALEX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Realex Module', 'MODULE_PAYMENT_REALEX_STATUS', 'True', 'Do you want to use Realex Payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant ID', 'MODULE_PAYMENT_REALEX_MERCHANT_ID', 'sec0001', 'The merchant id used for the Realex Payments service', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shared Secret', 'MODULE_PAYMENT_REALEX_SHARED_SECRET', 'secret', 'The shared secret used for the Realex Payments service', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Curl Location', 'MODULE_PAYMENT_REALEX_CURL_LOC', '/usr/bin/curl', 'This is where Curl is located on your server', '6', '0', now())");
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Realex URL', 'MODULE_PAYMENT_REALEX_URL', 'https://epage.payandshop.com/epage-remote.cgi', 'This is the URL of the Realex Gateway', '6', '0', now())");

      
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_REALEX_STATUS', 'MODULE_PAYMENT_REALEX_MERCHANT_ID','MODULE_PAYMENT_REALEX_SHARED_SECRET','MODULE_PAYMENT_REALEX_CURL_LOC','MODULE_PAYMENT_REALEX_URL');
    }
  




}
?>
