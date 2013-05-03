<?php
/*
  $Id: catalogcartstoreadmin/customers_groups.php v1.0 2005/03/03
  for Separate Pricing Per Customer
  
  adapted from the file of the same name from TotalB2B
  by hOZONE, hozone[at]tiscali.it, http://hozone.cjb.net
  
  who in turn credits:
  Discount_Groups_v1.1, by Enrico Drusiani, 2003/5/22
  
  setting of allowed payments inspired by b2bsuite_b097 but implemented differently
  
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com
  
  Copyright (c) 2008 Adoovo Inc. USA
  
  GNU General Public License Compatible 
*/

  require('includes/application_top.php');
  
    $cg_show_tax_array = array(array('id' => '1', 'text' => ENTRY_GROUP_SHOW_TAX_YES),
                              array('id' => '0', 'text' => ENTRY_GROUP_SHOW_TAX_NO));
    $cg_tax_exempt_array = array(array('id' => '1', 'text' => ENTRY_GROUP_TAX_EXEMPT_YES),
                              array('id' => '0', 'text' => ENTRY_GROUP_TAX_EXEMPT_NO));
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {

      case 'update':
        $error = false;
	    $customers_group_id = tep_db_prepare_input($_GET['cID']);
		$customers_group_name = tep_db_prepare_input($_POST['customers_group_name']);
		$customers_group_show_tax = tep_db_prepare_input($_POST['customers_group_show_tax']);
		$customers_group_tax_exempt = tep_db_prepare_input($_POST['customers_group_tax_exempt']);
		$group_payment_allowed = '';
		if ($_POST['payment_allowed'] && $_POST['group_payment_settings'] == '1') {
		  while(list($key, $val) = each($_POST['payment_allowed'])) {
		    if ($val == true) { 
		    $group_payment_allowed .= tep_db_prepare_input($val).';'; 
		    }
		  } // end while
		  $group_payment_allowed = substr($group_payment_allowed,0,strlen($group_payment_allowed)-1);
		} // end if ($_POST['payment_allowed'])
		$group_shipment_allowed = '';
		if ($_POST['shipping_allowed'] && $_POST['group_shipment_settings'] == '1') {
		  while(list($key, $val) = each($_POST['shipping_allowed'])) {
		    if ($val == true) { 
		    $group_shipment_allowed .= tep_db_prepare_input($val).';'; 
		    }
		  } // end while
		  $group_shipment_allowed = substr($group_shipment_allowed,0,strlen($group_shipment_allowed)-1);
		} // end if ($_POST['shipment_allowed'])

        tep_db_query("update " . TABLE_CUSTOMERS_GROUPS . " set customers_group_name='" . $customers_group_name . "', customers_group_show_tax = '" . $customers_group_show_tax . "', customers_group_tax_exempt = '" . $customers_group_tax_exempt . "', group_payment_allowed = '". $group_payment_allowed ."', group_shipment_allowed = '". $group_shipment_allowed ."' where customers_group_id = " . tep_db_input($customers_group_id) );
        tep_redirect(tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_group_id));
		break;
        
      case 'deleteconfirm':
        $group_id = tep_db_prepare_input($_GET['cID']);
        tep_db_query("delete from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id= " . $group_id); 
        $customers_id_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_group_id=" . $group_id);
        while($customers_id = tep_db_fetch_array($customers_id_query)) {
            tep_db_query("UPDATE " . TABLE_CUSTOMERS . " set customers_group_id = '0' where customers_id=" . $customers_id['customers_id']);
        }     
        tep_redirect(tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')))); 
        break;
        
      case 'newconfirm' :
        $customers_group_name = tep_db_prepare_input($_POST['customers_group_name']);
	$customers_group_tax_exempt = tep_db_prepare_input($_POST['customers_group_tax_exempt']);
	$group_payment_allowed = '';
	if ($_POST['payment_allowed']) {
	      while(list($key, $val) = each($_POST['payment_allowed'])) {
	         if ($val == true) { 
	         $group_payment_allowed .= tep_db_prepare_input($val).';'; 
	         }
	      } // end while
	   $group_payment_allowed = substr($group_payment_allowed,0,strlen($group_payment_allowed)-1);
	} // end if ($_POST['payment_allowed'])
		$group_shipment_allowed = '';
		if ($_POST['shipping_allowed'] && $_POST['group_shipment_settings'] == '1') {
		  while(list($key, $val) = each($_POST['shipping_allowed'])) {
		    if ($val == true) { 
		    $group_shipment_allowed .= tep_db_prepare_input($val).';'; 
		    }
		  } // end while
		  $group_shipment_allowed = substr($group_shipment_allowed,0,strlen($group_shipment_allowed)-1);
		} // end if ($_POST['shipment_allowed'])

        $last_id_query = tep_db_query("select MAX(customers_group_id) as last_cg_id from " . TABLE_CUSTOMERS_GROUPS . "");
        $last_cg_id_inserted = tep_db_fetch_array($last_id_query);
        $new_cg_id = $last_cg_id_inserted['last_cg_id'] +1;
        tep_db_query("insert into " . TABLE_CUSTOMERS_GROUPS . " set customers_group_id = " . $new_cg_id . ", customers_group_name = '" . $customers_group_name . "', customers_group_show_tax = '" . $customers_group_show_tax . "', customers_group_tax_exempt = '" . $customers_group_tax_exempt . "', group_payment_allowed = '". $group_payment_allowed ."', group_shipment_allowed = '". $group_shipment_allowed ."'");
        tep_redirect(tep_href_link('customers_groups.php', tep_get_all_get_params(array('action'))));
        break;
    }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
  if ($_GET['action'] == 'edit') {
    $customers_groups_query = tep_db_query("select c.customers_group_id, c.customers_group_name, c.customers_group_show_tax, c.customers_group_tax_exempt, c.group_payment_allowed, c.group_shipment_allowed from " . TABLE_CUSTOMERS_GROUPS . " c  where c.customers_group_id = '" . $_GET['cID'] . "'");
    $customers_groups = tep_db_fetch_array($customers_groups_query);
    $cInfo = new objectInfo($customers_groups);
    
   $payments_allowed = explode (";",$cInfo->group_payment_allowed);
   $shipment_allowed = explode (";",$cInfo->group_shipment_allowed);
   $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
   $ship_module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';

   $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
   $directory_array = array();
   if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file; // array of all the payment modules present in includes/modules/payment
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }

   $ship_directory_array = array();
   if ($dir = @dir($ship_module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($ship_module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $ship_directory_array[] = $file; // array of all shipping modules present in includes/modules/shipping
        }
      }
    }
    sort($ship_directory_array);
    $dir->close();
  }
