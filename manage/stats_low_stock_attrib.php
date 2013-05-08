<?php
/*


  $Id: stats_products.php,v 1.22 2002/03/07 20:30:00 harley_vb Exp $
  (v 1.3 by Tom Wojcik aka TomThumb 2004/07/03)
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />
	 	
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="menuboxheading" align="center"><?php echo strftime(DATE_FORMAT_LONG); ?></td>
          </tr>

        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="formAreaTitle"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
				<td class="formAreaTitle"><?php echo TABLE_HEADING_MODEL; ?></td>
                <td class="formAreaTitle"><?php echo TABLE_HEADING_QUANTITY; ?></td>
                
                <td class="formAreaTitle" align="right"><?php echo TABLE_HEADING_PRICE; ?>&nbsp;</td>
                         </tr>
              <tr>
                <td colspan="4"><hr></td>
			                </tr>
<?php
  $products_query_raw = "select p.products_id, pd.products_name, p.products_model, p.products_quantity,p.products_price, l.name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_LANGUAGES . " l where p.products_id = pd.products_id and p.products_id = pd.products_id and l.languages_id = pd.language_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name ASC";
  
  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
      $products_id = $products['products_id'];

      // check for product or attributes below reorder level
      $products_stock_query=tep_db_query("SELECT products_stock_attributes, products_stock_quantity 
                                          FROM " . TABLE_PRODUCTS_STOCK . " 
                                          WHERE products_id=" . $products['products_id'] ." 
                                          ORDER BY products_stock_attributes");
      $products_stock_rows=tep_db_num_rows($products_stock_query);
	  // Highlight products with low stock
	  													   if ($products['products_quantity'] > STOCK_REORDER_LEVEL){
					   $trclass="dataTableRow";
					    } else { 
						$trclass="OutofStock";
						 } 
						 
      if (($products['products_quantity'] > (-1)) || ($products_stock_rows > 0)) {
        $products_quantity= $products['products_quantity'];
        $products_price=($products_stock_rows > 0) ? '&nbsp;' : $currencies->format($products['products_price']);
?>
             
              
              <?php
              
               /////////////////  Add Attributes
               

               
        if ($products_stock_rows > 0) {
          $products_options_name_query = tep_db_query("SELECT distinct popt.products_options_id, popt.products_options_name 
                                                       FROM " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib 
                                                       WHERE patrib.products_id='" . $products['products_id'] . "' 
                                                       AND patrib.options_id = popt.products_options_id 
                                                       AND popt.products_options_track_stock = '1' 
                                                       AND popt.language_id = '" . (int)$languages_id . "' 
                                                       ORDER BY popt.products_options_id");												   
						 
?>
<td colspan="4"><?php echo tep_draw_separator('pixel_trans.png', '10', '1.2'); ?></td>

					   <tr class="dataTableRow">
               <td class="dataTableContent" cellpadding="2"><?php echo '<a href="' . tep_href_link(FILENAME_STOCK, 'product_id=' . $products['products_id']) . '">' . $products['products_name'] .'</a>'; ?>&nbsp;</td>
			   <td class="dataTableContent" cellpadding="2"><?php echo $products['products_model']; ?></td>
               <td class="dataTableContent" cellpadding="2"><?php echo $products_quantity; ?></td>
               <td class="dataTableContent" align="right" cellpadding="2"><?php echo $products_price; ?>&nbsp;</td>
			   <td><BR></td>
              </tr>
			  
		    <tr class="dataTableRow">
              <td class="main">
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableRowSelected">
<?php
          // build headng line with option names
          while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
            echo "                    <td class=\"smalltext\">&nbsp;<u>" . $products_options_name['products_options_name'] . "</u></td>\n";
          }
?>
                  </tr>
<?php
          // buld array of attributes price delta
          $attributes_price = array();
          $products_attributes_query = tep_db_query("SELECT pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix 
                                                     FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                                                     WHERE pa.products_id = '" . $products['products_id'] . "'"); 
          while ($products_attributes_values = tep_db_fetch_array($products_attributes_query)) {
            $option_price = $products_attributes_values['options_values_price'];
            if ($products_attributes_values['price_prefix'] == "-") $option_price= -1*$option_price;
            $attributes_price[$products_attributes_values['options_id']][$products_attributes_values['options_values_id']] = $option_price;
          }
    
          // now display the attribute value names, table the html for quantity & price to get everything
          // to line up right
		  $model_html_table="                <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">\n";
          $model_html_table.="                  <tr class=\"dataTableRowSelected\"><td class=\"smalltext\" colspan=\"" . sizeof($products_options_array) . "\">&nbsp;</td></tr>\n";
          $quantity_html_table="               <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">\n";
          $quantity_html_table.="                  <tr class=\"dataTableRowSelected\"><td class=\"smalltext\" colspan=\"" . sizeof($products_options_array) . "\">&nbsp;</td></tr>\n";
          $price_html_table="                <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">\n";
          $price_html_table.="                  <tr class=\"dataTableRowSelected\"><td class=\"smalltext\" colspan=\"" . sizeof($products_options_array) . "\">&nbsp;</td></tr>\n";
          while($products_stock_values=tep_db_fetch_array($products_stock_query)) {
            $attributes=explode(",",$products_stock_values['products_stock_attributes']);
            echo "                  <tr class=\"dataTableRowSelected\">\n"; 
			
			$model_html_table.="                  <tr class=\"dataTableRowSelected\">\n";
            $quantity_html_table.="                  <tr class=\"dataTableRowSelected\">\n";
            $price_html_table.="                  <tr class=\"dataTableRowSelected\">\n";
            $total_price=$products['products_price'];
			
			// Highlight products out of stock
	  			 if (($products_stock_values['products_stock_quantity']) > STOCK_REORDER_LEVEL){
					   $trclassstock="dataTableContent";
					    } else {
						$trclassstock="OutofStockAttrib";
					 	}
						
						
            foreach($attributes as $attribute) {
              $attr=explode("-",$attribute);
              echo "<td class=\" " . $trclassstock . " \" >&nbsp;&nbsp;".tep_values_name($attr[1])."</td>\n";
              $total_price+=$attributes_price[$attr[0]][$attr[1]];
            }
			 $total_price=$currencies->format($total_price);
            echo "                  </tr>\n";

			$model_html_table.="<td class=\"" . $trclassstock . " \">&nbsp;</td>\n";
			$model_html_table.="</tr>\n";
            $quantity_html_table.="<td class=\"" . $trclassstock . " \">" . $products_stock_values['products_stock_quantity'] . "</td>\n";
            $quantity_html_table.="</tr>\n";
            $price_html_table.="<td align=\"right\" class=\" " . $trclassstock . " \">" . $total_price . "&nbsp;</td>\n";
            $price_html_table.="</tr>\n";
          }
          echo "                </table>\n";
          echo "              </td>\n";
		  $model_html_table.="                </table>\n";
          $quantity_html_table.="                </table>\n";
          $price_html_table.="                </table>\n";
          echo "              <td class=smalltext>" . $model_html_table . "</td>\n";
          echo "              <td class=smalltext>" . $quantity_html_table . "</td>\n";
		  echo "              <td class=smalltext>" . $price_html_table . "</td>\n";
          echo "            </tr>\n";
        
		}
		  else { ?>
		   <td colspan="4"><?php echo tep_draw_separator('pixel_trans.png', '10', '1.2'); ?></td>
                <tr class="<?php echo $trclass; ?>">
				
               <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_STOCK, 'product_id=' . $products['products_id']) . '">' . $products['products_name'] .'</a>'; ?>&nbsp;</td>
               <td class="dataTableContent"><?php echo $products['products_model']; ?></td>
               <td class="dataTableContent"><?php echo $products_quantity; ?></td>
               <td class="dataTableContent" align="right"><?php echo $products_price; ?>&nbsp;</td>
			   <td><BR></td>
              </tr>
			  
			  <?php }
		}
  ////////////////////////// End Attributes
  
  }
   
?>
              <tr>
                <td colspan="4"><?php echo tep_draw_separator(); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<font color="#FFCACB"
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>