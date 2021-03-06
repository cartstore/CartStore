<?php
/*
      QT Pro Version 4.1

      stock.php

      Contribution extension to:
        CartStore eCommerce Software, for The Next Generation
        http://www.cartstore.com

      Copyright (c) 2004, 2005 Ralph Day
      GNU General Public License Compatible

      Based on prior works GNU General Public License Compatible:
        QT Pro prior versions
          Ralph Day, October 2004
          Tom Wojcik aka TomThumb 2004/07/03 based on work by Michael Coffman aka coffman
          FREEZEHELL - 08/11/2003 freezehell@hotmail.com Copyright (c) 2003 IBWO
          Joseph Shain, January 2003
        CartStore 2.0
          Copyright (c) 2008 Adoovo Inc. USA

      Modifications made:
        11/2004 - Add input validation
                  clean up register globals off problems
                  use table name constant for products_stock instead of hard coded table name
        03/2005 - Change $_SERVER to $_SERVER for compatibility with older php versions

*******************************************************************************************

      QT Pro Stock Add/Update

      This is a page to that is linked from the CartStore admin categories page when an
      item is selected.  It displays a products attributes stock and allows it to be updated.

*******************************************************************************************

  $Id: stock.php,v 1.00 2003/08/11 14:40:27 IBWO Exp $

  Enhancement module for CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Credit goes to original QTPRO developer.
  Attributes Inventory - FREEZEHELL - 08/11/2003 freezehell@hotmail.com
  Copyright (c) 2003 IBWO

  GNU General Public License Compatible
*/
  require('includes/application_top.php');

  if ($_SERVER['REQUEST_METHOD']=="GET") {
    $VARS=$_GET;
  } else {
    $VARS=$_POST;
  }
  if ($VARS['action']=="Add") {
    $inputok = true;
    if (!(is_numeric($VARS['product_id']) and ($VARS['product_id']==(int)$VARS['product_id']))) $inputok = false;
    while(list($v1,$v2)=each($VARS)) {
      if (preg_match("/^option(\d+)$/",$v1,$m1)) {
        if (is_numeric($v2) and ($v2==(int)$v2)) $val_array[]=$m1[1]."-".$v2;
        else $inputok = false;
      }
    }
    if (!(is_numeric($VARS['quantity']) and ($VARS['quantity']==(int)$VARS['quantity']))) $inputok = false;

    if (($inputok)) {
      sort($val_array, SORT_NUMERIC);
      $val=join(",",$val_array);
      $q=tep_db_query("select products_stock_id as stock_id from " . TABLE_PRODUCTS_STOCK . " where products_id=" . (int)$VARS['product_id'] . " and products_stock_attributes='" . $val . "' order by products_stock_attributes");
      if (tep_db_num_rows($q)>0) {
        $stock_item=tep_db_fetch_array($q);
        $stock_id=$stock_item[stock_id];
        if ($VARS['quantity']=intval($VARS['quantity'])) {
          tep_db_query("update " . TABLE_PRODUCTS_STOCK . " set products_stock_quantity=" . (int)$VARS['quantity'] . " where products_stock_id=$stock_id");
        } else {
          tep_db_query("delete from " . TABLE_PRODUCTS_STOCK . " where products_stock_id=$stock_id");
        }
      } else {
        tep_db_query("insert into " . TABLE_PRODUCTS_STOCK . " values (0," . (int)$VARS['product_id'] . ",'$val'," . (int)$VARS['quantity'] . ")");
      }
      $q=tep_db_query("select sum(products_stock_quantity) as summa from " . TABLE_PRODUCTS_STOCK . " where products_id=" . (int)$VARS['product_id'] . " and products_stock_quantity>0");
      $list=tep_db_fetch_array($q);
      $summa= (empty($list[summa])) ? 0 : $list[summa];
      tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity=$summa where products_id=" . (int)$VARS['product_id']);
      if (($summa<1) && (STOCK_ALLOW_CHECKOUT == 'false')) {
        tep_db_query("update " . TABLE_PRODUCTS . " set products_status='0' where products_id=" . (int)$VARS['product_id']);
      }
    }
  }
  if ($VARS['action']=="Update") {
    tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity=" . (int)$VARS['quantity'] . " where products_id=" . (int)$VARS['product_id']);
    if (($VARS['quantity']<1) && (STOCK_ALLOW_CHECKOUT == 'false')) {
      tep_db_query("update " . TABLE_PRODUCTS . " set products_status='0' where products_id=" . (int)$VARS['product_id']);
    }
  }
  if ($VARS['action']=="Apply to all") {

  }
  $q=tep_db_query($sql="select products_name,products_options_name as _option,products_attributes.options_id as _option_id,products_options_values_name as _value,products_attributes.options_values_id as _value_id from ".
                  "products_description, products_attributes,products_options,products_options_values where ".
                  "products_attributes.products_id=products_description.products_id and ".
                  "products_attributes.products_id=" . (int)$VARS['product_id'] . " and ".
                  "products_attributes.options_id=products_options.products_options_id and ".
                  "products_attributes.options_values_id=products_options_values.products_options_values_id and ".
                  "products_description.language_id=" . (int)$languages_id . " and ".
                  "products_options_values.language_id=" . (int)$languages_id . " and products_options.products_options_track_stock=1 and ".
                  "products_options.language_id=" . (int)$languages_id . " order by products_attributes.options_id, products_attributes.options_values_id");
 //list($product_name,$option_name,$option_id,$value,$value_id)
  if (tep_db_num_rows($q)>0) {
    $flag=1;
    while($list=tep_db_fetch_array($q)) {
      $options[$list[_option_id]][]=array($list[_value],$list[_value_id]);
      $option_names[$list[_option_id]]=$list[_option];
      $product_name=$list[products_name];
    }
 }
 //Commented out so items with 0 stock will show up in the stock report.
 else {
  //  $flag=0;
   $q=tep_db_query("select products_quantity,products_name from " . TABLE_PRODUCTS . " p,products_description pd where pd.products_id=" . (int)$VARS['product_id'] . " and p.products_id=" . (int)$VARS['product_id']);
    $list=tep_db_fetch_array($q);
    $db_quantity=$list[products_quantity];
    $product_name=stripslashes($list[products_name]);
  }
