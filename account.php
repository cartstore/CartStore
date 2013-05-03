<?php
/*
 $Id: account.php,v 1.61 2003/06/09 23:03:52 hpdl Exp $

 CartStore eCommerce Software, for The Next Generation
 http://www.cartstore.com

 Copyright (c) 2008 Adoovo Inc. USA

 GNU General Public License Compatible
 */

require('includes/application_top.php');

if (!tep_session_is_registered('customer_id')) {
$navigation->set_snapshot();
tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));

require(DIR_WS_INCLUDES . 'header.php'); 
require(DIR_WS_INCLUDES . 'column_left.php'); ?>
			

		<table width="100%" border="0">
			<tr>
				<td></td>
			</tr>
		</table>
        <div class="article_desc">
		<h1><?php echo HEADING_TITLE; ?></h1>

		<?php
		if ($messageStack->size('account') > 0) {
		?>
		<?php echo $messageStack->output('account'); ?>
		<?php
		}

		if (tep_count_customer_orders() > 0) {
		?>
<br>

		<h3><?php echo OVERVIEW_TITLE; ?>
		<ul>

		<?php echo '<li><a class="general_link" href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . OVERVIEW_SHOW_ALL_ORDERS . '</a></li>'; ?>

	</ul>
		<?php echo '<h3>' . OVERVIEW_PREVIOUS_ORDERS . '</h3>'; ?>


		<?php
		$orders_query = tep_db_query("select o.orders_id, o.date_purchased, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id desc limit 3");
		while ($orders = tep_db_fetch_array($orders_query)) {
		if (tep_not_null($orders['delivery_name'])) {
		$order_name = $orders['delivery_name'];
		$order_country = $orders['delivery_country'];
		} else {
		$order_name = $orders['billing_name'];
		$order_country = $orders['billing_country'];
		}
		?>
		<table width="100%" border="0">
			<tr>
				<td>
				<tr class="moduleRow" onMouseOver="rowOverEffect(this)"
					onMouseOut="rowOutEffect(this)"
					onClick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL'); ?>'">
					<td class="main" width="80"><?php echo tep_date_short($orders['date_purchased']); ?></td>
					<td class="main"><?php echo '#' . $orders['orders_id']; ?></td>
					<td class="main"><?php echo tep_output_string_protected($order_name) . ', ' . $order_country; ?></td>
					<td class="main"><?php echo $orders['orders_status_name']; ?></td>
					<td align="right"><?php echo $orders['order_total']; ?></td>
					<td class="main" align="right"><?php echo '<li><a class="button" href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL') . '">' . SMALL_IMAGE_BUTTON_VIEW . '</a></li>'; ?></td>
				</tr>

		</table>
		<?php
		}
		?> <?php
		}
		?>
<br>

		<h3><?php echo MY_ACCOUNT_TITLE; ?></h3>
		<ul>
		<?php echo ' <li><a class="general_link" href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MY_ACCOUNT_INFORMATION . '</a></li>'; ?>
		<?php echo '<li> <a class="general_link" href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MY_ACCOUNT_ADDRESS_BOOK . '</a></li>'; ?>
		<?php echo ' <li><a class="general_link" href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MY_ACCOUNT_PASSWORD . '</a></li>'; ?>
		</ul>

		<h3><?php echo MY_ORDERS_TITLE; ?></h3>


		<ul>
		<?php echo '<li> <a class="general_link" href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . MY_ORDERS_VIEW . '</a></li>'; ?>
</ul>

			<h3><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></h3>
<ul>
			<?php echo '<li> <a class="general_link" href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a></li>'; ?>
			<?php echo '<li> <a class="general_link" href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_PRODUCTS . '</a></li>'; ?>
		</ul>

		<!-- // Points/Rewards Module V2.00 points_system_box_bof //--> <?php
		if (USE_POINTS_SYSTEM == 'true') { // check that the points system is enabled
		?>


		<h3><?php echo MY_POINTS_TITLE; ?></h3>



		<?php
		$shopping_points = tep_get_shopping_points();
		if ($shopping_points > 0) {
		?> <?php echo ''.  sprintf(MY_POINTS_CURRENT_BALANCE, number_format($shopping_points,POINTS_DECIMAL_PLACES),$currencies->format(tep_calc_shopping_pvalue($shopping_points))); ?>
		<?php
		}
		?>

		<ul>
		<?php echo ' <li><a class="general_link" href="' . tep_href_link(FILENAME_MY_POINTS, '', 'SSL') . '">' . MY_POINTS_VIEW . '</a></li>'; ?>
		<?php echo '<li> <a class="general_link" href="' . tep_href_link(FILENAME_MY_POINTS_HELP, '', 'SSL') . '">' . MY_POINTS_VIEW_HELP . '</a></li>'; ?>
		</ul>

		<?php
		}// else do not show points_system_box
		?> <!-- // Points/Rewards Module V2.00 points_system_box_eof //-->
        </div><br>
<br>

        <div class="modulelist">
<?php include 'includes/modules/new_products.php';?>
</div>

		</td>
			</tr>
		</table>


			<?php require(DIR_WS_INCLUDES . 'column_right.php'); 
 require(DIR_WS_INCLUDES . 'footer.php'); 
 require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
