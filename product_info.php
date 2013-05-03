<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_PRODUCT_INFO == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);
  if (!tep_session_is_registered('sppc_customer_group_id')) {
      $customer_group_id = '0';
  } else {
      $customer_group_id = $sppc_customer_group_id;
  }

  require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_INCLUDES . 'column_left.php');
?>



  <!-- body_text //-->
<?php


			if (IS_MOBILE_DEVICE == TRUE) {
				          include(DIR_WS_TEMPLATES . 'pages/product_info_mobile.php');
				
			}else{
				          include(DIR_WS_TEMPLATES . 'pages/product_info.php');
				
		}

 
?>
  <!-- body_text_eof //-->



        <?php
                  require(DIR_WS_INCLUDES . 'column_right.php');
                  require(DIR_WS_INCLUDES . 'footer.php');
                  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>