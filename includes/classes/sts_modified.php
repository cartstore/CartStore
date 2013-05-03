<?php
/*
  $Id: sts.php,v 4.1 2006/03/06 22:30:54 Rigadin Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
  * 
  * STS v4.1
*/

require (DIR_WS_FUNCTIONS.'sts.php');
include("../configuration.php");


class sts {
  var $sts_block, $template,
	  $display_template_output, $display_debugging_output,
	  $template_file, $template_folder;
  
  function sts (){
    $this->update_from_url(); // Check for debug mode from URL
  // Use template output if enabled or in debug mode (=only for admin)
    if ((MODULE_STS_DEFAULT_STATUS == 'true') || ($this->display_debug_output == true))
      $this->display_template_output = true;
	else { 
	  $this->display_template_output = false;
	  return;
	}

	// Defines constants needed when working with templates
    define('STS_TEMPLATE_DIR', DIR_WS_TEMPLATES . 'includes/sts_templates/' . $this->template_folder .'/');
    define('STS_DEFAULT_TEMPLATE', STS_TEMPLATE_DIR . MODULE_STS_TEMPLATE_FILE); 	
	
  // Initialisation	of variables
	$this->template = array('debug' => '', 
	                        'headcontent' =>'',
	                        'extracss' =>'');
    $this->version= "4.1";
	$this->osc_version_required = "CartStore 2.0";			

  // Actions to do first
    $this->check_version(PROJECT_VERSION); // Check if this STS is running under the correct osCommerce version
	
	// Find the right template to use according to actual page and parameters. Displays normal output if no template returned
    if ($this->find_template() == '') {
	  $this->display_template_output = false; // If no template returned, do not use templates at all and exit
	  return;
	}
	if ($this->read_template_file() == false) {
	  $this->display_template_output = false; // If template file does not exist, do not use templates at all and exit
	  return;
	}
	
  } //end constructor
  
  function check_version ($osc_version) {
  // Perform OSC version checking
    if ($osc_version != $this->osc_version_required) {
      echo "STS4 was designed to work with OSC version [$this->osc_version_required].  This is version [$osc_version].\n";
	  die;
    }    
  }
  
  function update_from_url () {
    // Allow Debugging control from the URL
    if ($_GET['sts_debug'] == MODULE_STS_DEBUG_CODE) {
      $this->display_debug_output = true;
    }
	
	// Defines constants needed when working with templates
    if ($_GET['sts_template']) {
	  $this->template_folder = $_GET['sts_template'];
    } else {
	  $this->template_folder = MODULE_STS_TEMPLATE_FOLDER;
	}
    
  }  

  function find_template (){

  // Retrieve script name without path nor parameters
    $scriptbasename = basename ($_SERVER['PHP_SELF']);

  // If script name contains "popup" then turn off templates and display the normal output
  // This is required to prevent display of standard page elements (header, footer, etc) from the template and allow javascript code to run properly
  // Do not add pages here unless it is from the standard osC and really should be there. If you have a special page that you don't want with template,
  // Create a module sts_mypagename.php that returns an empty string as template filename, it will automatically switch off STS for this page.
    if (strstr($scriptbasename, "popup")|| strstr($scriptbasename, "info_shopping_cart")) {
      $this->display_template_output = false;
	  return;
    }

  // Check for module that will handle the template (for example module sts_index takes care of index.php templates)
	$check_file = 'sts_'.$scriptbasename;
	$modules_installed = explode (';', MODULE_STS_INSTALLED);
	if (!in_array($check_file, $modules_installed)) $check_file = 'sts_default.php';

    include_once (DIR_WS_MODULES.'sts/'.$check_file);
	$classname=substr($check_file,0,strlen($check_file)-4);
	$this->script=new $classname; // Create an object from the module
	
// If module existes but is disabled, use the default module.
	if ($this->script->enabled==false) {
	  unset ($this->script);
      include_once (DIR_WS_MODULES.'sts/sts_default.php');
	  $this->script=new sts_default; // Create an object from the module	   
	}
	
	$this->template_file = $this->script->find_template(); // Retrieve the template to use
	return $this->template_file ;
  }
  
  function start_capture () {
  // Start redirecting output to the output buffer, if template mode on.
    if ($this->display_template_output) {
	  ob_end_clean(); // Clear out the capture buffer
	  ob_start();
	}
  }
  
  function stop_capture ($block_name='', $action='') {
  // Store captured output to $sts_capture
    if (!$this->display_template_output) return; // Do not process anything if we are not in using templates
	$block = ob_get_contents(); // Get content of buffer
    ob_end_clean(); // Clear out the capture buffer
	if ($block_name=='') return; // Not need to continue if we don't want to save the buffer
	switch($action){
		case 'box': 
		  $this->template[$block_name]= sts_strip_unwanted_tags($block, $block_name);
		  break;
		default:
		$this->template[$block_name]=$block;
	} // switch  
  }
  
