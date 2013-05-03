<?php
/*
 $Id: wa_taxes_report.php, v1.29 2008/12/08 lildog Exp $

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2003 osCommerce

 Released under the GNU General Public License
 */

require ('includes/application_top.php');

require (DIR_WS_CLASSES . 'currencies.php');
require (DIR_WS_FUNCTIONS . 'wa_taxes_report.php');

$currencies = new currencies();

//set up the date
if ($_GET['month'] == '') {
	$month = date('m');
} else {
	$month = $_GET['month'];
}

if ($month != 555) {
	$tmonth = " AND month(o.date_purchased) = " . $month . " ";
} else {
	$tmonth = '';
}

if ($_GET['year'] == '') {
	$year = date('Y');
} else {
	$year = $_GET['year'];
}

$months = array();
$months[] = array('id' => 555, 'text' => TEXT_ENTIRE_YEAR);
$months[] = array('id' => 1, 'text' => 'January');
$months[] = array('id' => 2, 'text' => 'February');
$months[] = array('id' => 3, 'text' => 'March');
$months[] = array('id' => 4, 'text' => 'April');
$months[] = array('id' => 5, 'text' => 'May');
$months[] = array('id' => 6, 'text' => 'June');
$months[] = array('id' => 7, 'text' => 'July');
$months[] = array('id' => 8, 'text' => 'August');
$months[] = array('id' => 9, 'text' => 'September');
$months[] = array('id' => 10, 'text' => 'October');
$months[] = array('id' => 11, 'text' => 'November');
$months[] = array('id' => 12, 'text' => 'December');

$years = array();
$years[] = array('id' => date('Y') - 4, 'text' => date('Y') - 4);
$years[] = array('id' => date('Y') - 3, 'text' => date('Y') - 3);
$years[] = array('id' => date('Y') - 2, 'text' => date('Y') - 2);
$years[] = array('id' => date('Y') - 1, 'text' => date('Y') - 1);
$years[] = array('id' => date('Y'), 'text' => date('Y'));

$status = (int)$_GET['status'];

$statuses_query = tep_db_query("select * from orders_status where language_id = '" . (int)$languages_id . "' order by orders_status_id");
$statuses = array();
$statuses[] = array('id' => 0, 'text' => TEXT_ORDERS_STATUS);
while ($st = tep_db_fetch_array($statuses_query)) {
	$statuses[] = array('id' => $st['orders_status_id'], 'text' => $st['orders_status_name']);
}

if ($status != 0) {
	$os = " and o.orders_status = " . $status . " ";
} else {
	$os = '';
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS;?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
		<title><?php echo TITLE;?></title>
		<br>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
		<script language="javascript" src="includes/general.js"></script>
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
		<!-- header //-->
		<?php
		if ($printable != 'on') {
			require (DIR_WS_INCLUDES . 'header.php');
		};
		?>
		<!-- header_eof //-->
		<!-- body //-->
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
			<tr>
				<td width="<?php echo BOX_WIDTH;?>" valign="top">
				<table border="0" width="<?php echo BOX_WIDTH;?>" cellspacing="1" cellpadding="1" class="columnLeft">
					<!-- left_navigation //-->
					<?php
						require (DIR_WS_INCLUDES . 'column_left.php');
					?>
					<!-- left_navigation_eof //-->
				</table><!-- body_text //--><td width="100%" valign="top">
				<table border="0" align="center" width="95%" cellspacing="0" cellpadding="2">
					<tr>
						<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td class="pageHeading"><?php echo HEADING_TITLE;?></td>
								<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);?></td>
							</tr>
						</table></td>
					</tr>
					<tr>
						<td>
						<table border="0" align="left" width="100%" cellspacing="0" cellpadding="2">
							<?php echo tep_draw_form('date_range', FILENAME_WA_TAXES_REPORT, '', 'get');?>
							<tr>
								<td class="smallText"><?php
								echo ENTRY_STATUS . '&nbsp;&nbsp;' . tep_draw_pull_down_menu('status', $statuses, $status, 'onchange=\'this.form.submit();\'') . '&nbsp;&nbsp;&nbsp;&nbsp;';
								echo '&nbsp;' . ENTRY_PRINTABLE . tep_draw_checkbox_field('printable', $print) . '<br><br>';

								echo TEXT_DATE . '<br>';
								echo ENTRY_YEAR . '&nbsp;&nbsp;' . tep_draw_pull_down_menu('year', $years, $year, 'onchange=\'this.form.submit();\'') . '&nbsp;&nbsp;&nbsp;&nbsp;';
								echo ENTRY_MONTH . '&nbsp;&nbsp;' . tep_draw_pull_down_menu('month', $months, $month, 'onchange=\'this.form.submit();\'') . '&nbsp;&nbsp;&nbsp;&nbsp;';

								echo '</td></form>';
								?>
							</tr>
						</table></td>
					</tr>
					<tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10');?></td>
					</tr>
					<tr>
						<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td valign="top">
								<table border="0" width="100%" cellspacing="0" cellpadding="2">
									<tr class="dataTableHeadingRow">
										<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OID;?></td>
										<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE;?></td>
										<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX_COLLECTED;?></td>
										<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ZONE_ID;?>&nbsp;</td>
									</tr>
									<?php
