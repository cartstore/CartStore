<?php
/*
  $Id: specials.php,v 1.41 2003/06/29 22:50:52 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  // BOF Separate Pricing Per Customer
      $customers_groups_query = tep_db_query("select customers_group_name, customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
    while ($existing_groups =  tep_db_fetch_array($customers_groups_query)) {
         $input_groups[] = array("id"=>$existing_groups['customers_group_id'], "text"=> $existing_groups['customers_group_name']);
        $all_groups[$existing_groups['customers_group_id']]=$existing_groups['customers_group_name'];
    }
// EOF Separate Pricing Per Customer



  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        tep_set_specials_status($_GET['id'], $_GET['flag']);

        tep_redirect(tep_href_link(FILENAME_SPECIALS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'sID=' . $_GET['id'], 'NONSSL'));
        break;
      case 'insert':
		$errors == false;
        $products_id = tep_db_prepare_input($_POST['products_id']);
        $products_price = tep_db_prepare_input($_POST['products_price']);
        $specials_price = tep_db_prepare_input($_POST['specials_price']);
        $expires_date = tep_db_prepare_input($_POST['expires_date']);
        $start_date = tep_db_prepare_input($_POST['specialStartDate']);

		// BOF Separate Pricing Per Customer
        $customers_group=tep_db_prepare_input($_POST['customers_group']);
        $price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS. " WHERE products_id = ".(int)$products_id . " AND customers_group_id  = ".(int)$customers_group);
        while ($gprices = tep_db_fetch_array($price_query)) {
            $products_price = $gprices['customers_group_price'];
        }
		// EOF Separate Pricing Per Customer


        //if (substr($specials_price, -1) == '%') {
		 if (substr($specials_price, -1) == '%' && $customers_group == '0') {
          $new_special_insert_query = tep_db_query("select products_id, products_price from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
          $new_special_insert = tep_db_fetch_array($new_special_insert_query);

          $products_price = $new_special_insert['products_price'];
          $specials_price = ($products_price - (($specials_price / 100) * $products_price));
        }

		$check_query = tep_db_query("select * from " . TABLE_SPECIALS . " where products_id = " . $_POST['products_id']);
		while ($check = tep_db_fetch_array($check_query)){
			if ($check['customers_group_id'] == $_POST['customers_group']){
				$errors = true;
				$messageStack->add(ERROR_SPECIALS_DUPLICATE_CUSTOMER_GROUP, 'error');
				$action = 'new';
			}
		}
		if ($errors == false){
			// BOF Separate Pricing Per Customer
			/*
			tep_db_query("insert into " . TABLE_SPECIALS . " (products_id, specials_new_products_price, specials_date_added, expires_date, status) values ('" . (int)$products_id . "', '" . tep_db_input($specials_price) . "', now(), '" . tep_db_input($expires_date) . "', '1')");
			*/
	    	tep_db_query("insert into " . TABLE_SPECIALS . " (products_id, specials_new_products_price, specials_date_added, expires_date, status, customers_group_id,specialStartDate) values ('" . (int)$products_id . "', '" . tep_db_input($specials_price) . "', now(), '" . tep_db_input($expires_date) . "', '1', ".(int)$customers_group.",'".$start_date."')");
			// EOF Separate Pricing Per Customer
        	require(DIR_WS_FUNCTIONS . 'SocialRunnerConnector.php');
        	SrBroadcast($products_id);
        	tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page']));
        }
        break;
      case 'update':
        $specials_id = tep_db_prepare_input($_POST['specials_id']);
        $products_price = tep_db_prepare_input($_POST['products_price']);
        $specials_price = tep_db_prepare_input($_POST['specials_price']);
        $expires_date = tep_db_prepare_input($_POST['expires_date']);
        $start_date = tep_db_prepare_input($_POST['specialStartDate']);

        if (substr($specials_price, -1) == '%' && $customers_group != '0')
		//if (substr($specials_price, -1) == '%')
			$specials_price = ($products_price - (($specials_price / 100) * $products_price));

        tep_db_query("update " . TABLE_SPECIALS . " set specials_new_products_price = '" . tep_db_input($specials_price) . "', specials_last_modified = now(), expires_date = '" . tep_db_input($expires_date) . "',specialStartDate='" . tep_db_input($start_date) . "' where specials_id = '" . (int)$specials_id . "'");

        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $specials_id));
        break;
      case 'deleteconfirm':
        $specials_id = tep_db_prepare_input($_GET['sID']);

        tep_db_query("delete from " . TABLE_SPECIALS . " where specials_id = '" . (int)$specials_id . "'");

        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page']));
        break;
      case 'promote_special':
        if (isset($_GET['pID'])) {
           $products_id = tep_db_prepare_input($_GET['pID']);
           require(DIR_WS_FUNCTIONS . 'SocialRunnerConnector.php');
           SrBroadcast($products_id);
        }
        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page']));
        break;
    }
  }
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>
<?php echo HEADING_TITLE; ?></h1></div>

          <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-money fa-5x pull-left"></i>
Here you can add and edit specials. The specials feature also supports special pricing per customer groups so you can have a special price for each group. Additionally expiration date is supported.                         </div>
                      </div>
                  </div>   
              </div>  

