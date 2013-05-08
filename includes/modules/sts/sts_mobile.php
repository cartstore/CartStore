<?php
/*
$Id: STS_MOBILE.php,v 1.0.4 2005/12/12 09:36:00 rigadin Exp $

CartStore eCommerce Software, for The Next Generation
http://www.cartstore.com

Copyright (c) 2008 Adoovo Inc. USA

GNU General Public License Compatible
* 
* STS v4 module for pages without own module by Rigadin (rigadin@osc-help.net)
*/


class sts_mobile {
  function sts_mobile (){
    $this->code = 'sts_mobile';
    $this->title = MODULE_STS_MOBILE_TITLE;
    $this->description = MODULE_STS_MOBILE_DESCRIPTION.' (v1.0.4)';
	$this->sort_order=1;
  }
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_STS_MOBILE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }
    function keys() {
      return array('MODULE_STS_MOBILE_STATUS', 'MODULE_STS_MOBILE_TEMPLATE_FOLDER');
    }
    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Mobile Template?', 'MODULE_STS_MOBILE_STATUS', 'false', 'Do you want to enable Mobile template?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Template folder', 'MODULE_STS_MOBILE_TEMPLATE_FOLDER', 'mobile', 'Location of templates inside the templates/includes/sts_templates/ folder. Do not start nor end with a slash', '6', '2', now())");
    }
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }  
}
