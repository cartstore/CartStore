<?php
/*
$Id: sts_product_info.php,v 1.0.5 2005/12/12 09:36:00 Rigadin Exp $

CartStore eCommerce Software, for The Next Generation
http://www.cartstore.com

Copyright (c) 2008 Adoovo Inc. USA

GNU General Public License Compatible

STS PLUS v4 module for product_info.php by Rigadin (rigadin@osc-help.net)
*/

class sts_product_info {

  var $template_file, $template_type;
  
  function sts_product_info (){
    $this->code = 'sts_product_info';
    $this->title = MODULE_STS_PRODUCT_INFO_TITLE;
    $this->description = MODULE_STS_PRODUCT_INFO_DESCRIPTION.' (v1.0.5)';
	$this->sort_order=3;
    $this->enabled = ((MODULE_STS_PRODUCT_INFO_STATUS == 'true') ? true : false);
	$this->template_file = STS_DEFAULT_TEMPLATE; // Should not be needed but just in case something goes weird
	$this->content_template_file='';
  }
  
  function find_content_template ($products_id, $sts_cpath) {
  // Private function to check if there is a content template for products.

    // Is there a content template for this particular product?
	$check_file= STS_TEMPLATE_DIR . "content/product_info.php_$products_id.html"; 
	if (file_exists($check_file)) return $check_file;
	  
	// Is there a category-specific content template file like "product_info.php_c1_17.html", then "product_info.php_c1.html
	while ($sts_cpath != "") {
      $check_file = STS_TEMPLATE_DIR . "content/product_info.php_c$sts_cpath.html";
      if (file_exists($check_file)) return $check_file;
	  $sts_cpath = substr($sts_cpath, 0, (strrpos($sts_cpath, "_")));
	} //end while

	// or is there a general content template for products
	$check_file= STS_TEMPLATE_DIR . "content/product_info.php.html"; 
	if (file_exists($check_file)) return $check_file;
	
	// If no content template found, return empty string
	return '';
  }

  function find_template (){
  // Return an html file to use as template
    global $cPath;

	$products_id=intval($_GET['products_id']); // Get current product ID
	
    // Check if there is a content template
	$this->content_template_file = $this->find_content_template($products_id, $cPath); 

	// Check now for the 'outside' template (columns, header, footer):
	// Is there a template for this particular product?
	$check_file= STS_TEMPLATE_DIR . "product_info.php_$products_id.html";
	if (file_exists($check_file)) {
	    // Use it
	  $this->template_file = $check_file;
	  return $check_file;
    }
	
	// Is there a category-specific template for products like "product_info.php_c1_17.html", then "product_info.php_c1.html?
	$sts_cpath= $cPath;
	while ($sts_cpath != "") {
    
      $check_file = STS_TEMPLATE_DIR . "product_info.php_c$sts_cpath.html";
      if (file_exists($check_file)) {
      // Use it
		$this->template_file = $check_file;
		return $check_file;
      }
	  $sts_cpath = substr($sts_cpath, 0, (strrpos($sts_cpath, "_")));
	} //end while
	
	
	// Is there a general template for products?
	$check_file= STS_TEMPLATE_DIR . "product_info.php.html"; 
	if (file_exists($check_file)) {
	  // Use it
	  $this->template_file = $check_file;
	  return $check_file;
	}	
	
	// Is there a category-specific template like "index.php_1_17.html", then "index.php_1.html?
	$sts_cpath= $cPath;
	while ($sts_cpath != "") {
    
      $check_file = STS_TEMPLATE_DIR . "index.php_$sts_cpath.html";
      if (file_exists($check_file)) {
      // Use it
		$this->template_file = $check_file;
		return $check_file;
      }
	  $sts_cpath = substr($sts_cpath, 0, (strrpos($sts_cpath, "_")));
	} //end while

	// No specific template found, use default template
	return STS_DEFAULT_TEMPLATE;
  }

  function capture_fields () {
  // Returns list of files to include from folder sts_inc in order to build the $template fields
    if ($this->content_template_file!='') { // If we use a content template
	  return MODULE_STS_PRODUCT_INFO_CONTENT;  // include also product info fields
	} else {
	    $temp= MODULE_STS_PRODUCT_INFO_NORMAL;
		if (MODULE_STS_PRODUCT_V3COMPAT=='true') $temp.=';pinfo_sts3.php';
	    return $temp;
	 }	
  }

  function replace (&$template) {
  // If we do not use a content template, extract the content from buffer
    if ($this->content_template_file=='') {
	  $template['content']=sts_strip_content_tags($template['content'], 'Product Info Content');
	  return;
	}
	
  // Otherwise continue and use the content template to build the content
	
	global $template_pinfo;
	
    // Read content template file
	$template_html = sts_read_template_file($this->content_template_file);

    if (defined(STS_END_CHAR)==false) { // If no end char defined for the placeholders, have to sort the placeholders.
      uksort($template_pinfo, "sortbykeylength"); // Sort array by string length, so that longer strings are replaced first
	  define ('STS_CONTENT_END_CHAR', ''); // An end char must be defined, even if empty.
    }	
    foreach ($template_pinfo as $key=>$value) {
	  $template_html = str_replace('$' . $key . STS_CONTENT_END_CHAR , $value, $template_html);
    }

    $template['content'] = sts_strip_content_tags($template_html, 'Product Info Content Template');
  }
  
//======================================
// Functions needed for admin
//======================================
  
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_STS_PRODUCT_INFO_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_STS_PRODUCT_INFO_STATUS','MODULE_STS_PRODUCT_V3COMPAT' ,'MODULE_STS_PRODUCT_INFO_NORMAL', 'MODULE_STS_PRODUCT_INFO_CONTENT');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use template for product info page', 'MODULE_STS_PRODUCT_INFO_STATUS', 'false', 'Do you want to use templates for product info pages?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable STS3 compatibility mode', 'MODULE_STS_PRODUCT_V3COMPAT', 'false', 'Do you want to enable the STS v3 compatibility mode (only for product info templates made with STS v2 and v3)?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Files for normal template', 'MODULE_STS_PRODUCT_INFO_NORMAL', 'sts_user_code.php', 'Files to include for a normal template, separated by semicolon', '6', '2', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Files for content template', 'MODULE_STS_PRODUCT_INFO_CONTENT', 'sts_user_code.php;product_info.php', 'Files to include for a content template, separated by semicolon', '6', '3', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }  
  
}// end class
?>