<?php
/*
  $Id: event_calendar.php,v 2.0 2003/05/09 13:25:51 ip chilipepper.it Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2001 osCommerce
  Released under the GNU General Public License
*/
?>

<td width="33%" align="center">

<!-- manufacturers //-->
<?php
  $heading = array();
  $heading[] = array('align' => 'center', 'text'  => TEXT_CHOOSE_MANUFACTURER);
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");

    $select_box = '<select name="manufacturers_id" onChange="submit();">';
    if (MAX_MANUFACTURERS_LIST < 2) {
      $select_box .= '<option value=""></option>';
    }
    while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) {
      $select_box .= '<option value="' . $manufacturers_values['manufacturers_id'] . '"';
      //if ($_POST['manufacturers_id'] == $manufacturers_values['manufacturers_id']) $select_box .= ' SELECTED';
      $select_box .= '>' . substr($manufacturers_values['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</option>';
    }
    $select_box .= "</select>";

  $info_box_contents = array();
  $info_box_contents[] = array('form'  => '', 'align' => '', 'text'  => $select_box );
  $box = new box;
  echo $box->menuBox($heading, $info_box_contents);
?>
<!-- manufacturers_end //-->

</td><td width="33%" align="center">

<!-- categories //-->
<?php
  $heading = array();
  $heading[] = array('align' => 'center', 'text'  => TEXT_CHOOSE_CATEGORY);
  $info_box_contents = array();
  $info_box_contents[] = array('form'  => '', 'align' => '', 'text'  => tep_draw_pull_down_menu('cPath', tep_get_category_tree(), '', 'onChange="submit();"'));
  $box = new box;
  echo $box->menuBox($heading, $info_box_contents);
?>
<!-- categories_end //-->

</td><td width="33%" align="center" valign="top">

<!-- products //-->
<?php
$date = date('Y-m-d H:i:s');
if ($_POST['manufacturers_id']) {
// We show all products by that manufacturer
        $listing_query = tep_db_query("select p.products_id, pd.products_name from  " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where
                                     p.manufacturers_id = '" . $_POST['manufacturers_id'] . "' and
                                     pd.products_id = p.products_id and
                                     pd.language_id = '" . $languages_id . "' ");

} else {
// We show all products in a specific category where status = 1
        $listing_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where
                                     pd.language_id = '" . $languages_id . "' and
                                     p2c.categories_id = '" . $_POST['cPath'] . "' and
                                     p.products_id = p2c.products_id and
                                     pd.products_id = p2c.products_id ");
      }
      
if (tep_db_num_rows($listing_query) > 0) {

  $heading = array();
  $heading[] = array('align' => 'center', 'text' => TEXT_CHOOSE_PRODUCT );
  $info_box_contents = array();
  $products_array = array(array('id' => '', 'text' => ''));
  while ($listing_products = tep_db_fetch_array($listing_query)) {
  $products_array[] = array('id' => $listing_products['products_id'], 'text' => $listing_products['products_name']);
  }
  $info_box_contents[] = array('form' => '', 'align' => '', 'text' => tep_draw_pull_down_menu('products_id', $products_array, '','onChange="submit();"'));
  $box = new box;
  echo $box->menuBox($heading, $info_box_contents);
} else {
  $heading = array();
  $heading[] = array('align' => 'center', 'text' => TEXT_CHOOSE_PRODUCT );
  $info_box_contents = array();
  $box = new box;
  echo $box->menuBox($heading,'');
}
?>
<!-- products end//-->

</td></tr><tr><td colspan="3" >

<!-- upcoming products //-->
<?php
// We select all products not available yet
  $upcoming_query = tep_db_query("select p.products_id, p.products_date_available, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where
                                     pd.language_id = '" . $languages_id . "' and
                                     p.products_id = pd.products_id and
                                     p.products_date_available != 0");

if (tep_db_num_rows($upcoming_query) > 0) {

  $heading = array();
  $heading[] = array('align' => 'center', 'text' => TEXT_CHOOSE_UPCOMING );
  $info_box_contents = array();
  $upcoming_array = array(array('id' => '', 'text' => ''));
  while ($upcoming_products = tep_db_fetch_array($upcoming_query)) {
  $upcoming_array[] = array('id' => $upcoming_products['products_id'], 'text' => $upcoming_products['products_name'] .' |   expected: '. substr($upcoming_products['products_date_available'],0,10));
  }
  
  $info_box_contents[] = array('form' => '', 'align' => '', 'text' => (tep_draw_pull_down_menu('upcoming', $upcoming_array, ' ','onChange="submit();"')));
  $box = new box;
  
  echo $box->menuBox($heading, $info_box_contents);
} else {
  $heading = array();
  $heading[] = array('align' => '', 'text' => TEXT_NO_UPCOMING_PRODUCTS );
  $info_box_contents = array();
  $box = new box;
 echo $box->menuBox($heading, $info_box_contents);  
}
?>
<!-- upcoming products end//-->
</td>

