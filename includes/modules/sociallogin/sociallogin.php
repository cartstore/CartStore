<?php
/*
  $Id: sociallogin.php 1739 2012-03-20 00:52:16Z Team LoginRadius $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  class sociallogin {
    var $code, $title, $description, $enabled, $sort_order;

    function sociallogin() {
	   $this->code = 'sociallogin';
       $this->title = MODULE_SOCIAL_LOGIN_TITLE;
       $this->description = MODULE_SOCIAL_LOGIN_DESCRIPTION;
       $this->enabled = ((MODULE_SOCIAL_LOGIN_STATUS == 'true') ? true : false);
       $this->sort_order = MODULE_MODULE_SOCIAL_LOGIN_SORT_ORDER;
	   $this->api_key = MODULE_SOCIAL_LOGIN_API_KEY;
	   $this->api_secret_key = MODULE_SOCIAL_LOGIN_API_SECRET_KEY;
	   $this->email_required = (MODULE_SOCIAL_LOGIN_EMAIL_REQUIRED == 'True');
	   $this->useapi = (MODULE_SOCIAL_LOGIN_USEAPI == 'CURL');
	   $this->link_account = (MODULE_SOCIAL_LOGIN_LINKACCOUNT == 'False');
	   $this->use_redirect = (MODULE_SOCIAL_LOGIN_REDIRECT == 'Javascript');
	   $this->title = MODULE_SOCIAL_LOGIN_TITLE;
	}

    function quote() {} 

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_SOCIAL_LOGIN_STATUS', 'MODULE_SOCIAL_LOGIN_SORT_ORDER','MODULE_SOCIAL_LOGIN_API_KEY','MODULE_SOCIAL_LOGIN_API_SECRET_KEY','MODULE_SOCIAL_LOGIN_EMAIL_REQUIRED','MODULE_SOCIAL_LOGIN_TITLE','MODULE_SOCIAL_LOGIN_USEAPI','MODULE_SOCIAL_LOGIN_LINKACCOUNT', 'MODULE_SOCIAL_LOGIN_REDIRECT');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Social Login', 'MODULE_SOCIAL_LOGIN_STATUS', 'true', 'Do you want to display the Social Login?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SOCIAL_LOGIN_SORT_ORDER', '2', 'Sort order of display.', '6', '2', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('LoginRadius API Key', 'MODULE_SOCIAL_LOGIN_API_KEY', '0', 'Paste LoginRadius API Key here', '6', '0', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('LoginRadius API Secret', 'MODULE_SOCIAL_LOGIN_API_SECRET_KEY', '0', 'Paste LoginRadius API Secret here', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Email Required', 'MODULE_SOCIAL_LOGIN_EMAIL_REQUIRED', 'True', 'Is Email Required?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Title', 'MODULE_SOCIAL_LOGIN_TITLE', 'Social Login', 'Enter the Module Title of your choice', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Select API Credential', 'MODULE_SOCIAL_LOGIN_USEAPI', 'CURL', 'To Communicate with API', '6', '1', 'tep_cfg_select_option(array(\'CURL\', \'FSCKOPEN\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Link to existing account', 'MODULE_SOCIAL_LOGIN_LINKACCOUNT', 'False', 'Attach social login ID to existing account.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Select Redirect option ', 'MODULE_SOCIAL_LOGIN_REDIRECT', 'Javascript', 'Redirect user after login using(if not working any of one)', '6', '1', 'tep_cfg_select_option(array(\'Javascript\', \'tep_redirect\'), ', now())");
	}

   function remove() {
     tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
 }?>