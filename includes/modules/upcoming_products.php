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

<div class="module-upcoming">

					<div class="upcomingtitle clearfix">
						<div class="title">
						<?php echo TABLE_HEADING_UPCOMING_PRODUCTS;?>
						</div>
						<div class="date">
							<?php echo TABLE_HEADING_DATE_EXPECTED;?>
						</div>
					</div>
					
					
					
	
			<?php
			$row = 0;
			while ($expected = tep_db_fetch_array($expected_query)) {
				$row++;
				if (($row / 2) == floor($row / 2)) {
					echo '<div class="item clearfix">
						<div class="itemname">';
				} else {
					echo '<div class="item gray clearfix">
						<div class="itemname">';
				}

				echo '  	<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected['products_id']) . '">' . $expected['products_name'] . '</a>
				</div>
						<div class="itemdate">
				  
				' . tep_date_short($expected['date_expected']) . '</div>
					</div>';
			}
			?>
		</div>
<!-- upcoming_products_eof //-->
<?php
}
?>