?>

<script language="javascript"><!--
function check_form() {
  var error = 0;

  var customers_group_name = document.customers.customers_group_name.value;
  
  if (customers_group_name == "") {
    error_message = "<?php echo ERROR_CUSTOMERS_GROUP_NAME; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>

	  <tr><?php echo tep_draw_form('customers', 'customers_groups.php', tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>

      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_GROUPS_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_group_name', $cInfo->customers_group_name, 'maxlength="32"', false); ?> &#160;&#160;Maximum length: 32 characters</td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_GROUP_SHOW_TAX; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('customers_group_show_tax', $cg_show_tax_array, (($cInfo->customers_group_show_tax == '1') ? '1' : '0')); ?> &#160;&#160;This Setting only works when 'Display Prices with Tax'</td>
          </tr>
          <tr>
            <td class="main">&#160;</td>
            <td class="main" style="line-height: 2">is set to true in the Configuration for your store and Tax Exempt (below) to 'No'. </td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_GROUP_TAX_EXEMPT; ?></td>
            <td class="main"><?php
            echo tep_draw_pull_down_menu('customers_group_tax_exempt', $cg_tax_exempt_array, (($cInfo->customers_group_tax_exempt == '1') ? '1' : '0')); ?></td>
          </tr>
	</table>
	</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php include_once(DIR_WS_LANGUAGES . $language . '/modules.php');
	echo HEADING_TITLE_MODULES_PAYMENT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
	   <tr bgcolor="#DEE4E8">
            <td class="main"><?php echo tep_draw_radio_field('group_payment_settings', '1', false, (tep_not_null($cInfo->group_payment_allowed)? '1' : '0' )) . '&nbsp;&nbsp;' . ENTRY_GROUP_PAYMENT_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('group_payment_settings', '0', false, (tep_not_null($cInfo->group_payment_allowed)? '1' : '0' )) . '&nbsp;&nbsp;' . ENTRY_GROUP_PAYMENT_DEFAULT ; ?></td>
          </tr>
<?php
    $module_active = explode (";",MODULE_PAYMENT_INSTALLED);
    $installed_modules = array();
    for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++) {
    $file = $directory_array[$i];
    if (in_array ($directory_array[$i], $module_active)) {
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/payment/' . $file);
      include($module_directory . $file);

     $class = substr($file, 0, strrpos($file, '.'));
     if (tep_class_exists($class)) {
       $module = new $class;
       if ($module->check() > 0) {
         $installed_modules[] = $file;
       }
     } // end if (tep_class_exists($class))
?>
	   <tr>
            <td class="main"><?php echo tep_draw_checkbox_field('payment_allowed[' . $i . ']', $module->code.".php" , (in_array ($module->code.".php", $payments_allowed)) ?  1 : 0); ?>&#160;&#160;<?php echo $module->title; ?></td>
           </tr>
<?php
  } // end if (in_array ($directory_array[$i], $module_active)) 
 } // end for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++)
?>
	   <tr>
            <td class="main" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_PAYMENT_SET_EXPLAIN ?></td>
           </tr>
        </table>
       </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo HEADING_TITLE_MODULES_SHIPPING; ?> (Note: If you enable MVS, MVS will override these settings here and make this non functional)</td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
	   <tr bgcolor="#DEE4E8">
            <td class="main"><?php echo tep_draw_radio_field('group_shipment_settings', '1', false, (tep_not_null($cInfo->group_shipment_allowed)? '1' : '0' )) . '&nbsp;&nbsp;' . ENTRY_GROUP_SHIPPING_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('group_shipment_settings', '0', false, (tep_not_null($cInfo->group_shipment_allowed)? '1' : '0' )) . '&nbsp;&nbsp;' . ENTRY_GROUP_SHIPPING_DEFAULT ; ?></td>
          </tr>
<?php
    $ship_module_active = explode (";",MODULE_SHIPPING_INSTALLED);
    $installed_shipping_modules = array();
    for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++) {
    $file = $ship_directory_array[$i];
    if (in_array ($ship_directory_array[$i], $ship_module_active)) {
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/shipping/' . $file);
      include($ship_module_directory . $file);

     $ship_class = substr($file, 0, strrpos($file, '.'));
     if (tep_class_exists($ship_class)) {
       $ship_module = new $ship_class;
       if ($ship_module->check() > 0) {
         $installed_shipping_modules[] = $file;
       }
     } // end if (tep_class_exists($ship_class))
?>
	   <tr>
            <td class="main"><?php echo tep_draw_checkbox_field('shipping_allowed[' . $i . ']', $ship_module->code.".php" , (in_array ($ship_module->code.".php", $shipment_allowed)) ?  1 : 0); ?>&#160;&#160;<?php echo $ship_module->title; ?></td>
           </tr>
<?php
  } // end if (in_array ($ship_directory_array[$i], $ship_module_active)) 
 } // end for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++)
?>
	   <tr>
            <td class="main" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_SHIPPING_SET_EXPLAIN ?></td>
           </tr>
        </table>
       </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_update.png', IMAGE_UPDATE) . ' <a class="button" href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('action','cID'))) .'">' .  IMAGE_CANCEL . '</a>'; ?></td>
      </tr>
      </form>

	  <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '70'); ?></td>
      </tr>