  function restart_capture ($block_name='sts_empty', $action='') {
  // Capture buffer, save it and start a new capture
    if (!$this->display_template_output) return;
    $this->stop_capture($block_name, $action);
	$this->start_capture();
  }
  
  function capture_fields (){
// If we use template, ask to module what file(s) to include for building fields
    if ($this->display_template_output) {
	  $fields_arr= explode(';', 'general.php;'.$this->script->capture_fields ());
	}
	return $fields_arr;	
  }
  
  function read_template_file (){
  // Purpose: Open Template file and read it

	// Generate an error if the template file does not exist and return 'false'.
    if (! file_exists($this->template_file)) {
      print 'Template file does not exist: ['.$this->template_file.']';
	  return false;
    }
	// We use templates and the template file exists
	// Capture the template, this way we can use php code inside templates
	
	$this->start_capture (); // Start capture to buffer
	require $this->template_file; // Includes the template, this way php code can be used in templates
	$this->stop_capture ('template_html');
	return true;
  } // End read_template_file
  

  function replace (){
    global $messageStack, $request_type;
    
	if (!$this->display_template_output) return;  // Go out if we don't use template
    if (defined("STS_END_CHAR") == false) define ('STS_END_CHAR', ''); // An end char must be defined, even if empty.
    
	// Load up the <head> content that we need to link up everything correctly.  Append to anything that may have been set in sts_user_code.php
	// Note that since v3.0, stylesheet is not defined here but in the template file, allowing different stylesheet for different template.
    $this->template['headcontent'] = $this->template['headcontent'].'';
    $this->template['headcontent'] = $this->template['headcontent'].'<meta http-equiv="Content-Type" content="text/html; charset=' . CHARSET . '">' . "\n"; 
    $this->template['headcontent'] = $this->template['headcontent'].$this->template['headertags'];
    $this->template['headcontent'] = $this->template['headcontent'].'<base href="' . (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . '">' . "\n";
    $this->template['headcontent'] = $this->template['headcontent'].get_javascript($this->template['applicationtop2header'],'get_javascript(applicationtop2header)');
	
	$this->script->replace($this->template); // Module can make tricks here, just before replacing, like using own content template
    // Add messages before the content
    if ($messageStack->size('header') > 0) {
      $this->template['content'] = $messageStack->output('header') . $this->template['content'];
    }
  
  // Manually replace the <!--$headcontent--> if present
    $this->template['template_html'] = str_replace('<!--$headcontent-->', $this->template['headcontent'], $this->template['template_html']);

  
  
  /////////////////Drop Down wiht links start from here//////////////////
echo $mosConfig_user;
	function dbLink()
	{
		
		if( !$link = mysql_connect('localhost',$mosConfig_user, $mosConfig_password) )
		{
			printError( "Cannot connect to host localhost<br>SQL Error: ".mysql_error()."<br>Error Number: ".mysql_errno() );
			die;
		}
		else
		{
			if ( !mysql_select_db( $mosConfig_db, $link ) )
			{
				printError("Cannot connect to database '".$mosConfig_db."'<br>SQL Error: ".mysql_error( $link )."<br>Error Number: ".mysql_errno( $link ) );
				die;
			}
			else
			{
				return $link;
			}
			
		}
	}


//$link=dbLink();
$query_lnks = "SELECT * FROM jos_weblinks WHERE approved=1 ORDER BY date DESC ";
$toplinks = mysql_query($query_lnks,$link);
$drpdown ='


<SCRIPT LANGUAGE="JavaScript">
<!--
function gotourl()
{
alert(document.gmform.jgm_lnk.value);
document.location.href=document.gmform.jgm_lnk.value;
}
//-->
</SCRIPT>

<form name="gmform" action="">
<select name="jgm_lnk" onchange="return gotourl();">';

while($row = mysql_fetch_array($toplinks))
{
$drpdown .='<option value="'.$row["url"].'">'.$row["title"].'</option>';
}
$drpdown .='</select></form>'; 

$this->template['template_html'] = str_replace('$wblinks', 
$drpdown, $this->template['template_html']);
/////////////////Drop Down wiht links start from here//////////////////
  
  
  
  
  
  
  
  
  
  
  
  
  // Manually replace the <!--$extracss--> with template['extracss']
    $this->template['template_html'] = str_replace('<!--$extracss-->', $this->template['extracss'], $this->template['template_html']);









  // Automatically replace all the other template variables
    if (STS_END_CHAR=='') { // If no end char defined for the placeholders, have to sort the placeholders.
      uksort($this->template, "sortbykeylength"); // Sort array by string length, so that longer strings are replaced first
    }
    foreach ($this->template as $key=>$value) {
      $this->template['template_html'] = str_replace('$' . $key . STS_END_CHAR , $value, $this->template['template_html']);
    }
  }

// *****************************************
// Functions added for debug
// *****************************************   
  function add_debug ($text, $br=true) {
  // STS v4.1: Add debug text to the STS PLUS debug variable. If $br=false, then no line break added
    $this->template['debug'].= $text . ($br ? "\n" : '');
  }

}  //end class
?>