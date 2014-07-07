<?php  
	$order_id = $insert_id;

	require_once (DIR_WS_CLASSES . 'class.phpmailer.php');
	require (DIR_WS_CLASSES . 'fpdf.php');
	class PDF extends FPDF {
		//Page header
		function Header() {
			$this -> SetTitle("Packing Slip Order #" . $order_id);
			//Logos
	//	$this -> Image('templates/includes/sts_templates/default/images/logo.jpg', 5, 8);
//			$this -> Image('images/oscommerce.png', 118, 7);
			$this -> SetFont('Arial', 'B', 18);
			$this -> SetTextColor(0, 0, 0);
			//Conact info
			$this -> Ln(10);
			$this -> MultiCell(0, 8, STORE_NAME_ADDRESS);
			$this -> Line(5, 130, 205, 130);
			$this -> Ln(20);
		}

		//Page footer
		function Footer() {
			//Position at 1.5 cm from bottom
			$this -> SetY(-15);
			//Arial italic 8
			$this -> SetFont('Arial', 'I', 8);
			//Page number
			$this -> Cell(0, 10, 'Page ' . $this -> PageNo() . '/{nb}', 0, 0, 'C');
		}

	}

	$currencies1 = new currencies();

	//$oID = $order_id;
	$orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");

	//include(DIR_WS_CLASSES . 'order.php');
	$order1 = $order = new order($order_id);

			$status = '';
			//$oID=$insert_id;
			$status = $order1->info['order_status'];
			$cust_phone = $order->customer['telephone'];
			$debug = 'no';
			$index2 = 0;
			$oID = $order_id;

			$index = 0;
			$vendor_orders_products_query = tep_db_query("select o.orders_id, o.orders_products_id, o.products_model, o.products_id, o.products_quantity, o.products_name, o.products_price from " . TABLE_ORDERS_PRODUCTS . " o, " . TABLE_PRODUCTS . " p where o.products_id=p.products_id and o.orders_id='" . $order_id . "' order by o.products_name");
			while ($vendor_orders_products = tep_db_fetch_array($vendor_orders_products_query)) {

				$vendor_products[$index2]['vendor_orders_products'][$index] = array('Pqty' => $vendor_orders_products['products_quantity'], 'Pname' => $vendor_orders_products['products_name'], 'PID' => $vendor_orders_products['products_id'],  'Pmodel' => $vendor_orders_products['products_model'], 'Pprice' => $vendor_orders_products['products_price'],  'spacer' => '-');
				$subindex = 0;
				$vendor_attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . $order_id . "' and orders_products_id = '" . (int)$vendor_orders_products['orders_products_id'] . "'");
				if (tep_db_num_rows($vendor_attributes_query)) {
					while ($vendor_attributes = tep_db_fetch_array($vendor_attributes_query)) {
						$vendor_products[$index2]['vendor_orders_products'][$index]['vendor_attributes'][$subindex] = array('option' => $vendor_attributes['products_options'], 'value' => $vendor_attributes['products_options_values'], 'prefix' => $vendor_attributes['price_prefix'], 'price' => $vendor_attributes['options_values_price']);

						$subindex++;
					}
				}
				$index++;
			}
			$index2++;
			// let's build the email
			// Get the delivery address
			$delivery_address_query = tep_db_query("select distinct delivery_company, delivery_name, delivery_street_address, delivery_city, delivery_state, delivery_postcode from " . TABLE_ORDERS . " where orders_id='" . $order_id . "'");
			$vendor_delivery_address_list = tep_db_fetch_array($delivery_address_query);

			$email = '';
			for ($l = 0, $m = sizeof($vendor_products); $l < $m; $l++) {

				$vendor_country = tep_get_country_name($vendor_products[$l]['Vcountry']);
				$order_number = $order_id;
				$vendors_id = $vendor_products[$l]['Vid'];
				$the_email = $vendor_products[$l]['Vemail'];
				//$the_email="art@bluscs.com";
				$the_name = $vendor_products[$l]['Vname'];
				$the_contact = $vendor_products[$l]['Vcontact'];

				$email = 'Dear customer a PDF invoice for order id: ' . $order_id . ' is attached to this email in case you would like to print it.<br>A overview of the order can be found below as well.<br><br>' . EMAIL_SEPARATOR . '<br>' . 'Customers Phone #: ' . '' . $cust_phone . '<br>' . EMAIL_SEPARATOR . '<br> Shipping Method:  Standard Ground (UPS, FedEx, or DHL) <br>' . EMAIL_SEPARATOR . '<br>' . '<br>Deliver to:<br>' . $vendor_delivery_address_list['delivery_company'] . '<br>' . $vendor_delivery_address_list['delivery_name'] . '<br>' . $vendor_delivery_address_list['delivery_street_address'] . '<br>' . $vendor_delivery_address_list['delivery_city'] . ', ' . $vendor_delivery_address_list['delivery_state'] . ' ' . $vendor_delivery_address_list['delivery_postcode'] . '<br><br>';
				$email = $email . '<table width="75%" border=1 cellspacing="0" cellpadding="3">
    <tr><td>Qty:</td><td>Product Name:</td><td>Item Code/Number:</td><td>Product Model:</td><td>Per Unit Price:</td></tr>';
				for ($i = 0, $n = sizeof($vendor_products[$l]['vendor_orders_products']); $i < $n; $i++) {
					// print $vendor_products[$l]['vendor_orders_products'][$i]['Pname'];
					$product_attribs = '';
					if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0)) {

						for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++) {
							$product_attribs .= '&nbsp;&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['option'] . ': ' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['value'] . '<br>';
						}
					}

					$email = $email . '<tr><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pqty'] . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pname'] . '<br>&nbsp;&nbsp;<i>Option<br> ' . $product_attribs . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['PID'] . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'] . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pprice'] . '</td></tr>';
				}
			}
			$email = $email . '</table><br><HR><br>';
			//-------------------------------------------

			//Instanciation of inherited class
			$pdf = new PDF();
			$pdf -> AliasNbPages();
			$pdf -> AddPage();
			$pdf -> SetFont('Arial', 'B', 12);
			$pdf -> Ln(2);
			$pdf -> Cell(100, 5, 'SOLD TO', 0, 0);
			$pdf -> Cell(100, 5, 'SHIP TO', 0, 0);
			$pdf -> Ln(2);
			$pdf -> SetFont('Arial', '', 12);
			$pdf -> Cell(100, 15, $order1 -> customer['name'], 0, 0);
			$pdf -> Cell(100, 15, $order1 -> delivery['name'], 0, 0);
			$pdf -> Ln(5);
			$pdf -> Cell(100, 15, $order1 -> customer['street_address'], 0, 0);
			$pdf -> Cell(100, 15, $order1 -> delivery['street_address'], 0, 0);
			$pdf -> Ln(5);
			$pdf -> Cell(100, 15, $order1 -> customer['city'] . ", " . $order1 -> customer['state'] . " " . $order1 -> customer['postcode'], 0, 0);
			$pdf -> Cell(100, 15, $order1 -> delivery['city'] . ", " . $order1 -> delivery['state'] . " " . $order1 -> delivery['postcode'], 0, 0);
			$pdf -> Ln(5);
			$pdf -> Cell(100, 15, $order1 -> customer['country'], 0, 0);
			$pdf -> Cell(100, 15, $order1 -> delivery['country'], 0, 0);
			$pdf -> Ln(10);
			$pdf -> Cell(100, 15, $order1 -> customer['telephone'], 0, 0);
			$pdf -> Ln(5);
			$pdf -> SetFont('Arial', 'U', 12);
			$pdf -> Cell(100, 15, $order1 -> customer['email_address'], 0, 0);
			$pdf -> Ln(10);
			$pdf -> SetFont('Arial', 'B', 12);
			$pdf -> Cell(40, 15, 'Order Number:', 0, 0);
			$pdf -> SetFont('Arial', '', 12);
			$pdf -> Cell(100, 15, $order_id, 0, 0);
			$pdf -> Ln(5);
			$pdf -> SetFont('Arial', 'B', 12);
			$pdf -> Cell(40, 15, 'Payment Method:', 0, 0);
			$pdf -> SetFont('Arial', '', 12);
			$pdf -> Cell(100, 15, 'We Accept VISA, MasterCard, American Express, and Discover Card.', 0, 0);
			$pdf -> Ln(13);

			// products

			$index = 0;
			$order_packslip_query = tep_db_query("select orders_products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from " . TABLE_ORDERS_PRODUCTS . " where orders_id = " . (int)$order_id);
			while ($order_packslip_data = tep_db_fetch_array($order_packslip_query)) {
				//print_r($order_packslip_data);
				$packslip_products[$index] = array('qty' => $order_packslip_data['products_quantity'], 'name' => $order_packslip_data['products_name'], 'model' => $order_packslip_data['products_model'], 'tax' => $order_packslip_data['products_tax'], 'price' => $order_packslip_data['products_price'], 'vendors_id' => $order_packslip_data['vendors_id'], 'final_price' => $order_packslip_data['final_price']);

				$subindex = 0;
				$packslip_attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$order_packslip_data['orders_products_id'] . "'");
				if (tep_db_num_rows($packslip_attributes_query)) {
					while ($packslip_attributes = tep_db_fetch_array($packslip_attributes_query)) {
						$packslip_products[$index]['packslip_attributes'][$subindex] = array('option' => $packslip_attributes['products_options'], 'value' => $packslip_attributes['products_options_values'], 'prefix' => $packslip_attributes['price_prefix'], 'price' => $packslip_attributes['options_values_price']);

						$subindex++;
					}
				}
				$index++;
			}

			$pdf -> SetFillColor(201, 201, 201);
			$pdf -> SetTextColor(255, 255, 255);
			$pdf -> SetFont('Arial', 'B', 10);
			$pdf -> Cell(100, 4.7, 'Products', 0, 0, "L", 1);
			$pdf -> Cell(30, 4.7, 'Model', 0, 0, "L", 1);
			$pdf -> Cell(30, 4.7, 'Price', 0, 0, "R", 1);
			$pdf -> Cell(30, 4.7, 'Total', 0, 0, "R", 1);
			$pdf -> SetFillColor(241);
			$pdf -> SetTextColor(0);
			$pdf -> SetFont('Arial', '', 10);
			$pdf -> Ln(4.7);

			//$package_num = sizeof($order1->products);
			$box_num = $l + 1;
			for ($i = 0, $n = sizeof($packslip_products); $i < $n; $i++) {
				$pdf -> Cell(100, 4.7, html_entity_decode($packslip_products[$i]['qty'] . ' x  ' . $packslip_products[$i]['name']), 0, 0, "L", 1);
				$pdf -> Cell(30, 4.7, $packslip_products[$i]['model'], 0, 0, "L", 1);
				$pdf -> Cell(30, 4.7, $packslip_products[$i]['price'], 0, 0, "R", 1);
				$pdf -> Cell(30, 4.7, round($packslip_products[$i]['final_price']*$packslip_products[$i]['qty'],2), 0, 0, "R", 1);
				$pdf -> Ln(4.7);
				if (isset($packslip_products[$i]['packslip_attributes']) && (sizeof($packslip_products[$i]['packslip_attributes']) > 0)) {
					for ($j = 0, $k = sizeof($packslip_products[$i]['packslip_attributes']); $j < $k; $j++) {
						$pdf -> SetFont('Arial', 'I', 8);
						$pdf -> Cell(190, 4.7, html_entity_decode(' - ' . $packslip_products[$i]['packslip_attributes'][$j]['option'] . ': ' . $packslip_products[$i]['packslip_attributes'][$j]['value']), 0, 0, "L", 1);
						$pdf -> Ln(4.7);
					}
					$pdf -> SetFont('Arial', '', 10);
				}

			}
			$attachFile = "tmp/Order-" . $order_id . "-" . STORE_NAME . "-vendor-" . $order_vendor_data['vendors_id'] . ".pdf";
			$pdf -> Output($attachFile, "F");
			$mail1 = new PHPMailer();
			$mail1 -> From = STORE_OWNER_EMAIL_ADDRESS;
			$mail1 -> FromName = STORE_OWNER;
//			$mail1 -> AddAddress($order_vendor_data['vendors_email'], $order_vendor_data['vendors_name']);
            $mail1 -> AddAddress(STORE_OWNER_EMAIL_ADDRESS);

$mail1 -> AddAddress($order->customer['email_address'], $order->customer['firstname']);



		//	$mail1 -> AddAddress("adoovo@gmail.com");
			$mail1 -> Subject = STORE_NAME . " PDF Invoice for Order #" . $order_id;
			$mail1 -> IsHTML(true);
			$mail1 -> Body = $email;
			$mail1 -> AddAttachment($attachFile);
			$mail1 -> Send();
			unlink($attachFile);
?>