<?php
  } else if($_GET['action'] == 'new') {   
?>
<script language="javascript"><!--
function check_form() {
  var error = 0;

  var customers_group_name = document.customers.customers_group_name.value;
  
  if (customers_group_name == "") {
    error_message = "<?php echo ERROR_CUSTOMERS_GROUP_NAME; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('customers', 'customers_groups.php', tep_get_all_get_params(array('action')) . 'action=newconfirm', 'post', 'onSubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_GROUPS_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_group_name', '', 'maxlength="32"', false); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_GROUP_SHOW_TAX; ?></td>
            <td class="main"><?php
            echo tep_draw_pull_down_menu('customers_group_show_tax', $cg_show_tax_array, '1'); ?>  This Setting only works when 'Display Prices with Tax'</td>
          </tr>
          <tr>
            <td class="main">&#160;</td>
            <td class="main" style="line-height: 2"> is set to true in the Configuration for your store and Tax Exempt (below) to 'No'.</td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_GROUP_TAX_EXEMPT; ?></td>
            <td class="main"><?php
            echo tep_draw_pull_down_menu('customers_group_tax_exempt', $cg_tax_exempt_array, '0'); ?></td>
          </tr>
	 </table>
	</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php include_once(DIR_WS_LANGUAGES . $language . '/modules.php');
	echo HEADING_TITLE_MODULES_PAYMENT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
	   <tr bgcolor="#DEE4E8">
            <td class="main"><?php echo tep_draw_radio_field('group_payment_settings', '1', false, '0') . '&nbsp;&nbsp;' . ENTRY_GROUP_PAYMENT_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('group_payment_settings', '0', false, '0') . '&nbsp;&nbsp;' . ENTRY_GROUP_PAYMENT_DEFAULT ; ?></td>
          </tr>
<?php
  $module_active = explode (";",MODULE_PAYMENT_INSTALLED);
  $ship_module_active = explode (";",MODULE_SHIPPING_INSTALLED);
  $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
  $ship_module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';

// code slightly adapted from admin/modules.php
  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file; // array of all the payment modules present in includes/modules/payment
        }
      }
    }
    $dir->close();
  } // end if ($dir = @dir($module_directory))

   $ship_directory_array = array();
   if ($dir = @dir($ship_module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($ship_module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $ship_directory_array[] = $file; // array of all shipping modules present in includes/modules/shipping
        }
      }
    }
    sort($ship_directory_array);
    $dir->close();
  }
    $installed_modules = array();
    for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++) {
    $file = $directory_array[$i];
    if (in_array ($directory_array[$i], $module_active)) {
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/payment/' . $file);
      include($module_directory . $file);

     $class = substr($file, 0, strrpos($file, '.'));
     if (tep_class_exists($class)) {
       $module = new $class;
       if ($module->check() > 0) {
         $installed_modules[] = array('file_name' => $file, 'title' => $module->title);
       }
     } // end if (tep_class_exists($class))
   } // end if (in_array ($directory_array[$i], $module_active)) 
 } // end for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++)

  for ($y = 0; $y < sizeof($installed_modules) ; $y++) {
?>
	   <tr>
            <td class="main"><?php echo tep_draw_checkbox_field('payment_allowed[' . $y . ']', $installed_modules[$y]['file_name'] , 0); ?>&#160;&#160;<?php echo $installed_modules[$y]['title']; ?></td>
           </tr>
<?php
 } // end for ($y = 0; $y < sizeof($installed_modules) ; $y++)
