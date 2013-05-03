<?php
/*
$Id: sts_default.php,v 1.0.4 2005/12/12 09:36:00 rigadin Exp $

CartStore eCommerce Software, for The Next Generation
http://www.cartstore.com

Copyright (c) 2008 Adoovo Inc. USA

GNU General Public License Compatible
* 
* STS v4 module for pages without own module by Rigadin (rigadin@osc-help.net)
*/


class sts_default {

  var $template_file;
  
  function sts_default (){
    $this->code = 'sts_default';
    $this->title = MODULE_STS_DEFAULT_TITLE;
    $this->description = MODULE_STS_DEFAULT_DESCRIPTION.' (v1.0.4)';
	$this->sort_order=1;
  }

  function find_template (){
  // Return an html file to use as template
    // Check if there is a template for this script
	$check_file = STS_TEMPLATE_DIR . basename ($_SERVER['PHP_SELF']) . ".html";
	if (file_exists($check_file)) return $check_file;
	
	// No template for this script, returns the default template
    return STS_DEFAULT_TEMPLATE;
  } // End function

  function capture_fields () {
  // Returns list of files to include from folder sts_inc in order to build the $template fields
    return MODULE_STS_DEFAULT_NORMAL;
  }

  function replace (&$template) {
    $template['content']=sts_strip_content_tags($template['content'], 'Default Content');
  }

//======================================
// Functions needed for admin
//======================================

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_STS_DEFAULT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_STS_DEFAULT_STATUS', 'MODULE_STS_DEBUG_CODE' ,'MODULE_STS_DEFAULT_NORMAL', 'MODULE_STS_TEMPLATE_FOLDER','MODULE_STS_TEMPLATE_FILE');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Templates?', 'MODULE_STS_DEFAULT_STATUS', 'false', 'Do you want to use Simple Template System?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Code for debug output', 'MODULE_STS_DEBUG_CODE', 'debug', 'Code to enable debug output from URL (ex: index.php?sts_debug=debug', '6', '2', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Files for normal template', 'MODULE_STS_DEFAULT_NORMAL', 'sts_user_code.php', 'Files to include for a normal template, separated by semicolon', '6', '2', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Template folder', 'MODULE_STS_TEMPLATE_FOLDER', 'test', 'Location of templates inside the templates/includes/sts_templates/ folder. Do not start nor end with a slash', '6', '2', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Default template file', 'MODULE_STS_TEMPLATE_FILE', 'sts_template.html', 'Name of the default template file', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }  
  
}// end class
?>
