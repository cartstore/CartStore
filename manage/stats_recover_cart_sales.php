<?php
/*
  $Id: stats_recover_cart_sales.php,v 1.6 2005/08/16 20:56:38 lane Exp $
  Recover Cart Sales Report v2.22

  Recover Cart Sales contribution: JM Ivler 11/20/03
  (c) Ivler / Ideas From the Deep / osCommerce
  
  Released under the GNU General Public License

 Modifed by Aalst (recover_cart_sales.php,v 1.2 .. 1.36)
 aalst@aalst.com

 Modified by Lane Roathe (recover_cart_sales.php,v 1.4d .. v2.22)
 lane@ifd.com	www.osc-modsquad.com / www.ifd.com
*/
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');

  $currencies = new currencies();

  function tep_date_order_stat($raw_date) {
    if ($raw_date == '') return false;
    $year = substr($raw_date, 2, 2);
    $month = (int)substr($raw_date, 4, 2);
    $day = (int)substr($raw_date, 6, 2);
    return date(DATE_FORMAT, mktime(0, 0, 0, $month, $day, $year));
  }

?>




<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<?php
function seadate($day) {
 $ts = date("U");
 $rawtime = strtotime("-".$day." days", $ts);
 $ndate = date("Ymd", $rawtime);
return $ndate;
}
?>


<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>

<?php echo HEADING_TITLE; ?></h1></div>


          

              <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body">
<i class="fa fa-coffee fa-5x pull-left"></i>
Help for this section is not yet available.                                  </div>
                      </div>
                  </div>   
              </div>    
              		 
	                	<?php $tdate = $_POST['tdate'];
   	                 	if ($tdate == '') $tdate = RCS_REPORT_DAYS;
      	              	$ndate = seadate($tdate); ?>
         	       	<form method=post action=<?php echo $PHP_SELF;?> >
 <div class="form-group"><label><?php echo DAYS_FIELD_PREFIX; ?>  <?php echo DAYS_FIELD_POSTFIX; ?> </label>
 <input class="form-control" type=text  value=<?php echo $tdate; ?> name=tdate>


</div>
   	      
 <p><input class="btn btn-default" type=submit value="<?php echo DAYS_FIELD_BUTTON; ?>"> </p>       
	 
			            </form>
         	     
            	
          		<table class="table table-hover table-condensed table-responsive">
            		<tr>
<?php
	// Init vars
  $custknt = 0;
  $total_recovered = 0;
  $custlist = '';

	// Query database for abandoned carts within our timeframe
  $conquery = tep_db_query("select * from ". TABLE_SCART ." where dateadded >= '".$ndate."' order by dateadded DESC" );
  $rc_cnt = tep_db_num_rows($conquery);

	// Loop though each one and process it
  for ($i = 0; $i < $rc_cnt; $i++)
  {
		$inrec = tep_db_fetch_array($conquery);
		$cid = $inrec['customers_id'];

		// we have to get the customer data in order to better locate matching orders
		$query1 = tep_db_query("select c.customers_firstname, c.customers_lastname from ".TABLE_CUSTOMERS." c where c.customers_id ='".$cid."'");
		$crec = tep_db_fetch_array($query1);

		// Query DB for the FIRST order that matches this customer ID and came after the abandoned cart
		$orders_query_raw = "select o.orders_id, o.customers_id, o.date_purchased, s.orders_status_name, ot.text as order_total, ot.value from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where (o.customers_id = " . (int)$cid . ' OR o.customers_email_address like "' . $crec['customers_email_address'] .'" OR o.customers_name like "' . $crec['customers_firstname'] . ' ' . $crec['customers_lastname'] . '") and o.orders_status > ' . RCS_PENDING_SALE_STATUS . ' and s.orders_status_id = o.orders_status and o.date_purchased >= "' . $inrec['dateadded'] . '" and ot.class = "ot_total"';
		$orders_query = tep_db_query($orders_query_raw);
		$orders = tep_db_fetch_array($orders_query);

		// If we got a match, create the table entry to display the information
		if( $orders )
		{
			$custknt++;
			$total_recovered += $orders['value'];
			$custknt % 2 ? $class = RCS_REPORT_EVEN_STYLE : $class = RCS_REPORT_ODD_STYLE;
			$custlist .= "<tr class=".$class.">".
						"<td class=datatablecontent align=right>".$inrec['scartid']."</td>".
						"<td>&nbsp;</td>".
						"<td class=datatablecontent align=center>".tep_date_order_stat($inrec['dateadded'])."</td>".
						"<td>&nbsp;</td>".
						"<td class=datatablecontent><a href='" . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $crec['customers_lastname'], 'NONSSL') . "'>".$crec['customers_firstname']." ".$crec['customers_lastname']."</a></td>".
						"<td class=datatablecontent>".tep_date_short($orders['date_purchased'])."</td>".
						"<td class=datatablecontent align=center>".$orders['orders_status_name']."</td>".
						"<td class=datatablecontent align=right>".strip_tags($orders['order_total'])."</td>".
						"<td>&nbsp;</td>".
						"</tr>";
		}
	}
  $cline =  "<tr><td height=\"15\" COLSPAN=8> </td></tr>".
  				"<tr>".
					"<td align=right COLSPAN=3 class=main><b>". TOTAL_RECORDS ."</b></td>".
					"<td>&nbsp;</td>".
					"<td align=left COLSPAN=5 class=main>". $rc_cnt ."</td>".
				"</tr>".
				"<tr>".
					"<td align=right COLSPAN=3 class=main><b>". TOTAL_SALES ."</b></td>".
					"<td>&nbsp;</td>".
					"<td align=left COLSPAN=5 class=main>". $custknt . TOTAL_SALES_EXPLANATION ." </td>".
				"</tr>".
				"<tr><td height=\"12\" COLSPAN=6> </td></tr>";
   echo $cline;
?>
			 <tr class="dataTableHeadingRow">	<!-- Header -->
			  <td width="7%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_SCART_ID ?></td>
			  <td width="1%" class="dataTableHeadingContent">&nbsp;</td>
			  <td width="10%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_SCART_DATE ?></td>
			  <td width="1%" class="dataTableHeadingContent">&nbsp;</td>
			  <td width="50%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER ?></td>
			  <td width="10%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER_DATE ?></td>
			  <td width="10%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER_STATUS ?></td>
			  <td width="10%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER_AMOUNT ?></td>
			  <td width="1%" class="dataTableHeadingContent">&nbsp;</td>
      	</tr>
<?php
   echo $custlist;	// BODY: <tr> sections with recovered cart data
?>
		<tr>
				<td colspan=9 valign="bottom"><hr width="100%" size="1" color="#800000" noshade></td>
			 </tr>
		<tr class="main">
		  <td align="right" valign="center" colspan=4 class="main"><b><?php echo TOTAL_RECOVERED ?>&nbsp;</b></td>
		  <td align=left colspan=3 class="main"><b><?php echo $rc_cnt ? tep_round(($custknt / $rc_cnt) * 100, 2) : 0 ?>%</b></td>
		  <td class="main" align="right"><b><?php echo $currencies->format(tep_round($total_recovered, 2)) ?></b></td>
		  <td class="main">&nbsp;</td>
		</tr></table>
Done!
    
 

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