?>
	   <tr>
            <td class="main" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_PAYMENT_SET_EXPLAIN ?></td>
           </tr>
        </table>
       </td>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo HEADING_TITLE_MODULES_SHIPPING; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
	   <tr bgcolor="#DEE4E8">
            <td class="main"><?php echo tep_draw_radio_field('group_shipment_settings', '1', false, (tep_not_null($cInfo->group_shipment_allowed)? '1' : '0' )) . '&nbsp;&nbsp;' . ENTRY_GROUP_SHIPPING_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('group_shipment_settings', '0', false, (tep_not_null($cInfo->group_shipment_allowed)? '1' : '0' )) . '&nbsp;&nbsp;' . ENTRY_GROUP_SHIPPING_DEFAULT ; ?></td>
          </tr>
<?php
    $ship_module_active = explode (";",MODULE_SHIPPING_INSTALLED);
    $installed_shipping_modules = array();
    for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++) {
    $file = $ship_directory_array[$i];
    if (in_array ($ship_directory_array[$i], $ship_module_active)) {
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/shipping/' . $file);
      include($ship_module_directory . $file);

     $ship_class = substr($file, 0, strrpos($file, '.'));
     if (tep_class_exists($ship_class)) {
       $ship_module = new $ship_class;
       if ($ship_module->check() > 0) {
         $installed_shipping_modules[] = array('file_name' => $file, 'title' => $ship_module->title);
       }
     } // end if (tep_class_exists($ship_class))
   } // end if (in_array ($ship_directory_array[$i], $ship_module_active))
 } // end for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++)

 for ($y = 0; $y < sizeof($installed_shipping_modules) ; $y++) {
?>
	   <tr>
            <td class="main"><?php echo tep_draw_checkbox_field('shipping_allowed[' . $y . ']', $installed_shipping_modules[$y]['file_name'] , 0); ?>&#160;&#160;<?php echo $installed_shipping_modules[$y]['title']; ?></td>
           </tr>
<?php
  } // end for ($y = 0; $y < sizeof($installed_modules) ; $y++) 