$wa_orders_query_raw = "SELECT o.orders_id, o.date_purchased, o.delivery_street_address, o.delivery_city, o.delivery_postcode, o.wa_dest_tax as tax_zone, s.orders_status_name, ot.text as tax_total FROM " . TABLE_ORDERS . " o LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot ON (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s WHERE o.orders_status = s.orders_status_id ". $os ."AND s.language_id = '" . (int)$languages_id . "' AND ot.class = 'ot_tax' AND year(o.date_purchased) = " . $year . $tmonth. " ORDER BY o.orders_id DESC";

$rows = 0;
$wa_tax_query = tep_db_query($wa_orders_query_raw);

$zone_totals_array = array();

while ($wa_orders = tep_db_fetch_array($wa_tax_query)) {
$rows ++;
if (strlen($rows) < 2) {
$rows = '0' . $rows;
}
									?>
									<tr bgcolor="<?php echo ((++$cnt)%2 == 0) ? '#E0E0E0' : '#FFFFFF' ?>" id="defaultSelected" class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)">
										<td class="dataTableContent"><?php $wa_oid = $wa_orders['orders_id'];?>
										<?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, 'oID=' . $wa_oid) . '">' . $wa_oid . '</a>'
										?></td>
										<td class="dataTableContent"><?php echo $wa_orders['date_purchased'];?></td>
										<td class="dataTableContent"><?php echo $wa_orders['tax_total'];?></td>
										<td class="dataTableContent"><?php
										if ($wa_orders['tax_zone']) {
											echo $wa_orders['tax_zone'];
										} else {
											$new_zone = parse_DOR($wa_orders['delivery_street_address'], $wa_orders['delivery_city'], $wa_orders['delivery_postcode']);
											if ($new_zone) {
												$wa_orders['tax_zone'] = $new_zone;
												echo $wa_orders['tax_zone'];
												tep_db_query("update " . TABLE_ORDERS . " set wa_dest_tax ='" . $new_zone . "' where orders_id = '" . (int)$wa_oid . "'");
											} else {
												echo TEXT_UNKNOWN;
											}
										}
										?></td>
										<?php
										//add the zone totals
										$zone_id = $wa_orders['tax_zone'];
										$zone_total = tep_strip_currency_sign($wa_orders['tax_total']);
										$zone_prev_total = $zone_totals_array[$zone_id];
										$zone_final_total = $zone_prev_total + $zone_total;
										$zone_totals_array[$zone_id] = $zone_final_total;
										?>
									</tr>
									<?php
									}
									?>
									<tr>
										<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5');?></td>
									</tr>
									<tr class="dataTableHeadingRow">
										<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ZONE_ID;?></td>
										<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ZONE_TAX_TOTAL;?></td>
										<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ZONE_SUBTOTAL_TOTAL;?></td>
										<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ZONE_TOTAL_TOTAL;?></td>
									</tr>
									<tr>
										<?php
foreach ($zone_totals_array as $zone_title => $zone_sum){
	$totals_query = tep_db_query( "SELECT ot.class, ot.value FROM " . TABLE_ORDERS . " o LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot ON (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s WHERE o.orders_status = s.orders_status_id ". $os ."AND s.language_id = '" . (int)$languages_id . "' AND year(o.date_purchased) = " . $year . $tmonth. " and o.wa_dest_tax = '" . $zone_title . "'" );
	$totals_total =- $subtotals_total = 0.00;
	while ($totals = tep_db_fetch_array($totals_query)){
		switch ($totals['class']){
			case 'ot_subtotal':
				$subtotals_total += (float)$totals['value'];
				break;
			case 'ot_total':
				$totals_total += (float)$totals['value'];
				break;
		}
	} 
	
										?>
									<tr>
										<td class="dataTableContent"><?php
										if ($zone_title) {
											echo $zone_title;
										} else {
											echo TEXT_UNKNOWN;
										}
										?></td>
										<td class="dataTableContent"><?php echo $zone_sum; ?></td>
										<td class="dataTableContent"><?php echo $currencies->format( $subtotals_total ); ?></td>
										<td class="dataTableContent"><?php echo $currencies->format( $totals_total ); ?></td>
									</tr>
									<?php
									}
									?>
							</tr>
						</table></td>
					</tr>
					<tr>
						<td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2"></table></td>
					</tr>
				</table></td>
			</tr>
		</table></td>
		<!-- body_text_eof //-->
		</tr>
		</table> <!-- body_eof //-->
		<!-- footer //-->
		<!-- footer_eof //-->
	</body>
</html>
<?php
	require (DIR_WS_INCLUDES . 'application_bottom.php');
?>