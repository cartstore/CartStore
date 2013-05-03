<?php
/*
  $Id: options_images.php,v 1.0 2003/08/18 

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
?>

          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <?php  echo '<td class="main" colspan="2">' . TEXT_PRODUCT_OPTIONS . '<br>Please select your desired option using the buttons provided'; ?>
							<?php if (OPTIONS_IMAGES_CLICK_ENLARGE == 'true') echo '<br>Click the images to enlarge';?>
							</td>
            </tr>
<?php
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name, popt.products_options_images_enabled from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pov.products_options_values_thumbnail, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
        while($products_options = tep_db_fetch_array($products_options_query)){ 
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name'], 'thumbnail' => $products_options['products_options_values_thumbnail']);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
          }
        }
				
				  if (isset($cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          $selected_attribute = $cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        }

?>
 
            <tr>
              <td class="main" valign="top"><?php echo $products_options_name['products_options_name'] . ':'; ?></td>
    
              
							
<?php 
							if ($products_options_name['products_options_images_enabled'] == 'false'){
							  echo '<td class="main">' . tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute) . '</td></tr>';
               }     
               else {
							  $count=0;
								echo '<td class="main"><table cellpadding=0 cellspacing=0 border=0><tr>';
                foreach ($products_options_array as $opti_array){
							    echo '<td><table cellspacing="0" cellpadding="0" border="0">';
							    if (OPTIONS_IMAGES_CLICK_ENLARGE == 'true') 
								    echo '<td align="center"><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_OPTIONS_IMAGES_POPUP, 'oID=' . $opti_array['id']) .'\')">' . tep_image(DIR_WS_IMAGES . 'options/' . $opti_array['thumbnail'], $opti_array['text'], OPTIONS_IMAGES_WIDTH, OPTIONS_IMAGES_HEIGHT) . '</a></td></tr>';
								    else echo '<tr><td align="center">' . tep_image(DIR_WS_IMAGES . 'options/' . $opti_array['thumbnail'], $opti_array['text'], OPTIONS_IMAGES_WIDTH, OPTIONS_IMAGES_HEIGHT) . '</td></tr>';
							        echo '<tr><td class="main" align="center">' . $opti_array['text'] . '</td></tr>';
							        echo '<tr><td align="center"><input type="radio" name ="id[' . $products_options_name['products_options_id'] . ']" value="' . $opti_array['id'] . '" checked></td></tr></table></td>';
							        $count++;
                      if ($count%OPTIONS_IMAGES_NUMBER_PER_ROW == 0) {
							 	        echo '</tr><tr>';
								        $count = 0;
							        }
							      }
									echo '</table>';
								}

?>
        </td></tr>
<?php
      }
?>
     </table>
<?php
    }
?>