?>
	   <tr>
            <td class="main" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_SHIPPING_SET_EXPLAIN ?></td>
           </tr>
        </table>
       </td>
      </tr>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_update.png', IMAGE_UPDATE) . ' <a class="button" href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('action','cID'))) .'">' .  IMAGE_CANCEL . '</a>'; ?></td>
      </tr>
      </form>
<?php 
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo tep_draw_form('search', 'customers_groups.php', '', 'get'); ?>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="pageHeading2" align="right"></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>

          <?php
          switch ($listing) {
              case "group":
              $order = "g.customers_group_name";
              break;
              case "group-desc":
              $order = "g.customers_group_name DESC";
              break;
              default:
              $order = "g.customers_group_id ASC";
          }
          ?>
	    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
               <tr class="dataTableHeadingRow">
	       <td class="dataTableHeadingContent"><a href="<?php echo "$PHP_SELF?listing=group"; ?>"><?php echo tep_image_button('ic_up.gif', ' Sort ' . TABLE_HEADING_NAME . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=group-desc"; ?>"><?php echo tep_image_button('ic_down.gif', ' Sort ' . TABLE_HEADING_NAME . ' --> Z-X-Y From Top '); ?></a><br><?php echo TABLE_HEADING_NAME; ?></td>
				   <td class="dataTableHeadingContent" align="right" valign="bottom"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
			   </tr>

<?php
    $search = '';
    if ( ($_GET['search']) && (tep_not_null($_GET['search'])) ) {
      $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
      $search = "where g.customers_group_name like '%" . $keywords . "%'";
    }

    $customers_groups_query_raw = "select g.customers_group_id, g.customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " g  " . $search . " order by $order";
    $customers_groups_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_groups_query_raw, $customers_groups_query_numrows);
    $customers_groups_query = tep_db_query($customers_groups_query_raw);

    while ($customers_groups = tep_db_fetch_array($customers_groups_query)) {
      $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers_groups['customers_group_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if ((!isset($_GET['cID']) || (@$_GET['cID'] == $customers_groups['customers_group_id'])) && (!$cInfo)) {
        $cInfo = new objectInfo($customers_groups);
      }

      if ( (is_object($cInfo)) && ($customers_groups['customers_group_id'] == $cInfo->customers_group_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID')) . 'cID=' . $customers_groups['customers_group_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $customers_groups['customers_group_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($customers_groups['customers_group_id'] == $cInfo->customers_group_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png', ''); } else { echo '<a href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID')) . 'cID=' . $customers_groups['customers_group_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_groups_split->display_count($customers_groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_GROUPS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_groups_split->display_links($customers_groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
<?php
    if (tep_not_null($_GET['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a  class="button" href="' . tep_href_link('customers_groups.php') . '">' . IMAGE_RESET . '</a>'; ?></td>
                  </tr>
<?php
    } else {
?>
			      <tr>
                    <td align="right" colspan="2" class="smallText"><?php echo '<a class="button" href="' . tep_href_link('customers_groups.php', 'page=' . $_GET['page'] . '&action=new') . '">' .  IMAGE_INSERT . '</a>'; ?></td>
                  </tr>
<?php
	}
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'confirm':
        if ($_GET['cID'] != '0') {
            $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.png', '11', '12') .'&nbsp;<br><b>' . TEXT_INFO_HEADING_DELETE_GROUP . '</b>');
            $contents = array('form' => tep_draw_form('customers_groups', 'customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=deleteconfirm'));
            $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_group_name . ' </b>');
            if ($cInfo->number_of_reviews > 0) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
            $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id) . '">' . IMAGE_CANCEL . '</a>');
        } else {
            $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.png', '11', '12') .'&nbsp;<br><b>' . TEXT_INFO_HEADING_DELETE_GROUP . '</b>');
            $contents[] = array('text' => 'You are not allowed to delete this group:<br><br><b>' . $cInfo->customers_group_name . ' </b>');
        }
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.png', '11', '12') .'&nbsp;<br><b>' . $cInfo->customers_group_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=edit') . '">' .  IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=confirm') . '">' .  IMAGE_DELETE . '</a>');

      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '           <td valign="top"  width="220px">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>