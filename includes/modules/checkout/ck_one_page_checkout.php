<?php
/*
  $Id: ck_one_page_checkout
  Installer module by G.L. Walker
  http://wsfive.com
  Developed for use with:
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Released under the GNU General Public License
*/

  class ck_one_page_checkout {
    var $code = 'ck_one_page_checkout';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function ck_one_page_checkout() {
    	global $PHP_SELF;

      $this->title = ONEPAGE_CHECKOUT_TITLE;
      $this->description = ONEPAGE_CHECKOUT_DESCRIPTION;

      if ( defined('ONEPAGE_CHECKOUT_ENABLED') ) {
        $this->sort_order = ONEPAGE_SORT_ORDER;
        $this->enabled = (ONEPAGE_CHECKOUT_ENABLED == 'True');
      }     
    }

    function execute() {
      global $PHP_SELF, $header, $oscTemplate;
      if (preg_match("/checkout.php/", $PHP_SELF)) {
		$header = '<style>.pstrength-minchar {font-size : 10px;}.fieldRed{ background:#F00;}.buttonP { margin:0 0 0 -6px; text-indent: -9999px;cursor: pointer; width: 16px; height: 16px; float: right; text-align: center; background:url(ext/jquery/ui/redmond/images/ui-icons_2e83ff_256x240.png) 0 -190px no-repeat; }.dec { background-position: -16px -190px; float:left; }</style>' . "\n";
        $header .= '<script src="includes/modules/checkout/ext/jquery/jquery.ajaxq-0.0.1.js"></script>' . "\n";
        $header .= '<script src="includes/modules/checkout/ext/jquery/jQuery.pstrength.js"></script>' . "\n";
        $header .= '<script src="includes/modules/checkout/includes/modules/checkout.js"></script>' . "\n";
		$oscTemplate->addBlock($header, 'header_tags');		
	  }  
	}




    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined( 'ONEPAGE_CHECKOUT_ENABLED' );
    }

    function install() { 
		tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable One Page Checkout', 'ONEPAGE_CHECKOUT_ENABLED', 'True', 'Enable one page checkout?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())" );
	  
		tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Default Address Country', 'ONEPAGE_DEFAULT_COUNTRY', '223', 'Default country for new address and for checking out without account', '6', '2', 'tep_cfg_pull_down_country_list(', now())" );
		
		tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Account Creation', 'ONEPAGE_ACCOUNT_CREATE', 'create', '<strong>required</strong> - Password is required<br><strong>optional</strong> - Password is optional, no account created if empty<br><strong>create</strong> - Password is optional, account created with random password', '6', '3', 'tep_cfg_select_option(array(\'required\', \'optional\', \'create\'), ', now())" );
		
		tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Require Login', 'ONEPAGE_LOGIN_REQUIRED', 'false', 'Require customer to be logged in to proceed through checkout?', '6', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())" );	  
	  
		tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Send Debug Emails To:', 'ONEPAGE_DEBUG_EMAIL_ADDRESS', 'set.me.to.valid@email.address', 'This will send the debug emails to the specified email address these emails are used for debugging', '6', '5', now())" );
	  
		tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Telephone Required', 'ONEPAGE_TELEPHONE', 'True', 'Telephone will be a required field?', '6', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())" );
		
		tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Ship Methods', 'ONEPAGE_CHECKOUT_HIDE_SHIPPING', 'false', 'Dont show shipping and handling address checkbox or ship methods if weight of products = 0', '6', '7', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())" );
		
		tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Addresses Layout', 'ONEPAGE_ADDR_LAYOUT', 'vertical', 'Layout style', '6', '8', 'tep_cfg_select_option(array(\'vertical\', \'horizontal\'), ', now())" );
		
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'ONEPAGE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '9', now())");
	  
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('ONEPAGE_CHECKOUT_ENABLED', 'ONEPAGE_DEFAULT_COUNTRY', 'ONEPAGE_ACCOUNT_CREATE', 'ONEPAGE_LOGIN_REQUIRED', 'ONEPAGE_DEBUG_EMAIL_ADDRESS', 'ONEPAGE_TELEPHONE', 'ONEPAGE_CHECKOUT_HIDE_SHIPPING', 'ONEPAGE_ADDR_LAYOUT', 'ONEPAGE_SORT_ORDER');
    }
  }