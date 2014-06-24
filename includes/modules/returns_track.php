<?php
/*
$id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

*/


?>


<!-- body //-->



    
          <?php

         $account_query = tep_db_query("SELECT customers_firstname, customers_lastname, customers_email_address FROM " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
         $account = tep_db_fetch_array($account_query);
         // query the order table, to get all the product details

?>
      <b><?php echo TEXT_SUPPORT_PRODUCT_RETURN; ?></b><BR>
	  <?php echo HEADING_PRODUCTS; ?></b>
<?php

//  $ordered_product_query = tep_db_query("SELECT * FROM " . TABLE_ORDERS_PRODUCTS . " where order_id = '" . $_GET


    echo '          ' . "\n" .
         '            <br />
' . $returned_products['products_quantity'] . '' . " " .
         '            
' . $returned_products['products_name'];


echo ' ' . "";
echo '
' . $currencies->format(($returned_products['products_price'] + (tep_calculate_tax(($returned_products['products_price']),($returned_products['products_tax'])))) * ($returned_products['products_quantity'])) . '' . "\n" .
         '          ' . "\n";

?>
                <br /><br />

<b><?php echo TEXT_SUPPORT_BILLING_ADDRESS; ?></b><br />

         <?php
    echo '             ' . "\n" .
         '                ' . "\n" .
         '                ' . tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>') . '' . "\n" .
         '              ' . "\n";
           ?>
           <br /><br />

<b><?php echo TEXT_SUPPORT_DELIVERY_ADDRESS; ?></b><br />

         <?php
    echo '              ' . "\n" .
         '                ' . "\n" .
         '               ' . tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>') . '' . "\n" .
         '              ' . "\n";
           ?>
           <br /><br />

<b><?php echo TEXT_SUPPORT_USER_EMAIL; ?></b>

         <?php
    echo '              ' . "" .
         '                ' . "" .
         '               <br />
' . $account['customers_email_address'] . tep_draw_hidden_field('support_user_email', $account['customers_email_address']) . '' . "\n" .
         '              ' . "\n";

           ?>
            <br /><br />

<b><?php echo TEXT_WHY_RETURN; ?></b>:
<?php //echo tep_draw_input_field('link_url'); ?>
          <?php
            $reason_query = tep_db_query("SELECT return_reason_name FROM " . TABLE_RETURN_REASONS . " where return_reason_id = '" . $returned_products['returns_reason'] . "' and language_id = '" . $languages_id . "'");
            $reason = tep_db_fetch_array($reason_query);

             echo $reason['return_reason_name'];
          ?>
            <br /><br />

<b><?php echo TEXT_SUPPORT_TEXT; ?></b><br />

         <?php
    echo '              ' . "\n" .
         '                ' . "\n" .
         '               ' . nl2br($returned_products['comments']) . '</td>' . "\n" .
         '             ' . "\n";

           ?>
          
