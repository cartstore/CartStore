<?php
  require('includes/application_top.php');
  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ALLPRODS);
  $breadcrumb->add(HEADING_TITLE, tep_href_link(FILENAME_ALLPRODS, '', 'NONSSL'));
  $firstletter = $_GET['fl'];
  $where = "where $YMM_where pd.products_name like '$firstletter%' AND p.products_status='1' ";

  if (file_exists(DIR_WS_INCLUDES . 'header_tags.php')) {
      require(DIR_WS_INCLUDES . 'header_tags.php');
  } else {

  }

  require(DIR_WS_INCLUDES . 'header.php');

  require(DIR_WS_INCLUDES . 'column_left.php');
?>

<!-- body_text //-->






    <ul class="breadcrumb"> 
    	 <li><a class="" href="index.php">Home</a><span class="divider">/</span></li>
    	
      <?php
      echo $breadcrumb->trail(' &raquo; ');
	  echo '</ul>
	  
	  <h1>
        '.HEADING_TITLE .'
      </h1>
	  
	  
	  
	  ';

      $firstletter_nav = '
      
   <blockquote> <div class="btn-group">
<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
Find Product by First Letter
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
<!-- dropdown menu links -->
      
      
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=A', 'NONSSL') . '"> A </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=B', 'NONSSL') . '"> B </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=C', 'NONSSL') . '"> C </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=D', 'NONSSL') . '"> D </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=E', 'NONSSL') . '"> E </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=F', 'NONSSL') . '"> F </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=G', 'NONSSL') . '"> G </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=H', 'NONSSL') . '"> H </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=I', 'NONSSL') . '"> I </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=J', 'NONSSL') . '"> J </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=K', 'NONSSL') . '"> K </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=L', 'NONSSL') . '"> L </A></li>' . '
     <li> <a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=M', 'NONSSL') . '"> M </A></li>' . '
     <li> <a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=N', 'NONSSL') . '"> N </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=O', 'NONSSL') . '"> O </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=P', 'NONSSL') . '"> P </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=Q', 'NONSSL') . '"> Q </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=R', 'NONSSL') . '"> R </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=S', 'NONSSL') . '"> S </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=T', 'NONSSL') . '"> T </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=U', 'NONSSL') . '"> U </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=V', 'NONSSL') . '"> V </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=W', 'NONSSL') . '"> W </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=X', 'NONSSL') . '"> X </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=Y', 'NONSSL') . '"> Y </A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, 'fl=Z', 'NONSSL') . '"> Z</A></li>' . '
      <li><a href="' . tep_href_link(FILENAME_ALLPRODS, '', 'NONSSL') . '">' . HEADING_TITLE . '</A></li>
      </ul>
</div></blockquote>';
      echo $firstletter_nav;

      $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL, 'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME, 'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER, 'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE, 'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY, 'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT, 'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE, 'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);
      asort($define_list);
      $column_list = array();
      reset($define_list);
      while (list($column, $value) = each($define_list)) {
          if ($value)
              $column_list[] = $column;
      }
      $select_column_list = '';
      for ($col = 0, $n = sizeof($column_list); $col < $n; $col++) {
          if (($column_list[$col] == 'PRODUCT_LIST_BUY_NOW') || ($column_list[$col] == 'PRODUCT_LIST_NAME') || ($column_list[$col] == 'PRODUCT_LIST_PRICE')) {
              continue;
          }
      }
      $listing_sql = "select p.products_id, products_weight, p.products_quantity,p.map_price, p.msrp_price, p.products_model, pd.products_name, pd.products_description, pd.products_short, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, p.manufacturers_id from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id  $where";
      $sort_col = $_GET['sort_id'];
      $listing_sql .= ' order by ';
      switch ($sort_col) {
          case 'high':
              $listing_sql .= "products_price desc ";
              break;
          case 'low':
              $listing_sql .= "products_price asc ";
              break;
          case 'title':
              $listing_sql .= "pd.products_name ";
              break;
          default:
              case 'sortorder':
                  $listing_sql .= "p.pSortOrder ";
                  break;
          default:
              $listing_sql .= "p.pSortOrder ";
              break;
      }
      
      if (IS_MOBILE_DEVICE == TRUE)
				include (DIR_WS_MODULES . FILENAME_PRODUCT_LISTING_MOBILE);
			else
				include (DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);
		 
      
      
   
?>

<!-- body_text_eof //-->

<?php
      require(DIR_WS_INCLUDES . 'column_right.php');

      require(DIR_WS_INCLUDES . 'footer.php');

      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>