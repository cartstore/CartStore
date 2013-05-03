<?php
/*
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

  require('includes/application_top.php');
  
		$submitted=$_GET['submitted'];
if (!$submitted || ($submitted != 1 && $submitted != 2))
{
		
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />
	 	
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3>Export Order</h3></td>
            <td class="menuboxheading" align="center"><?php echo strftime(DATE_FORMAT_LONG); ?></td>
          </tr>

        </table></td>
      </tr>
      <tr>
        <td>
		
	
		<form method="GET" action="<?php echo $PHP_SELF; ?>"><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td>
        <table border="0" cellpadding="0">
          <tr>
            <td><?php echo "Start Order #:"; ?></td>
            <td><input class="inputbox" name="start" size="5" value="<?php echo $start; ?>">
          </tr>
          <tr>
            <td><?php echo "End Order #:" ; ?></td>
            <td><input class="inputbox" name="end" size="5" value="<?php echo $end; ?>">
          </tr>
          <tr>
            <td><?php echo "Order Status:"; ?></td>
            <?php
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
?>
            <td><?php echo tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => 'All Orders')), $orders_statuses), $status); ?> 
          </tr>
          <tr>
            <td><?php echo "Display Type:"; ?></td>
            <td><select class="inputbox" name="submitted">
                <option value="1">Create CSV File</option>

                <option value="2">Print to Screen</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" class="button" value="<?php echo "Generate"; ?>"></td>
          </tr>
        </table>
      </td>
  </tr>
</table></form>

</td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 

}else
{

generatecsv($start, $end, $status, $submitted);
}


// generates csv file from $start order to $end order, inclusive
function generatecsv($start, $end, $status, $submitted)
{

 $order_edit = "&action=edit";
 $order_url = "orders.php?oID=$order_num";
 $space = " ";
 
 $open_table = $submitted == 1 ? '' : '<table class=table_export width=100% border=1 cellspacing=0 cellpadding=0>';
 $close_table = $submitted == 1 ? '' : '</table>';
 $open_header = $submitted == 1 ? '' : '<tr bgcolor=#cccccc>';
 $close_header = $submitted == 1 ? '' : '</tr>';
 $open_row = $submitted == 1 ? '' : '<tr>';
 $close_row = $submitted == 1 ? '' : '</tr>';
 $open_column = $submitted == 1 ? '' : '<td><font face=verdana size=2>';
 $delim = $submitted == 1 ? ',' : '</font></td>';

if($submitted == 1){ //  Heading CSV output file
	$csv_output .= "Num".$delim;
	$csv_output .= "Date".$delim;
	$csv_output .= "Bill Name".$delim;
	$csv_output .= "Last".$delim;
	$csv_output .= "Bill Address1".$delim;
	$csv_output .= "Bill Address2".$delim;
	$csv_output .= "City".$delim;
	$csv_output .= "State".$delim;
	$csv_output .= "Zip".$delim;
	$csv_output .= "Phone".$delim;
	$csv_output .= "Ship Name".$delim;
	$csv_output .= "Ship Address1".$delim;
	$csv_output .= "Ship Address2".$delim;
	$csv_output .= "City".$delim;
	$csv_output .= "State".$delim;
	$csv_output .= "Zip".$delim;
	$csv_output .= "Model".$delim;
	$csv_output .= "Qty".$delim;
	$csv_output .= "Product".$delim;
	$csv_output .= "Comments".$delim;
	$csv_output .= "\n";
}

 $orders = tep_db_query("select customers_id, orders_id, date_purchased, customers_name, cc_owner, customers_company, customers_email_address, billing_street_address, billing_city, billing_state, billing_postcode, billing_country, customers_telephone, delivery_name, delivery_company, delivery_street_address, delivery_city, delivery_state, delivery_postcode, delivery_country, cc_type, cc_number, cc_expires, payment_method, orders_status from " . TABLE_ORDERS . " where 1 " . ($start ? "and orders_id >= $start " : "") . ($end ? "and orders_id <= $end " : "") . ($status ? "and orders_status = $status " : "") . "order by customers_id");

while ($row_orders = mysql_fetch_array($orders)) { //start one loop

$Orders_id = $row_orders["orders_id"];
$orders_status = $row_orders["orders_status"];
$customers_id = $row_orders["customers_id"];
$customers_gender = $row_orders["customers_gender"];
$Date1 = $row_orders["date_purchased"];
//list($Date, $Time) = explode (' ',$Date1);
$Date = date('m.d.Y', strtotime($Date1));
$Time= date('H:i:s', strtotime($Date1));
$Name_On_Card1 = $row_orders["customers_name"];
$Name_On_Card = filter_text($Name_On_Card1);// order changed
list($First_Name,$Last_Name) = explode(', ',$Name_On_Card1); // order changed
$Company = filter_text($row_orders["customers_company"]);
$email = filter_text($row_orders["customers_email_address"]);
$payment = filter_text($row_orders["payment_method"]);
$Billing_Address_1 = filter_text($row_orders["billing_street_address"]);
$Billing_Address_2 = "";
$Billing_City = filter_text($row_orders["billing_city"]);
$Billing_State = filter_text($row_orders["billing_state"]);
$Billing_Zip = filter_text($row_orders["billing_postcode"]);
$Billing_Country = str_replace("(48 Contiguous Sta", "", $row_orders["billing_country"]);
$Billing_Phone = filter_text($row_orders["customers_telephone"]);
$ShipTo_Name1 = $row_orders["delivery_name"];
$ShipTo_Name = filter_text($ShipTo_Name1); // order changed
list($ShipTo_First_Name,$ShipTo_Last_Name) = explode(', ',$ShipTo_Name1); // order changed
$ShipTo_Company = filter_text($row_orders["delivery_company"]);
$ShipTo_Address_1 = filter_text($row_orders["delivery_street_address"]);
$ShipTo_Address_2 = "";
$ShipTo_City = filter_text($row_orders["delivery_city"]);
$ShipTo_State = filter_text($row_orders["delivery_state"]);
$ShipTo_Zip = filter_text($row_orders["delivery_postcode"]);
$ShipTo_Country = str_replace("(48 Contiguous Sta", "", $row_orders["delivery_country"]);
$ShipTo_Phone = "";
$Card_Type = $row_orders["cc_type"];
$Card_Number = $row_orders["cc_number"];
$Exp_Date = $row_orders["cc_expires"];
$Bank_Name = "";
$Gateway  = "";
$AVS_Code = "";
$Transaction_ID = "";
$Order_Special_Notes = "";
// --------------------    QUERIES 1  ------------------------------------//
//Orders_status_history for comments
 $orders_status_history = tep_db_query("select comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = " . $Orders_id);
 //$row_orders_status_history = tep_db_fetch_array($comments);
 while($row_orders_status_history = mysql_fetch_array($orders_status_history)) {
 // end //

$Comments = filter_text($row_orders_status_history["comments"]);

}
// --------------------    QUERIES 2  ------------------------------------//
//Orders_subtotal
$orders_subtotal = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where class = 'ot_subtotal' and orders_id = " . $Orders_id);
//$row_orders_subtotal = tep_db_fetch_array($orders_subtotal);
while($row_orders_subtotal = mysql_fetch_array($orders_subtotal)) {
 // end //
$Order_Subtotal = filter_text($row_orders_subtotal["value"]);
}
// --------------------    QUERIES 3  ------------------------------------//
//Orders_tax
$Order_Tax = '0';
$orders_tax = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where class = 'ot_tax' and orders_id = " . $Orders_id);
//$row_orders_tax = tep_db_fetch_array($orders_tax);
while($row_orders_tax = mysql_fetch_array($orders_tax)) {
 // end //
$Order_Tax = filter_text($row_orders_tax["value"]);
}
// --------------------    QUERIES 4  ------------------------------------//
//Orders_Insurance
$orders_insurance = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where class = 'ot_insurance' and orders_id = " . $Orders_id);
//$row_orders_insurance = tep_db_fetch_array($orders_insurance);
while($row_orders_insurance = mysql_fetch_array($orders_insurance)) {
 // end //
$Order_Insurance = filter_text($row_orders_insurance["value"]);
}
$Tax_Exempt_Message = "";
// --------------------    QUERIES 5a  ------------------------------------//
//Orders_Shipping Versandkosten
$orders_shipping = tep_db_query("select title, value from " . TABLE_ORDERS_TOTAL . " where class = 'ot_shipping' and orders_id = " . $Orders_id);
//$row_orders_shipping = tep_db_fetch_array($orders_shipping);
while($row_orders_shipping = mysql_fetch_array($orders_shipping)) {
 // end //
$Order_Shipping_Total = $row_orders_shipping["value"];
$Shipping_Method = filter_text($row_orders_shipping["title"]); // Shipping method from query 5
}
// --------------------    QUERIES 5b  ------------------------------------//
//Orders_Shipping_Nachnahme
unset($nn_gebuehr);
$orders_shipping_nn = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . "
where class = 'ot_cod_fee' and orders_id = " . $Orders_id);
//$row_orders_shipping_nn = tep_db_fetch_array($orders_shipping_nn);
while($row_orders_shipping_nn = mysql_fetch_array($orders_shipping_nn)) {
 // end //
$nn_gebuehr = $row_orders_shipping_nn["value"];

}
// --------------------    QUERIES 5c  ------------------------------------//
//Orders_Shipping_Minderwert bei Auslandsaufträgen
unset($minderwert);
$orders_shipping_minderwert = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . "
where class = 'ot_loworderfee' and orders_id = " . $Orders_id);
//$row_orders_shipping_minderwert = tep_db_fetch_array($orders_shipping_minderwert);
while($row_orders_shipping_minderwert = mysql_fetch_array($orders_shipping_minderwert)) {
 // end //
$minderwert = $row_orders_shipping_minderwert["value"];

}
// --------------------    QUERIES 5d  ------------------------------------//
//Orders_Coupon Rabatt bei Couponeinsatz
unset($coupon);
$orders_coupon = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . "
where class = 'ot_discount_coupon' and orders_id = " . $Orders_id);
//$row_orders_coupon = tep_db_fetch_array($orders_coupon);
while($row_orders_coupon = mysql_fetch_array($orders_coupon)) {
 // end //
$coupon = $row_orders_coupon["value"];

}

// --------------------    QUERIES 6  ------------------------------------//
//Orders_Residential Del Fee (Giftwrap)
$orders_residential_fee = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . "
where class = 'ot_giftwrap' and orders_id = " . $Orders_id);
//$row_orders_residential_fee = tep_db_fetch_array($orders_residential_fee);
while($row_orders_residential_fee = mysql_fetch_array($orders_residential_fee)) {
 // end //
$Small_Order_Fee = $row_orders_residential_fee["value"];
}
////////////////////////////////////
$Discount_Rate = "";
$Discount_Message  = "";
$CODAmount  = "";
// --------------------    QUERIES 7  ------------------------------------//
//Orders_Total Gesamtbetrag der Bestellung wird noch nicht gebraucht
$orders_total = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . "
where class = 'ot_total' and orders_id = " . $Orders_id);
//$row_orders_total = tep_db_fetch_array($orders_total);
while($row_orders_total = mysql_fetch_array($orders_total)) {
 // end //
$Order_Grand_Total = $row_orders_total["value"];
}
// --------------------    QUERIES 8  ------------------------------------//
//Kundendaten wie Name, Faxnummer und Refferer
$customers = tep_db_query("select customers_gender, customers_firstname, customers_lastname, customers_fax from " . TABLE_CUSTOMERS . "
where customers_id = " . $customers_id);
//$row_customers = tep_db_fetch_array($customers);
while($row_customers = mysql_fetch_array($customers)) {
    // end //
$fax = $row_customers["customers_fax"];
$gender = $row_customers["customers_gender"];
$kvorname = $row_customers["customers_firstname"];
$knachname = $row_customers["customers_lastname"];
}

// --------------------    QUERIES 10  ------------------------------------//
//Products COunt
$orders_count = tep_db_query("select count(products_quantity) as o_count from " . TABLE_ORDERS_PRODUCTS . "
where orders_id = " . $Orders_id);
//$row_orders_total = tep_db_fetch_array($orders_total);
while($row_orders_count = mysql_fetch_array($orders_count)) {
 // end //
$Number_of_Items = $row_orders_count[0]; // used array to show the number of items ordered
}
//
$Shipping_Weight = "";
$Coupon_Code = "";
$Order_security_msg = "";
$Order_Surcharge_Amount = "";
$Order_Surcharge_Something = "";
$Affiliate_code = "";
$Sentiment_message = "";
$Checkout_form_type = "";
$future1  = " ";
$future2 = "";
$future3 = "";
$future4 = "";
$future5 = "";
$future6 = "";
$future7 = "";
$future8 = "";
$future9 = "";
// csv settings
//$CSV_SEPARATOR = ";";
//$CSV_NEWLINE = "\r\n";
//$csv_output .= $Orders_id . $delim ;
//$csv_output .= $Date . $delim ;
//$csv_output .= $Time . $delim ;
//$csv_output .= $customers_id . $delim ;
//$csv_output .= $gender . $delim ;
//$csv_output .= $kvorname . $delim ;
//$csv_output .= $knachname . $delim ;
//$csv_output .= $Company . $delim ;
//$csv_output .= $email . $delim ;
//$csv_output .= $Billing_Address_1 . $delim ;
//$csv_output .= $Billing_Address_2 . $delim ;
//$csv_output .= $Billing_City . $delim ;
//$csv_output .= $Billing_State . $delim ;
//$csv_output .= $Billing_Zip . $delim ;
//$csv_output .= $Billing_Country . $delim ;
//$csv_output .= $Billing_Phone . $delim ;
//$csv_output .= $fax . $delim ;
//$csv_output .= $ShipTo_First_Name . $delim ;
//$csv_output .= $ShipTo_Last_Name . $delim ;
//$csv_output .= $ShipTo_Name . $delim ;
//$csv_output .= $ShipTo_Company . $delim ;
//$csv_output .= $ShipTo_Address_1 . $delim ;
//$csv_output .= $ShipTo_Address_2 . $delim ;
//$csv_output .= $ShipTo_City . $delim ;
//$csv_output .= $ShipTo_State . $delim ;
//$csv_output .= $ShipTo_Zip . $delim ;
//$csv_output .= $ShipTo_Country . $delim ;
//$csv_output .= $ShipTo_Phone . $delim ;
//$csv_output .= $Card_Type . $delim ;
//$csv_output .= $Card_Number . $delim ;
//$csv_output .= $Exp_Date . $delim ;
//$csv_output .= $Bank_Name . $delim ;
//$csv_output .= $Gateway . $delim ;
//$csv_output .= $AVS_Code . $delim ;
//$csv_output .= $Transaction_ID . $delim ;
//$csv_output .= $payment . $delim ;
//$csv_output .= $Order_Special_Notes . $delim ;
//$csv_output .= $Comments . $delim ;
//$csv_output .= $Order_Subtotal . $delim ;
//$csv_output .= $Order_Tax . $delim ;
//$csv_output .= $Order_Insurance . $delim ;
//$csv_output .= $Tax_Exempt_Message . $delim ;
//$csv_output .= $Order_Shipping_Total . $delim ;
//$csv_output .= $nn_gebuehr . $delim ;
//$csv_output .= $minderwert . $delim ;
//$csv_output .= $coupon . $delim ;
//$csv_output .= $Small_Order_Fee . $delim ;
//$csv_output .= $Discount_Rate . $delim ;
//$csv_output .= $Discount_Message . $delim ;
//$csv_output .= $CODAmount . $delim ;
//$csv_output .= $Order_Grand_Total . $delim ;
//$csv_output .= $Number_of_Items . $delim ;
//$csv_output .= $Shipping_Method . $delim ;
//$csv_output .= $Shipping_Weight . $delim ;
//$csv_output .= $Coupon_Code . $delim ;
//$csv_output .= $Order_security_msg . $delim ;
//$csv_output .= $Order_Surcharge_Amount . $delim ;
//$csv_output .= $Order_Surcharge_Something . $delim ;
//$csv_output .= $Affiliate_code . $delim ;
//$csv_output .= $Sentiment_message . $delim ;
//$csv_output .= $Checkout_form_type . $delim ;
//$csv_output .= $productname . $delim ;
// --------------------    QUERIES 9  ------------------------------------//
//Get list of products ordered
	
$orders_products = tep_db_query("select products_model, products_price, products_quantity, products_name from " . TABLE_ORDERS_PRODUCTS . "
where orders_id = " . $Orders_id);
$productname = $row_customers["products_name"];
$order_num = $Orders_id;
// While loop to list the item
$csv_output .= $open_table;

$csv_output .= $open_header;
if($submitted == 2){ // Html output to screen
	$csv_output .= "<td width=20>Num".$delim;
	$csv_output .= "<td width=50>Date".$delim;
	$csv_output .= "<td width=100>Bill Name".$delim;
	$csv_output .= "<td width=100>Last".$delim;
	$csv_output .= "<td width=100>Bill Address1".$delim;
	$csv_output .= "<td width=25>Bill Address2".$delim;
	$csv_output .= "<td width=100>City".$delim;
	$csv_output .= "<td width=100>State".$delim;
	$csv_output .= "<td width=25>Zip".$delim;
	$csv_output .= "<td width=100>Phone".$delim;
	$csv_output .= "<td width=100>Ship Name".$delim;
	$csv_output .= "<td width=100>Ship Address1".$delim;
	$csv_output .= "<td width=25>Ship Address2".$delim;
	$csv_output .= "<td width=100>City".$delim;
	$csv_output .= "<td width=100>State".$delim;
	$csv_output .= "<td width=25>Zip".$delim;
}
$csv_output .= $close_header;
$csv_output .= $submitted == 1 ? $open_column.$order_num.$delim : $open_column."<a href=".$order_url.$order_num.$order_edit.">".$order_num."</a>".$delim;
$csv_output .= $open_column.$Date . $delim ;
$csv_output .= $open_column.$kvorname . $delim ;
$csv_output .= $open_column.$knachname . $delim ;
$csv_output .= $open_column.$Billing_Address_1 . $delim ;
$csv_output .= $open_column.$Billing_Address_2 . $delim ;
$csv_output .= $open_column.$Billing_City . $delim ;
$csv_output .= $open_column.$Billing_State . $delim ;
$csv_output .= $open_column.$Billing_Zip . $delim ;
$csv_output .= $open_column.$Billing_Phone . $delim ;

$csv_output .= $open_column.$ShipTo_First_Name . " " .$ShipTo_Last_Name. $delim ;
$csv_output .= $open_column.$ShipTo_Address_1 . $delim ;
$csv_output .= $open_column.$ShipTo_Address_2 . $delim ;
$csv_output .= $open_column.$ShipTo_City . $delim ;
$csv_output .= $open_column.$ShipTo_State . $delim ;
$csv_output .= $open_column.$ShipTo_Zip . $delim ;


$csv_output .= $open_header;
if($submitted == 2){ // Html output to screen
	$csv_output .= "<td width=100>Model".$delim;
	$csv_output .= "<td width=25>Qty".$delim;
	$csv_output .= "<td width=250>Product".$delim;
	$csv_output .= "<td width=200>Comments".$delim;
}
$csv_output .= $close_header;
while($row_orders_products = mysql_fetch_array($orders_products)) {

//$csv_output .= $open_column.$order_url.$order_num.$order_edit . $delim ;
$csv_output .= $open_column.filter_text($row_orders_products[0]) . $delim ;
$csv_output .= $open_column.$row_orders_products[2] . $delim ;
$csv_output .= $open_column.filter_text($row_orders_products[3]) . $delim ;
$csv_output .= $open_column.$Comments . $delim ;
$csv_output .= $submitted == 1 ? "" : "";

} // end while loop for products
$csv_output .= $close_table;
$csv_output .= $submitted == 1 ? "" : "<br>";


// --------------------------------------------------------------------------//
$csv_output .= "\n";
} // while loop main first


//BOF OUTPUT
if($submitted == 1){
	header("Content-Type: application/force-download\n");
	header("Cache-Control: cache, must-revalidate");
	header("Pragma: public");
	header("Content-Disposition: attachment; filename=orders_" . date("mdY") . ".csv");
	print $csv_output;
}
elseif($submitted == 2){
	$csv_output = str_replace("\n", "<br>", $csv_output);
	echo "<style type=\"text/css\">
<!--
.table_export , .table_export td {
	text-align: center;
	border: 1px solid #cccccc;
		font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
-->
</style><p dir=rtl>".$csv_output."</p>";
}
exit;
//EOF OUTPUT
}//function main

function filter_text($text) {
$filter_array = array(",","\r","\n","\t");
return str_replace($filter_array,"",$text);
} // function for the filter
?>
<!-- footer_eof //-->
</body>
</html>
<font color="#FFCACB"
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>