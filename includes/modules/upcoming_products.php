<?php
/*
  $Id: upcoming_products.php,v 1.24 2003/06/09 22:49:59 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  $expected_query = tep_db_query("select p.products_id, pd.products_name, products_date_available as date_expected from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_UPCOMING_PRODUCTS == 'Yes' ? $YMM_where : '') . " to_days(products_date_available) >= to_days(now()) and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT . " limit " . MAX_DISPLAY_UPCOMING_PRODUCTS);
  if (tep_db_num_rows($expected_query) > 0) {
?>
<!-- upcoming_products //-->

 
 					
					 
 					
				<ul class="list-group">
					
	
			<?php
			$row = 0;
			while ($expected = tep_db_fetch_array($expected_query)) {
				$row++;
				if (($row / 2) == floor($row / 2)) {
					echo '';
				} else {
					echo ' <li class="list-group-item">';
				}

				echo '  	<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected['products_id']) . '">' . $expected['products_name'] . '
				 
						 
				  
 				 ' . tep_date_short($expected['date_expected']) . '</a></li>';
			}
			?>
		</ul>
<!-- upcoming_products_eof //-->
<?php
}
?>
