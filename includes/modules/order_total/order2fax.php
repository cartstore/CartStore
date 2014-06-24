<?php
/*
  $Id: order2fax.php,v 1.0 2006/06/12 18:05:04 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class order2fax {
    var $title, $output;

    function order2fax() {
      $this->code = 'order2fax';
      $this->title = MODULE_ORDER_TOTAL_ORDER2FAX_TEXT_TITLE;
      $this->description = MODULE_ORDER_TOTAL_ORDER2FAX_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_ORDER2FAX_STATUS == 'true') ? true : false);
      $this->output = array();
    }

    function process() {
      return;
    }

    function sendFax() {
      global $email_order;

      if(substr($_SERVER['SCRIPT_FILENAME'], strrpos($_SERVER['SCRIPT_FILENAME'], '/')+1) == 'checkout_process.php') {
         //tecspace.net settings begin
         //$message = "\nsender: ".MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL."\nsubject: order\nuser: ".MODULE_ORDER_TOTAL_ORDER2FAX_USERNAME."\npassword: ".MODULE_ORDER_TOTAL_ORDER2FAX_PASSWORD."\njob: send\nfaxnumber: ".MODULE_ORDER_TOTAL_ORDER2FAX_FAXNUMBER."\nmessage: ".$email_order;
         //$header = 'From: '.MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL."\r\n".'Reply-To: '.MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL;
         //mail('mail2fax@tecspace.net', 'order', $message, $header);
         //tecspace.net settings end

         /**************** Interfax settings begin **************/
         $username  = MODULE_ORDER_TOTAL_ORDER2FAX_USERNAME ; // Enter your Interfax username here
         $password  = MODULE_ORDER_TOTAL_ORDER2FAX_PASSWORD ; // Enter your Interfax password here
         $faxnumber = MODULE_ORDER_TOTAL_ORDER2FAX_FAXNUMBER ; // Enter your designated fax number here in the format +[country code][area code][fax number], for example: +12125554874
         $filetype  = 'TXT'; // If $texttofax is regular text, enter TXT here. If $texttofax is HTML enter HTML here

if ((substr(PHP_VERSION,0,1) < 5) || (!defined (SOAP_FUNCTIONS_ALL)))
// only need nusoap for php version < 5 or not --enable-soap
	require_once(DIR_WS_MODULES .'nusoap.php');

         $client = new soapclient('http://ws.interfax.net/dfs.asmx?wsdl', true);
         $params[] = array('Username'      => $username,
                'Password'        => $password,
                'FaxNumber'       => $faxnumber,
                'Data'            => $email_order,
                'FileType'        => $filetype
                );

        $faxresult = $client->call('SendCharFax', $params);

//        print_r($faxResult);
        /**************** Interfax settings end ****************/


      }
   }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_ORDER2FAX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable order2fax Module', 'MODULE_ORDER_TOTAL_ORDER2FAX_STATUS', 'false', 'Do you want to send orders by fax?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Username', 'MODULE_ORDER_TOTAL_ORDER2FAX_USERNAME', 'Username', 'Interfax Username', '6', '1', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Password', 'MODULE_ORDER_TOTAL_ORDER2FAX_PASSWORD', 'Password', 'Interfax Password', '6', '1', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Fax number', 'MODULE_ORDER_TOTAL_ORDER2FAX_FAXNUMBER', 'Fax number', 'to Receive Orders', '6', '1', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sender email address', 'MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL', '".STORE_OWNER_EMAIL_ADDRESS."', 'for Fax Header', '6', '1', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_ORDER2FAX_STATUS', 'MODULE_ORDER_TOTAL_ORDER2FAX_USERNAME', 'MODULE_ORDER_TOTAL_ORDER2FAX_PASSWORD', 'MODULE_ORDER_TOTAL_ORDER2FAX_FAXNUMBER', 'MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL');
    }

  }
?>