<?php
/*
$Id: sts_infobox.php,v 1.0.3 2006/05/08 09:36:00 rigadin $

CartStore eCommerce Software, for The Next Generation
http://www.cartstore.com

Copyright (c) 2008 Adoovo Inc. USA

GNU General Public License Compatible
*
STS PLUS v4 module for index.php by Rigadin (rigadin@osc-help.net)
*/

class sts_infobox {

  var $template_file;

  function sts_infobox (){
    $this->code = 'sts_infobox';
    $this->title = MODULE_STS_INFOBOX_TITLE;
    $this->description = MODULE_STS_INFOBOX_DESCRIPTION.' (v1.0.3)';
	$this->sort_order=7;
	$this->enabled = ((MODULE_STS_INFOBOX_STATUS == 'true') ? true : false);
  }

  function find_template (){
  // Private function to check if there is a content template for products.

	$check_file= STS_TEMPLATE_DIR . "content/infobox.php.html";
	if (file_exists($check_file)) return $check_file;

	// If no content template found, return empty string
	return '';
  } // End function

  function capture_fields () {
  // Returns list of files to include from folder sts_inc in order to build the $template fields
    return MODULE_STS_INFOBOX_NORMAL;
  }

  function replace (&$template) {
    $template['content']=sts_strip_content_tags($template['content'], 'Index content');
  }

//======================================
// Functions needed for admin
//======================================

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_STS_INFOBOX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_STS_INFOBOX_STATUS', 'MODULE_STS_INFOBOX_NORMAL');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use template for infoboxes', 'MODULE_STS_INFOBOX_STATUS', 'true', 'Do you want to use templates for infoboxes?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Files for infobox template', 'MODULE_STS_INFOBOX_NORMAL', 'sts_user_code.php', 'Files to include for infobox template, separated by semicolon', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

}// end class
?>
