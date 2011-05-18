<?php

  require('includes/application_top.php');
  $debug = 'no';
  $debug_preview = 'no';
  $debug_arrive = 'no';
  $debug_sent = 'no';
  $vendors_id = $_GET['vID'];
  $oID = $_GET['oID'];
  $vendor_order_sent = $_GET['vOS'];
  if ($_GET['action'] == 'preview') {
  	$email_text = stripslashes($_POST['email']);
  	}

  if ($debug == 'yes') {
      echo 'The vendor post data: ' . $vendors_id . ' ' . $oID . ' ' . $vendor_order_sent . '<br>';
  } //if ($debug == 'yes')
  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send_vendor_email')) {
      
      $email = stripslashes($_POST['email']);
      $the_email = stripslashes($_POST['the_email']);
      $the_contact = stripslashes($_POST['the_contact']);
      $oID = stripslashes($_POST['order_number']);
      $the_name = stripslashes($_POST['the_name']);
      $vendors_id = $_POST['vID'];
      if ($debug_sent == 'yes') {
          echo 'All the posted data is here: <br>' . (int)$vendors_id . '<br>' . $the_email . '<br>' . $the_contact . '<br>' . $oID . '<br>' . $the_name . '<br>' . $email;
          echo 'All the action: <br>' . $action;
      } //if ($debug_sent == 'yes')
      if ($_GET['action'] == 'send_vendor_email') {
          tep_mail($the_name, $the_email, EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID, $email . '<br>', STORE_NAME, STORE_OWNER_EMAIL_ADDRESS);
          $vendor_order_sent = 'yes';

          tep_db_query("update " . TABLE_ORDERS_SHIPPING . " set vendor_order_sent = '" . $vendor_order_sent . "' where orders_id = '" . (int)$oID . "'  and vendors_id = '" . (int)$vendors_id . "'");
          $messageStack->add('success', 'Email Sent');
          tep_redirect(tep_href_link(FILENAME_VENDORS_EMAIL_SEND, 'action=success' . '&oID=' . $oID . '&contact=' . $the_contact));
      } //if ($action == 'send_vendor_email')
      else {
          $error = true;
          $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
      } //else
  } //if (isset($_GET['action']) && ($_GET['action'] == 'send_vendor_email'))
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php
  echo TITLE;
?></title>
<link href="templates/admin/css/template_css.css" rel="stylesheet"
  type="text/css" />
 
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"
  leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php
  echo BOX_WIDTH;
?>" valign="top">
    <table border="0" width="<?php
  echo BOX_WIDTH;
?>" cellspacing="1"
      cellpadding="1" class="columnLeft">
      <!-- left_navigation //-->
      <?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
      <!-- left_navigation_eof //-->
    </table>
    </td>
    <!-- body_text //-->
    <td width="100%" valign="top">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="pageHeading">
        <h3><?php
  echo HEADING_TITLE;
?></h3>
        </td>
        <td class="pageHeading2" align="right"></td>
        <!--       <td class="pageHeading" align="left"><?php
  echo '<a class="button" href="' . tep_href_link(FILENAME_ORDERS_VENDORS, '&vID=' . $vendors_id) . '">' . IMAGE_BACK . '</a>';
?></td>  -->
      </tr>
      <tr>
        <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?php
  echo tep_draw_separator();
