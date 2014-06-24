<?php
/*
  $Id: account_notifications.php,v 1.2 2003/05/22 14:24:54 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_NOTIFICATIONS);

  $global_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "'");
  $global = tep_db_fetch_array($global_query);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (isset($_POST['product_global']) && is_numeric($_POST['product_global'])) {
      $product_global = tep_db_prepare_input($_POST['product_global']);
    } else {
      $product_global = '0';
    }

    (array)$products = $_POST['products'];

    if ($product_global != $global['global_product_notifications']) {
      $product_global = (($global['global_product_notifications'] == '1') ? '0' : '1');

      tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set global_product_notifications = '" . (int)$product_global . "' where customers_info_id = '" . (int)$customer_id . "'");
    } elseif (sizeof($products) > 0) {
      $products_parsed = array();
      for ($i=0, $n=sizeof($products); $i<$n; $i++) {
        if (is_numeric($products[$i])) {
          $products_parsed[] = $products[$i];
        }
      }

      if (sizeof($products_parsed) > 0) {
        $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$customer_id . "' and products_id not in (" . implode(',', $products_parsed) . ")");
        $check = tep_db_fetch_array($check_query);

        if ($check['total'] > 0) {
          tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$customer_id . "' and products_id not in (" . implode(',', $products_parsed) . ")");
        }
      }
    } else {
      $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$customer_id . "'");
      $check = tep_db_fetch_array($check_query);

      if ($check['total'] > 0) {
        tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$customer_id . "'");
      }
    }

    $messageStack->add_session('account', SUCCESS_NOTIFICATIONS_UPDATED, 'success');

    tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL'));

require(DIR_WS_INCLUDES . 'header.php'); 
require(DIR_WS_INCLUDES . 'column_left.php'); ?>


<!-- body_text //-->
 <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
<?php echo tep_draw_form('account_notifications', tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>

<div class="page-header">
<h1><?php echo HEADING_TITLE; ?></h1> </div>
      


<h3><?php echo MY_NOTIFICATIONS_TITLE; ?></h3>

 <p><?php echo MY_NOTIFICATIONS_DESCRIPTION; ?></p>
                
   
<h3><?php echo GLOBAL_NOTIFICATIONS_TITLE; ?></h3>


 <table class="table table-striped">
                  <tr>
                    <td class="main" width="30"><?php echo tep_draw_checkbox_field('product_global', '1', (($global['global_product_notifications'] == '1') ? true : false), 'onclick="checkBox(\'product_global\')"'); ?></td>
                    <td class="main"><b><?php echo GLOBAL_NOTIFICATIONS_TITLE; ?></b><p><?php echo GLOBAL_NOTIFICATIONS_DESCRIPTION; ?></p></td>
                  </tr>
                   
                </table> 
                
                
       
<?php
  if ($global['global_product_notifications'] != '1') {
?>
    
<?php
    $products_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$customer_id . "'");
    $products_check = tep_db_fetch_array($products_check_query);
    if ($products_check['total'] > 0) {
?>
                 
                 
<?php
      $counter = 0;
      $products_query = tep_db_query("select pd.products_id, pd.products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where pn.customers_id = '" . (int)$customer_id . "' and pn.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name");
      while ($products = tep_db_fetch_array($products_query)) {
?>
      <h3><?php echo NOTIFICATIONS_TITLE; ?></h3>

                 <i class="fa fa-info-circle"></i> <?php echo NOTIFICATIONS_DESCRIPTION; ?>

              <div class="form-group"><label><?php echo $products['products_name']; ?></label><?php echo tep_draw_checkbox_field('products[' . $counter . ']', $products['products_id'], true, 'onclick="checkBox(\'products[' . $counter . ']\')"'); ?></div> 
                   
<?php
        $counter++;
      }
    } else {
?>
                  <p><?php echo NOTIFICATIONS_NON_EXISTING; ?></p>
                
<?php
    }
?>
            
<?php
  }
?>
<div class="clear"></div>
    <span class="pull-left"> <?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' .  IMAGE_BUTTON_BACK . '</a>'; ?> </span> 
        <span class="pull-right">   <?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?> </span> 
             </form></td>
      </tr>
    </table> 
<!-- body_text_eof //-->

<?php require(DIR_WS_INCLUDES . 'column_right.php'); 
require(DIR_WS_INCLUDES . 'footer.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
