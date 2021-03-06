<?php
/*
   $ID: prods_by_vendor.php (for use with MVS)
   by Craig Garrison Sr, BluCollar Sales
  for MVS V1.0 2006/03/25 JCK/CWG
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $line2 = $line;
  if (!isset($line)) {
  $line = 'p.products_price';
  }
  if ($line == 'prod') {
  $line = 'pd.products_name';
  } elseif ($line == 'vpid'){
  $line = 'p.vendors_prod_id';
  } elseif ($line == 'pid'){
  $line = 'p.products_id';
  } elseif ($line == 'qty'){
  $line = 'p.products_quantity';
  } elseif ($line == 'vprice'){
  $line = 'p.vendors_product_price';
  } elseif ($line == 'price'){
  $line = 'p.products_price';
  }

?>
 
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
 <div class="page-header"><h1><?php echo HEADING_TITLE; ?> </h1></div>
 
 
            <?php
            //vendors_email start
    $vendors_array = array(array('id' => '1', 'text' => 'NONE'));
    $vendors_query = tep_db_query("select vendors_id, vendors_name from " . TABLE_VENDORS . " order by vendors_name");
    while ($vendors = tep_db_fetch_array($vendors_query)) {
      $vendors_array[] = array('id' => $vendors['vendors_id'],
                                     'text' => $vendors['vendors_name']);
    }
          ?>
             <div class="form-group"><label>  
<?php echo TABLE_HEADING_VENDOR_CHOOSE . ' '; ?></label>
<?php echo tep_draw_form('vendors_report', FILENAME_PRODS_VENDORS) . tep_draw_pull_down_menu('vendors_id', $vendors_array,'','onChange="this.form.submit()";');?></form></div>



      
<?php echo '<a href="' . tep_href_link(FILENAME_VENDORS) . '"> Go To Vendors List</a>';?>


  
<p class="pull-right small">
            <?php
            if ($show_order == 'desc') {
             echo 'Click for <a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=' . $line . '&show_order=asc') . '">ascending order</a>';
             } else {
            echo 'Click for <a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=' . $line . '&show_order=desc') . '">descending order</a>';
            }
            ?>
         </p>

<?php
  if (isset($vendors_id)) { ?>
            <?php
  // $vendors_id = $_POST['vendors_id'];
  $vend_query_raw = "select vendors_name as name from " . TABLE_VENDORS . " where vendors_id = '" . $vendors_id . "'";
  $vend_query = tep_db_query($vend_query_raw);
  $vendors = tep_db_fetch_array($vend_query); ?>
      


<table class="table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_VENDOR; ?></td>
                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=prod') . '">' . TABLE_HEADING_PRODUCTS_NAME . '</a>'; ?>&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=vpid') . '">' . TABLE_HEADING_VENDORS_PRODUCT_ID . '</a>'; ?></td>
                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=pid') . '">' .  TABLE_HEADING_PRODUCTS_ID . '</a>'; ?></td>
                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=qty') . '">' .  TABLE_HEADING_QUANTITY . '</a>'; ?></td>
                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=vprice') . '">' .  TABLE_HEADING_VENDOR_PRICE . '</a>'; ?></td>
                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=price') . '">' .  TABLE_HEADING_PRICE . '</a>'; ?></td>
              </tr>
              <tr class="dataTableRow">
     <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_VENDORS, '&vendors_id=' . $vendors_id . '&action=edit') . '" TARGET="_blank">' . $vendors['name'] . '</a>'; ?></td>
     <td class="dataTableContent"><?php echo ''; ?></td>
     <td class="dataTableContent"><?php echo ''; ?></td>
     <td class="dataTableContent"><?php echo ''; ?></td>
     <td class="dataTableContent"><?php echo ''; ?></td>
     <td class="dataTableContent"><?php echo ''; ?></td>
     <td class="dataTableContent"><?php echo ''; ?></td>

<?php
 // if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $rows = 0;
          if($show_order == 'desc') {
  $products_query_raw = "select p.products_id, p.vendors_id, pd.products_name, p.products_quantity , p.products_price, p.vendors_product_price, p.vendors_prod_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.vendors_id = " . $vendors_id . " and pd.language_id = " . $languages_id . " order by " . $line . " desc";
          } elseif ($show_order  == 'asc') {
   $products_query_raw = "select p.products_id, p.vendors_id, pd.products_name, p.products_quantity , p.products_price, p.vendors_product_price, p.vendors_prod_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.vendors_id = " . $vendors_id . " and pd.language_id = " . $languages_id . " order by " . $line . " asc";
          } else {
    $products_query_raw = "select p.products_id, p.vendors_id, pd.products_name, p.products_quantity , p.products_price, p.vendors_product_price, p.vendors_prod_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.vendors_id = " . $vendors_id . " and pd.language_id = " . $languages_id . " order by " . $line . "";
    }
/*  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
  Decide not to use SPLIT pages for the $vendors_id variable not being maintained.
  */

  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
               <tr class="dataTableRow">
                <?php if($products['vendors_prod_id']=='') {
                    $products['vendors_prod_id']= 'None Specified';
                    } ?>
                <td class="dataTableContent"><?php echo ''; ?></td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product&pID=' . $products['products_id']) . '" TARGET="_blank">' . $products['products_name'] . '</a>'; ?></td>
                <td class="dataTableContent"><?php echo $products['vendors_prod_id']; ?></td>
                <td class="dataTableContent"><?php echo $products['products_id']; ?></td>
                <td class="dataTableContent"><?php echo $products['products_quantity']; ?>&nbsp;</td>
                <td class="dataTableContent"><?php echo $products['vendors_product_price']; ?></td>
                <td class="dataTableContent"><?php echo $products['products_price']; ?></td>
                  </tr>
<?php
  }
  }
?>
            </table>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>