<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
    $form_action = 'insert';
    if ( ($action == 'edit') && isset($_GET['sID']) ) {
      $form_action = 'update';

      // BOF Separate Pricing Per Customer
      $product_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, s.specials_new_products_price, s.specialStartDate, s.expires_date, s.customers_group_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = s.products_id and s.specials_id = '" . (int)$_GET['sID'] . "'");
	  // EOF Separate Pricing Per Customer

	  $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $product['products_id']. "' and customers_group_id =  '" . $product['customers_group_id'] . "'");
       if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
            $product['products_price']= $customer_group_price['customers_group_price'];
       }

      $product = tep_db_fetch_array($product_query);

      $sInfo = new objectInfo($product);
    } else {
      $sInfo = new objectInfo(array());

// create an array of products on special, which will be excluded from the pull down menu of products
// (when creating a new product on special)
	/*
      $specials_array = array();

      $specials_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s where s.products_id = p.products_id");
      while ($specials = tep_db_fetch_array($specials_query)) {
        $specials_array[] = $specials['products_id'];
      }
	  */

		$specials_array = array();
		$specials_query = tep_db_query("select p.products_id, s.customers_group_id from " .  TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s where s.products_id = p.products_id");
		while ($specials = tep_db_fetch_array($specials_query)) {
		   $specials_array[] = (int)$specials['products_id'].":".(int)$specials['customers_group_id'];
		}

		if(isset($_GET['sID']) && $sInfo->customers_group_id!= '0'){
			$customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $sInfo->products_id . "' and customers_group_id =  '" . $sInfo->customers_group_id . "'");
			  if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
				$sInfo->products_price = $customer_group_price['customers_group_price'];
			  }
		}



    }
?>
     <form name="new_special" <?php echo 'action="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('specials_id', $_GET['sID']); ?>
   
<table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_PRODUCT; ?>&nbsp;</td>
    		<td class="main"><?php echo (isset($sInfo->products_name)) ? $sInfo->products_name . ' <small>(' . $currencies->format($sInfo->products_price) . ')</small>' : tep_draw_products_pull_down('products_id', '', $specials_array); echo tep_draw_hidden_field('products_price', (isset($sInfo->products_price) ? $sInfo->products_price : '')); ?></td>
          </tr>
		  <!-- BOF Separate Pricing per Customer -->
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_GROUPS; ?>&nbsp;</td>
            <td class="main"><?php if (isset($sInfo->customers_group_id)) {
            for ($x=0; $x<count($input_groups); $x++) {
            if ($input_groups[$x]['id'] == $sInfo->customers_group_id) {
            echo $input_groups[$x]['text'];
            }
            } // end for loop
            } else {
         echo tep_draw_pull_down_menu('customers_group', $input_groups, (isset($sInfo->customers_group_id)?$sInfo->customers_group_id:''));
         } ?> </td>
<!-- EOF Separate Pricing per Customer -->
		  </tr>

	      <tr>
            <td class="main"><?php echo TEXT_SPECIALS_SPECIAL_PRICE; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('specials_price', (isset($sInfo->specials_new_products_price) ? $sInfo->specials_new_products_price : '')); ?></td>
          </tr>
          <tr>
            <td class="main">Start Date:&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('specialStartDate', (isset($sInfo->specialStartDate) ? $sInfo->specialStartDate : date('Y-m-d')), ' id="jQCal1"'); ?></a></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_EXPIRES_DATE; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('expires_date', (isset($sInfo->expires_date) ? $sInfo->expires_date : ''), ' id="jQCal2"'); ?></a></td>
          </tr>
        </table>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><br><?php echo TEXT_SPECIALS_PRICE_TIP; ?></td>
            <td class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.png', IMAGE_INSERT) : tep_image_submit('button_update.png', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a class="btn btn-default" href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . (isset($_GET['sID']) ? '&sID=' . $_GET['sID'] : '')) . '">' .  IMAGE_CANCEL . '</a>'; ?></td>
          </tr>
        </table>

      </form>