?>
 
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
 

<div class="page-header"><h1><?php echo PRODUCTS_STOCK.": $product_name" . '</h1></div>
<p>
<a class="btn btn-default" href="' . tep_href_link(FILENAME_CATEGORIES, '&pID=' . $VARS['product_id'] . '&action=new_product') . '">' .  IMAGE_EDIT . '</a> '; ?>
        </p>
          
<form action="<?php echo $PHP_SELF;?>" method=get>
       
<table class="table table-hover table-condensed table-responsive">
              <tr class="dataTableHeadingRow">
<?php
  $title_num=1;
  if ($flag) {
    while(list($k,$v)=each($options)) {
      echo "<td class=\"\">&nbsp;&nbsp;$option_names[$k]</td>";
      $title[$title_num]=$k;
    }
    echo "<td class=\"\"><label>Quantity</label></td><td width=\"100%\"></td>";
    echo "</tr>";
    $q=tep_db_query("select * from " . TABLE_PRODUCTS_STOCK . " where products_id=" . $VARS['product_id'] . " order by products_stock_attributes");
    while($rec=tep_db_fetch_array($q)) {
      $val_array=explode(",",$rec[products_stock_attributes]);
      echo "<tr>";
      foreach($val_array as $val) {
        if (preg_match("/^(\d+)-(\d+)$/",$val,$m1)) {
          echo "<td class=smalltext>".tep_values_name($m1[2])."</td>";
        } else {
          echo "<td></td>";
        }
      }
      for($i=0;$i<sizeof($options)-sizeof($val_array);$i++) {
        echo "<td></td>";
      }
      echo "<td class=smalltext>$rec[products_stock_quantity]</td><td></td></tr>";
    }
    echo "<tr>";
    reset($options);
    $i=0;
    while(list($k,$v)=each($options)) {
      echo "<td class=dataTableHeadingRow><select name=option$k>";
      foreach($v as $v1) {
        echo "<option value=".$v1[1].">".$v1[0];
      }
      echo "</select></td>";
      $i++;
    }
  } else {
    $i=1;
    echo "<td>Quantity</td>";
  }
  echo "<td class=dataTableHeadingRow><input class=\"form-control\" type=text name=quantity   value=\"" . $db_quantity . "\"><input type=hidden name=product_id value=\"" . $VARS['product_id'] . "\">&nbsp;</td>"
          . ""
          . "<td class=dataTableHeadingRow><input class=\"btn btn-default\" type=submit name=action value=" . ($flag?"Add":"Update") . ">&nbsp;</td>"
          . " ";
?>
              </tr>
            </table>

        </form>

<?php  echo '<hr><p><a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '" class="menuBoxContentLink">Back to Products Category</a></p> '
        . '<p><a href="' . tep_href_link(FILENAME_STATS_LOW_STOCK_ATTRIB, '', 'NONSSL') . '" class="menuBoxContentLink">Back to Low Stock Report for Attributes</a></p>';?>




<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>