?></td>
          </tr>
          <tr>
            <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <?php
  $index2 = 0;
  
  
  $vendor_data_query = tep_db_query("select v.vendors_id, v.vendors_name, v.vendors_email, v.vendors_contact, v.vendor_add_info, v.vendor_street, v.vendor_city, v.vendor_state, v.vendors_zipcode, v.vendor_country, v.account_number, v.vendors_status_send, os.shipping_module, os.shipping_method, os.shipping_cost, os.shipping_tax, os.vendor_order_sent from " . TABLE_VENDORS . " v,  " . TABLE_ORDERS_SHIPPING . " os where v.vendors_id=os.vendors_id and v.vendors_id='" . $vendors_id . "' and os.orders_id='" . (int)$oID . "'");

  while ($vendor_order = tep_db_fetch_array($vendor_data_query)) {
      $vendor_products[$index2] = array('Vid' => $vendor_order['vendors_id'], 'Vname' => $vendor_order['vendors_name'], 'Vemail' => $vendor_order['vendors_email'], 'Vcontact' => $vendor_order['vendors_contact'], 'Vaccount' => $vendor_order['account_number'], 'Vstreet' => $vendor_order['vendor_street'], 'Vcity' => $vendor_order['vendor_city'], 'Vstate' => $vendor_order['vendor_state'], 'Vzipcode' => $vendor_order['vendors_zipcode'], 'Vcountry' => $vendor_order['vendor_country'], 'Vaccount' => $vendor_order['account_number'], 'Vinstructions' => $vendor_order['vendor_add_info'], 'Vmodule' => $vendor_order['shipping_module'], 'Vmethod' => $vendor_order['shipping_method']);
      if ($debug == 'yes') {
          echo 'The vendor query: ' . $vendor_order['vendors_id'] . '<br>';
      } //if ($debug == 'yes')
      $index = 0;
      $vendor_orders_products_query = tep_db_query("select o.orders_id, o.orders_products_id, o.products_model, o.products_id, o.products_quantity, o.products_name, p.vendors_id,  p.vendors_prod_comments, p.vendors_prod_id, p.vendors_product_price from " . TABLE_ORDERS_PRODUCTS . " o, " . TABLE_PRODUCTS . " p where p.vendors_id='" . (int)$vendors_id . "' and o.products_id=p.products_id and o.orders_id='" . $oID . "' order by o.products_name");
      while ($vendor_orders_products = tep_db_fetch_array($vendor_orders_products_query)) {
          $vendor_products[$index2]['vendor_orders_products'][$index] = array('Pqty' => $vendor_orders_products['products_quantity'], 'Pname' => $vendor_orders_products['products_name'], 'Pmodel' => $vendor_orders_products['products_model'], 'Pprice' => $vendor_orders_products['products_price'], 'Pvendor_name' => $vendor_orders_products['vendors_name'], 'Pcomments' => $vendor_orders_products['vendors_prod_comments'], 'PVprod_id' => $vendor_orders_products['vendors_prod_id'], 'PVprod_price' => $vendor_orders_products['vendors_product_price'], 'spacer' => '-');
          if ($debug == 'yes') {
              echo 'The products query: ' . $vendor_orders_products['products_name'] . '<br>';
          } //if ($debug == 'yes')
          $subindex = 0;
          $vendor_attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$vendor_orders_products['orders_products_id'] . "'");
          if (tep_db_num_rows($vendor_attributes_query)) {
              while ($vendor_attributes = tep_db_fetch_array($vendor_attributes_query)) {
                  $vendor_products[$index2]['vendor_orders_products'][$index]['vendor_attributes'][$subindex] = array('option' => $vendor_attributes['products_options'], 'value' => $vendor_attributes['products_options_values'], 'prefix' => $vendor_attributes['price_prefix'], 'price' => $vendor_attributes['options_values_price']);
                  $subindex++;
              } //while ($vendor_attributes = tep_db_fetch_array($vendor_attributes_query))
          } //if (tep_db_num_rows($vendor_attributes_query))
          $index++;
      } //while ($vendor_orders_products = tep_db_fetch_array($vendor_orders_products_query))
      $index2++;
      
      
      $delivery_address_query = tep_db_query("select distinct delivery_company, delivery_name, delivery_street_address, delivery_city, delivery_state, delivery_postcode from " . TABLE_ORDERS . " where orders_id='" . $oID . "'");
      $vendor_delivery_address_list = tep_db_fetch_array($delivery_address_query);
      if ($debug == 'yes') {
          echo 'The number of vendors: ' . sizeof($vendor_products) . '<br>';
      } //if ($debug == 'yes')
      $email = '';
      for ($l = 0, $m = sizeof($vendor_products); $l < $m; $l++) {
          $vendor_country = tep_get_country_name($vendor_products[$l]['Vcountry']);
          $order_number = $oID;
          $vendors_id = $vendors_id;
          $the_email = $vendor_products[$l]['Vemail'];
          $the_name = $vendor_products[$l]['Vname'];
          $the_contact = $vendor_products[$l]['Vcontact'];
          $email = '<b>To: ' . $the_contact . '  <br>' . $the_name . '<br>' . $the_email . '<br>' . $vendor_products[$l]['Vstreet'] . '<br>' . $vendor_products[$l]['Vcity'] . ', ' . $vendor_products[$l]['Vstate'] . '  ' . $vendor_products[$l]['Vzipcode'] . ' ' . $vendor_country . '<br>' . '<br>' . EMAIL_SEPARATOR . '<br>' . 'Special Comments or Instructions:  ' . $vendor_products[$l]['Vinstructions'] . '<br>' . '<br>' . EMAIL_SEPARATOR . '<br>' . 'From: ' . STORE_OWNER . '<br>' . STORE_NAME_ADDRESS . '<br>' . 'Accnt #: ' . $vendor_products[$l]['Vaccount'] . '<br>' . EMAIL_SEPARATOR . '<br>' . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . '<br>' . EMAIL_SEPARATOR . '<br>' . '<br>' . EMAIL_SEPARATOR . '<br> Shipping Method: ' . $vendor_products[$l]['Vmodule'] . ' -- ' . $vendor_products[$l]['Vmethod'] . '<br>' . EMAIL_SEPARATOR . '<br>' . '<br>Dropship deliver to:<br>' . $vendor_delivery_address_list['delivery_company'] . '<br>' . $vendor_delivery_address_list['delivery_name'] . '<br>' . $vendor_delivery_address_list['delivery_street_address'] . '<br>' . $vendor_delivery_address_list['delivery_city'] . ', ' . $vendor_delivery_address_list['delivery_state'] . ' ' . $vendor_delivery_address_list['delivery_postcode'] . '<br><br>';
          $email = $email . '<table width="100%" border="1" cellspacing="0" cellpadding="3">
    <tr><td>Qty:</td><td>Product Name:</td><td>Item Code/Number:</td><td>Product Model:</td><td>Per Unit Price:</td><td>Item Comments: </td></tr>';
          for ($i = 0, $n = sizeof($vendor_products[$l]['vendor_orders_products']); $i < $n; $i++) {
              $product_attribs = '';
              if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0)) {
                  $product_attribs .= '<i>Options<br>';
                  for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++) {
                      $product_attribs .= '' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['option'] . ': ' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['value'] . '<br>';
                  } //for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++)
              } //if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0))
              $email = $email . '<tr><td> ' . $vendor_products[$l]['vendor_orders_products'][$i]['Pqty'] . '</td><td> ' . $vendor_products[$l]['vendor_orders_products'][$i]['Pname'] . '<br>' . $product_attribs . '</td><td> ' . $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_id'] . '</td><td> ' . $vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'] . '</td><td> ' . $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_price'] . '</td><td> ' . $vendor_products[$l]['vendor_orders_products'][$i]['Pcomments'] . '</td></tr>';
          } //for ($i = 0, $n = sizeof($vendor_products[$l]['vendor_orders_products']); $i < $n; $i++)
      } //for ($l = 0, $m = sizeof($vendor_products); $l < $m; $l++)
      $email = $email . '</table><br><HR><br>';

      if ($debug == 'yes') {
          echo 'The $email(including headers:<br>Vendor Email Addy' . $the_email . '<br>Vendor Name' . $the_name . '<br>Vendor Contact' . $the_contact . '<br>Body--<br>' . $email . '<br>';
      } //if ($debug == 'yes')
  } //while ($vendor_order = tep_db_fetch_array($vendor_data_query))
  if ($_GET['action'] == 'success') {
?>
              <tr>
                <td class="main"><?php
      echo '<b>Congratulations!  The email has been sent to <big>' . $contact . ' </b></big><br>For order number <b>' . $oID . '</b>';
?></td>
                <td class="pageHeading" align="left"><?php
      echo '<a class="button" href="' . tep_href_link(FILENAME_ORDERS) . '">' . IMAGE_BACK . '</a>';
?></td>
                <?php
  } //if ($_GET["s_235"] == "s_236")
  elseif ($_GET['action'] == 'preview') {
?>
                <tr>
                <?php
      echo tep_draw_form('mail', FILENAME_VENDORS_EMAIL_SEND, 'action=send_vendor_email');
?>
                  <td>
                  <table border="0" width="100%" cellpadding="0" cellspacing="2">
                    <tr>
                      <td class="main"><?php
      echo 'The email will look like this: <br>';
?></td>
                      <td align="center"><?php
      echo '<a classs="button" href="' . tep_href_link(FILENAME_VENDORS_EMAIL_SEND, '&vID=' . $_GET['vID'] . '&oID=' . $_GET['oID'] . '&vOS=' . $_GET['vOS']) . '">' .  IMAGE_BACK . '</a><a class="button" href="' . tep_href_link(FILENAME_ORDERS_VENDORS, '&vID=' . $vendors_id) . '">' . IMAGE_CANCEL . '</a></td>';
?></td>
                      <tr>
                        <td colspan="3"><?php
      echo tep_draw_separator();
?></td>
                      </tr>
                      <?php
      if ($debug == 'yes') {
?>
                      <tr>
                        <td colspan="3"><?php
          echo $order_number . $the_email . $the_name . $the_contact;
?></td>
                      </tr>
                      <?php
      } //if ($debug == "s_259")
     // $email_text = stripslashes($_POST['email']);
      echo '<tr><td><br>' . $email_text;
?>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3"><?php
      echo tep_draw_separator();
?></td>
                    </tr>
                    <tr>
                      <td class="main"><br><br> <?php
      echo tep_draw_hidden_field('email', $email_text);
?>
                      
                      </td>
                      <?php
      echo tep_draw_hidden_field('order_number', stripslashes($order_number));
?>
                      </td>
                      <?php
      echo tep_draw_hidden_field('the_email', stripslashes($the_email));
?>
                      </td>
                      <?php
      echo tep_draw_hidden_field('the_name', stripslashes($the_name));
?>
                      </td>
                      <?php
      echo tep_draw_hidden_field('the_contact', stripslashes($the_contact));
?>
                      </td>
                      <?php
      echo tep_draw_hidden_field('vID', stripslashes($_GET['vID']));
?>
                      </td>
                    </tr>
                    <td align="right"><?php
      echo tep_image_submit('button_send_mail.png', IMAGE_SEND_EMAIL);
?></td>
                  </table>
                  </td>
                </tr>
            
            </table>
            </td>
            </form>
            </td>

            <?php
      } else
      {
?>
            <tr>
            <?php
          echo tep_draw_form('mail', FILENAME_VENDORS_EMAIL_SEND, 'action=preview' . '&vID=' . $vendors_id . '&oID=' . $oID . '&vOS=' . $vOS);
?>
              <td>
              <table border="0" width="100%" cellpadding="0" cellspacing="2">
                <tr>
                  <td class="main"><?php
          echo 'The body of the email will look like this, this is what your Vendor will see when they open the email: <br>';
?></td>
                  <td align="center"><?php
          echo '<a class="button" href="' . tep_href_link(FILENAME_ORDERS_VENDORS, '&vID=' . $vendors_id) . '">' . IMAGE_CANCEL . '</a></td>';
?></td>
                  <tr>
                    <td colspan="3"><?php
          echo tep_draw_separator();
?></td>
                  </tr>

                  <?php
          echo '<tr><td><br>' . stripslashes($email);
?>
                  </td>
                </tr>
                <tr>
                  <td class="main"><?php
          echo 'You may edit the email here: <br><br>';
?></td>
                  <tr>
                    <td colspan="3"><?php
          echo tep_draw_separator();
?></td>
                  </tr>
                  <br>
                  <tr>
                    <td class="main"><br><br>
                    <p>***** Please note that the email is formatted with HTML.
                    You can <b>ONLY</b> edit the <b>BODY</b> of the email here.
                    You <b>CANNOT</b> edit the address the email will go to. <br><br>*****
                    You may edit the HTML or just the text within the tags. Be
                    sure to maintain the HTML tags, if you are not sure what to
                    change and what not to, don't mess it up, do a little research
                    and find out the basics of HTML. 
                    
                    </p>
                    <br> <?php
          echo tep_draw_textarea_field_ckeditor('email', 'soft', '120', '25', stripslashes($email));
?>
                    
                    </td>
                  </tr>
                  <?php
          echo tep_draw_hidden_field('vID', $_GET['vID']);
?>
                  </td>
                  <?php
          echo tep_draw_hidden_field('oID', $_GET['oID']);
?>
                  </td>
                  <?php
          echo tep_draw_hidden_field('vOS', $_GET['vOS']);
?>
                  </td>
                  <td align="right"><?php
          echo tep_image_submit('button_preview.png', IMAGE_PREVIEW);
?></td>
              
              </table>
              </td>
            </tr>
        
        </table>
        </td>
        </form>
        </td>
        <?php
      }
?>
    
    </table>
    </td>
  </tr>
</table>
</td>
<!-- body_text_eof //-->
</tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php
      require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>

</body>
</html>
<?php
      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>