<?php
  } else {
?>
     
<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table class="table table-hover table-condensed table-responsive">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_PRICE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php

			// BOF Separate Pricing Per Customer
		/*    $specials_query_raw = "select p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = s.products_id order by pd.products_name"; */
			$all_groups = array();
			$customers_groups_query = tep_db_query("select customers_group_name, customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
			while ($existing_groups =  tep_db_fetch_array($customers_groups_query)) {
			  $all_groups[$existing_groups['customers_group_id']] = $existing_groups['customers_group_name'];
			}

		   $specials_query_raw = "select p.products_id, pd.products_name, p.products_price, s.specials_id, s.customers_group_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = s.products_id order by pd.products_name";

		   $customers_group_prices_query = tep_db_query("select s.products_id, s.customers_group_id, pg.customers_group_price from " . TABLE_SPECIALS . " s LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg using (products_id, customers_group_id) ");

		 while ($_customers_group_prices = tep_db_fetch_array($customers_group_prices_query)) {
		 $customers_group_prices[] = $_customers_group_prices;
		 }


	$specials_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $specials_query_raw, $specials_query_numrows);
    $specials_query = tep_db_query($specials_query_raw);


	// BOF Separate Pricing Per Customer
		$no_of_rows_in_specials = tep_db_num_rows($specials_query);
		while ($specials = tep_db_fetch_array($specials_query)) {
		for ($y = 0; $y < $no_of_rows_in_specials; $y++) {
		if ( tep_not_null($customers_group_prices[$y]['customers_group_price']) && $customers_group_prices[$y]['products_id'] == $specials['products_id'] && $customers_group_prices[$y]['customers_group_id'] == $specials['customers_group_id']) {
		$specials['products_price'] = $customers_group_prices[$y]['customers_group_price'] ;
		} // end if (tep_not_null($customers_group_prices[$y]['customers_group_price'] etcetera
		} // end for loop
	// EOF Separate Pricing Per Customer


      if ((!isset($_GET['sID']) || (isset($_GET['sID']) && ($_GET['sID'] == $specials['specials_id']))) && !isset($sInfo)) {
        $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$specials['products_id'] . "'");
        $products = tep_db_fetch_array($products_query);
        $sInfo_array = array_merge($specials, $products);
        $sInfo = new objectInfo($sInfo_array);
      }

      if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id)) {
        echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $specials['specials_id']) . '\'">' . "\n";
      }
?>
                <td  class="dataTableContent"><?php echo $specials['products_name']; ?></td>

				<!--
                <td  class="dataTableContent"><span class="oldPrice"><?php echo $currencies->format($specials['products_price']); ?></span> <span class="specialPrice"><?php echo $currencies->format($specials['specials_new_products_price']); ?></span></td>
				-->
				<!-- BOF Separate Pricing Per Customer -->
                <td  class="dataTableContent"><span class="oldPrice"><?php echo $currencies->format($specials['products_price']); ?></span> <span class="specialPrice"><?php echo $currencies->format($specials['specials_new_products_price'])." (".$all_groups[$specials['customers_group_id']].")"; ?></span></td>
				<!-- EOF Separate Pricing per Customer -->





                <td  class="dataTableContent">
<?php
      if ($specials['status'] == '1') {
        echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=0&id=' . $specials['specials_id'], 'NONSSL') . '" title="This feature is date controlled, setting it manualy will have no effect, the items status will revert to that of the expiry date. If expiry date 0 then it will reactivate upon front end page load."><i class="fa fa-check-circle-o text-success"></i></a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=1&id=' . $specials['specials_id'], 'NONSSL') . '" title="This feature is date controlled, setting it manualy will have no effect, the items status will revert to that of the expiry date. If expiry date 0 then it will reactivate upon front end page load."><i class="fa fa-times-circle-o text-danger"></i></a>';
      }
?></td>
                <td class="dataTableContent"><?php if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id)) { echo '<i class="fa fa-long-arrow-right"></i>'; } else { echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $specials['specials_id']) . '"><i class="fa fa-hand-o-up"></i></a>'; } ?>&nbsp;</td>
      </tr>
<?php
    }
?>
              <tr>
                <td colspan="4">
                
                
                  <tr>
                    <td class="smallText" valign="top"><?php echo $specials_split->display_count($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
                    <td class="smallText" align="right" colspan="4"><?php echo $specials_split->display_links($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="4" align="right"><?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&action=new') . '">' .  IMAGE_NEW_PRODUCT . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
              
            </table></td> 
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SPECIALS . '</b>');

      $contents = array('form' => tep_draw_form('specials', FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $sInfo->products_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . '&nbsp;<a class="btn btn-default" href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id) . '">' .  IMAGE_CANCEL . '</a>');
      break;
    default:
      if (is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a class="btn btn-default" href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=edit') . '">' .  IMAGE_EDIT . '</a> <a class="btn btn-default" href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=delete') . '">' .  IMAGE_DELETE . '</a><a class="btn btn-default" href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&pID=' . $sInfo->products_id . '&action=promote_special') . '">Promote</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($sInfo->specials_date_added));
        $contents[] = array('text' => '<br>' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($sInfo->specials_last_modified));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($sInfo->products_image, $sInfo->products_name,'200', '100%'));
        $contents[] = array('text' => '<br>' . TEXT_INFO_ORIGINAL_PRICE . ' ' . $currencies->format($sInfo->products_price));
        $contents[] = array('text' => '<br>' . TEXT_INFO_NEW_PRICE . ' ' . $currencies->format($sInfo->specials_new_products_price));
        $contents[] = array('text' => '<br>' . TEXT_INFO_PERCENTAGE . ' ' . ($sInfo->products_price == 0 ? 0.00 : number_format(100 - (($sInfo->specials_new_products_price / $sInfo->products_price) * 100))) . '%');

        $contents[] = array('text' => '<br>' . TEXT_INFO_EXPIRES_DATE . ' <strong>' . tep_date_short($sInfo->expires_date) . '</strong>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_STATUS_CHANGE . ' ' . tep_date_short($sInfo->date_status_change));
      }
      break;
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '           <td valign="top"  width="220px">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td> </tr>
        </table>' . "\n";
  }
}
?>
        
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
