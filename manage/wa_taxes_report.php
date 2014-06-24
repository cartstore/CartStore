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

	<?php
		if ($printable != 'on') {
			require (DIR_WS_INCLUDES . 'header.php');
		};
		?>
		<script language="javascript" src="includes/general.js"></script>
	

	
		



<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a><?php echo HEADING_TITLE;?></h1></div>

      <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body">
<i class="fa fa-university fa-5x pull-left"></i>
Help for this section is not yet available.                                  </div>
                      </div>
                  </div>   
              </div> 


						<table border="0" align="left" width="100%" cellspacing="0" cellpadding="2">
						
						
						
						<?php echo tep_draw_form('date_range', FILENAME_WA_TAXES_REPORT, '', 'get');?>
							<tr>
								<td class="smallText"><div class="form-group">	<?php
								echo '<label>'. ENTRY_STATUS . '</label>' . tep_draw_pull_down_menu('status', $statuses, $status, 'onchange=\'this.form.submit();\'') . '</div>';
								echo '<div class="checkbox">' . ENTRY_PRINTABLE . tep_draw_checkbox_field('printable', $print) . '</div>';

								echo '<b>'.TEXT_DATE . '</b>';
								echo '<div class="form-group"><label>' .ENTRY_YEAR . '</label>' . tep_draw_pull_down_menu('year', $years, $year, 'onchange=\'this.form.submit();\'') . '</div>';
								echo '<div class="form-group"><label>' .ENTRY_MONTH . '</label>' . tep_draw_pull_down_menu('month', $months, $month, 'onchange=\'this.form.submit();\'') . '</div>';

								echo '</td></form>';
								?>
							</tr>
						</table></td>
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
				</table> 
<?php
	require (DIR_WS_INCLUDES . 'application_bottom.php');
?>