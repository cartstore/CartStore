<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo GOOGLE_UA; ?>']);
  _gaq.push(['_trackPageview']);
<?php
    $orders_query = tep_db_query("select * from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id_temp . "' order by date_purchased desc limit 1");
    $orders = tep_db_fetch_array($orders_query);
    $products_array = array();
    $products_query = tep_db_query("select orders_products_id, products_id, products_name, products_model, products_price, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders['orders_id'] . "' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $variations = array();
      $attributes_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_products_id = " . $products['orders_products_id']);
      while ($attributes = tep_db_fetch_array($attributes_query)){
        $variations[] = $attributes['products_options'] . ": " . $attributes['products_options_values'];
      }
      if (!empty($variations)){ // use attributes
          $product_cat_var = implode(', ',$variations);
      } else { // get product category
        $cat_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where cd.categories_id = p2c.categories_id AND p2c.products_id = " . $products['products_id']);
        $cat = tep_db_fetch_array($cat_query);
        $product_cat_var = $cat['categories_name'];
      }

      echo "
       _gaq.push(['_addItem',
          '" . $orders['orders_id'] . "',         // order ID - necessary to associate item with transaction
          '" . $products['products_model'] . "',         // SKU/code - required
          '" . $products['products_name'] . "',      // product name - necessary to associate revenue with product
          '" . $product_cat_var . "', // category or variation
          '" . $products['products_price'] . "',        // unit price - required
          '" . $products['products_quantity'] . "'             // quantity - required
       ]);
       ";
    }

    $order_totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = " . $orders['orders_id']);
    $ot = array();
    while ($totals = tep_db_fetch_array($order_totals_query)){
     $ot[$totals['class']] = $totals['value'];
    }
    echo "
           _gaq.push(['_addTrans',
          '" . $orders['orders_id'] . "',           // order ID - required
          'CartStore',  // affiliation or store nam
          '" . $ot['ot_total'] . "',          // total - required
          '" . $ot['ot_tax'] . "',           // tax
          '" . $ot['ot_shipping'] . "',          // shipping
          '" . $orders['customers_city'] . "',       // city
          '" . $orders['customers_state'] . "',     // state or province
          '" . $orders['customers_country'] . "'             // country
       ]);
    ";



?>
   _gaq.push(['_trackTrans']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- INSERT INTO `configuration` (`configuration_title`,`configuration_key`,`configuration_value`,`configuration_description`,`configuration_group_id`,`sort_order`,`date_added`) VALUES ('Google Analytics Key','GOOGLE_UA','UA-123456789','Google Analytics Key',1,60,now()) -->