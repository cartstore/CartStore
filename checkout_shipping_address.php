<?php

/*

  $Id: checkout_shipping_address.php,v 1.15 2003/06/09 23:03:53 hpdl Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require('includes/application_top.php');



// if the customer is not logged on, redirect them to the login page

  if (!tep_session_is_registered('customer_id')) {

    $navigation->set_snapshot();

    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

  }



// if there is nothing in the customers cart, redirect them to the shopping cart page

  if ($cart->count_contents() < 1) {

    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));

  }



  // needs to be included earlier to set the success message in the messageStack

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING_ADDRESS);



  require(DIR_WS_CLASSES . 'order.php');

  $order = new order;



// if the order contains only virtual products, forward the customer to the billing page as

// a shipping address is not needed

  if ($order->content_type == 'virtual') {

    if (!tep_session_is_registered('shipping')) tep_session_register('shipping');

    $shipping = false;

    if (!tep_session_is_registered('sendto')) tep_session_register('sendto');

    $sendto = false;

    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

  }



  $error = false;

  $process = false;

  if (isset($_POST['action']) && ($_POST['action'] == 'submit')) {

// process a new shipping address

    if (tep_not_null($_POST['firstname']) && tep_not_null($_POST['lastname']) && tep_not_null($_POST['street_address'])) {

      $process = true;



      if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);

      if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);

      $firstname = tep_db_prepare_input($_POST['firstname']);

      $lastname = tep_db_prepare_input($_POST['lastname']);

      $street_address = tep_db_prepare_input($_POST['street_address']);

       $street_address_2 = tep_db_prepare_input($_POST['street_address_2']);

      if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);

      $postcode = tep_db_prepare_input($_POST['postcode']);

      $city = tep_db_prepare_input($_POST['city']);

      $country = tep_db_prepare_input($_POST['country']);

      if (ACCOUNT_STATE == 'true') {

        if (isset($_POST['zone_id'])) {

          $zone_id = tep_db_prepare_input($_POST['zone_id']);

        } else {

          $zone_id = false;

        }

        $state = tep_db_prepare_input($_POST['state']);

      }



      if (ACCOUNT_GENDER == 'true') {

        if ( ($gender != 'm') && ($gender != 'f') ) {

          $error = true;



          $messageStack->add('checkout_address', ENTRY_GENDER_ERROR);

        }

      }



      if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {

        $error = true;



        $messageStack->add('checkout_address', ENTRY_FIRST_NAME_ERROR);

      }



      if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {

        $error = true;



        $messageStack->add('checkout_address', ENTRY_LAST_NAME_ERROR);

      }



      if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {

        $error = true;



        $messageStack->add('checkout_address', ENTRY_STREET_ADDRESS_ERROR);

      }



      if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {

        $error = true;



        $messageStack->add('checkout_address', ENTRY_POST_CODE_ERROR);

      }



      if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {

        $error = true;



        $messageStack->add('checkout_address', ENTRY_CITY_ERROR);

      }



      if (ACCOUNT_STATE == 'true') {

        $zone_id = 0;

        $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");

        $check = tep_db_fetch_array($check_query);

        $entry_state_has_zones = ($check['total'] > 0);

        if ($entry_state_has_zones == true) {

          $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($state) . "%' or zone_code like '%" . tep_db_input($state) . "%')");

          if (tep_db_num_rows($zone_query) == 1) {

            $zone = tep_db_fetch_array($zone_query);

            $zone_id = $zone['zone_id'];

          } else {

            $error = true;



            $messageStack->add('checkout_address', ENTRY_STATE_ERROR_SELECT);

          }

        } else {

          if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {

            $error = true;



            $messageStack->add('checkout_address', ENTRY_STATE_ERROR);

          }

        }

      }



      if ( (is_numeric($country) == false) || ($country < 1) ) {

        $error = true;



        $messageStack->add('checkout_address', ENTRY_COUNTRY_ERROR);

      }



      if ($error == false) {

        $sql_data_array = array('customers_id' => $customer_id,

                                'entry_firstname' => $firstname,

                                'entry_lastname' => $lastname,

                                'entry_street_address' => $street_address,

                                  'entry_street_address_2' => $street_address_2,

                                'entry_postcode' => $postcode,

                                'entry_city' => $city,

                                'entry_country_id' => $country);



        if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;

        if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;

        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;

        if (ACCOUNT_STATE == 'true') {

          if ($zone_id > 0) {

            $sql_data_array['entry_zone_id'] = $zone_id;

            $sql_data_array['entry_state'] = '';

          } else {

            $sql_data_array['entry_zone_id'] = '0';

            $sql_data_array['entry_state'] = $state;

          }

        }



        if (!tep_session_is_registered('sendto')) tep_session_register('sendto');

// PWA BOF

        if ($customer_id==0) {

          $sendto = 1;

          $pwa_array_shipping = $sql_data_array;

          tep_session_register('pwa_array_shipping');

          if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
  //BOF WA State Tax Modification
        if (tep_session_is_registered('wa_dest_tax_rate')) tep_session_unregister('wa_dest_tax_rate');
  //EOF WA State Tax Modification

          tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

        }

// PWA EOF

        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);



        $sendto = tep_db_insert_id();



        if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
  //BOF WA State Tax Modification
        if (tep_session_is_registered('wa_dest_tax_rate')) tep_session_unregister('wa_dest_tax_rate');
  //EOF WA State Tax Modification



        tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

      }

// process the selected shipping destination

    } elseif (isset($_POST['address'])) {

      $reset_shipping = false;

      if (tep_session_is_registered('sendto')) {

        if ($sendto != $_POST['address']) {

          if (tep_session_is_registered('shipping')) {

            $reset_shipping = true;

          }

        }

      } else {

        tep_session_register('sendto');

      }



      $sendto = $_POST['address'];



      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");

      $check_address = tep_db_fetch_array($check_address_query);



      if ($check_address['total'] == '1') {

        if ($reset_shipping == true) tep_session_unregister('shipping');
  //BOF WA State Tax Modification
        if (tep_session_is_registered('wa_dest_tax_rate')) tep_session_unregister('wa_dest_tax_rate');
  //EOF WA State Tax Modification

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

      } else {

        tep_session_unregister('sendto');

      }

    } else {

      if (!tep_session_is_registered('sendto')) tep_session_register('sendto');

      $sendto = $customer_default_address_id;
  //BOF WA State Tax Modification
        if (tep_session_is_registered('wa_dest_tax_rate')) tep_session_unregister('wa_dest_tax_rate');
  //EOF WA State Tax Modification



      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

    }

  }



// if no shipping destination address was selected, use their own address as default

  if (!tep_session_is_registered('sendto')) {

    $sendto = $customer_default_address_id;

  }

// PWA BOF

  if (tep_session_is_registered('pwa_array_shipping') && is_array($pwa_array_shipping) && count($pwa_array_shipping)) {

    if (isset($pwa_array_shipping['entry_gender'])) $gender = $pwa_array_shipping['entry_gender'];

    $firstname = $pwa_array_shipping['entry_firstname'];

    $lastname = $pwa_array_shipping['entry_lastname'];

    if (isset($pwa_array_shipping['entry_company'])) $company = $pwa_array_shipping['entry_company'];

    $street_address = $pwa_array_shipping['entry_street_address'];

    if (isset($pwa_array_shipping['entry_suburb'])) $suburb = $pwa_array_shipping['entry_suburb'];

    $postcode = $pwa_array_shipping['entry_postcode'];

    $city = $pwa_array_shipping['entry_city'];

    if (isset($pwa_array_shipping['entry_state'])) $state = $pwa_array_shipping['entry_state'];

    $country = $pwa_array_shipping['entry_country_id'];

  }

// PWA EOF

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'));



  $addresses_count = tep_count_customer_address_book_entries();

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

<script language="javascript"><!--

var selected;



function selectRowEffect(object, buttonSelect) {

  if (!selected) {

    if (document.getElementById) {

      selected = document.getElementById('defaultSelected');

    } else {

      selected = document.all['defaultSelected'];

    }

  }



  if (selected) selected.className = 'moduleRow';

  object.className = 'moduleRowSelected';

  selected = object;



// one button is not an array

  if (document.checkout_address.address[0]) {

    document.checkout_address.address[buttonSelect].checked=true;

  } else {

    document.checkout_address.address.checked=true;

  }

}



function rowOverEffect(object) {

  if (object.className == 'moduleRow') object.className = 'moduleRowOver';

}



function rowOutEffect(object) {

  if (object.className == 'moduleRowOver') object.className = 'moduleRow';

}



function check_form_optional(form_name) {

  var form = form_name;



  var firstname = form.elements['firstname'].value;

  var lastname = form.elements['lastname'].value;

  var street_address = form.elements['street_address'].value;



  if (firstname == '' && lastname == '' && street_address == '') {

    return true;

  } else {

    return check_form(form_name);

  }

}

//--></script>

<?php require(DIR_WS_INCLUDES . 'form_check.js.php'); ?>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%" cellspacing="3" cellpadding="3">

  <tr>

    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

    </table></td>

<!-- body_text //-->

    <td width="100%" valign="top"><?php echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'), 'post', 'onSubmit="return check_form_optional(checkout_address);"'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

            <td align="right">&nbsp;</td>

          </tr>

        </table>
        
                     
<div id="module-product">
  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
	<li><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="ui-state-default  ui-tabs-selected ui-state-active ui-corner-all"><span>1. ' . CHECKOUT_BAR_DELIVERY . '</span></a></li>';?> 
    <li><?php echo '<span>2.  ' . CHECKOUT_BAR_PAYMENT . '</span>';?><br>

	<span>3. <?php
                                                                 echo CHECKOUT_BAR_CONFIRMATION;
?></span>
	





    <li>4. <?php  echo CHECKOUT_BAR_FINISHED; ?></span></li>
  </ul>
</div>
        
        
        </td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

  if ($messageStack->size('checkout_address') > 0) {

?>

      <tr>

        <td><?php echo $messageStack->output('checkout_address'); ?></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

  }



  if ($process == false) {

?>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

            <td class="main"><b><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></b></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECTED_SHIPPING_DESTINATION; ?></td>

                <td align="right" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2">

                  <tr>

                    <td class="main" align="center" valign="top"><?php echo '<b>' . TITLE_SHIPPING_ADDRESS . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>

                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                    <td class="main" valign="top"><?php echo tep_address_label($customer_id, $sendto, true, ' ', '<br>'); ?></td>

                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                  </tr>

                </table></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

    if ($addresses_count > 1) {

?>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

            <td class="main"><b><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES; ?></b></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_OTHER_SHIPPING_DESTINATION; ?></td>

                <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

              </tr>

<?php

      $radio_buttons = 0;



      $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address,entry_street_address_2 as street_address_2, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "'");

      while ($addresses = tep_db_fetch_array($addresses_query)) {

        $format_id = tep_get_address_format_id($addresses['country_id']);

?>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php

       if ($addresses['address_book_id'] == $sendto) {

          echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";

        } else {

          echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";

        }

?>

                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                    <td class="main" colspan="2"><b><?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?></b></td>

                    <td class="main" align="right"><?php echo tep_draw_radio_field('address', $addresses['address_book_id'], ($addresses['address_book_id'] == $sendto)); ?></td>

                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                  </tr>

                  <tr>

                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                    <td colspan="3"><table border="0" cellspacing="0" cellpadding="2">

                      <tr>

                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                        <td class="main"><?php echo tep_address_format($format_id, $addresses, true, ' ', ', '); ?></td>

                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                      </tr>

                    </table></td>

                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                  </tr>

                </table></td>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

              </tr>

<?php

        $radio_buttons++;

      }

?>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

    }

  }



  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {

?>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

            <td class="main"><b><?php echo TABLE_HEADING_NEW_SHIPPING_ADDRESS; ?></b></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td class="main" width="100%" valign="top"><?php echo TEXT_CREATE_NEW_SHIPPING_ADDRESS; ?></td>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

                  <tr>

                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                    <td><?php require(DIR_WS_MODULES . 'checkout_new_address.php'); ?></td>

                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                  </tr>

                </table></td>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

<?php

  }

?>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td class="main"><?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>

                <td class="main" align="right"><?php echo tep_draw_hidden_field('action', 'submit') . tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>

                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

<?php

  if ($process == true) {

?>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><?php echo '<a class="button" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . IMAGE_BUTTON_BACK . '</a>'; ?></td>

      </tr>

<?php

  }

?>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr>

                <td width="50%" align="right"></td>

                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

              </tr>

            </table></td>

            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr>

                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>

              </tr>

            </table></td>

          </tr>

      

        </table>
        
                         <hr>
<div id="module-product">
  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
	<li><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="ui-state-default  ui-tabs-selected ui-state-active ui-corner-all"><span>1. ' . CHECKOUT_BAR_DELIVERY . '</span></a></li>';?> 
    <li><?php echo '<span>2.  ' . CHECKOUT_BAR_PAYMENT . '</span>';?><br>

	<span>3. <?php
                                                                 echo CHECKOUT_BAR_CONFIRMATION;
?></span>
	





    <li>4. <?php  echo CHECKOUT_BAR_FINISHED; ?></span></li>
  </ul>
</div>
        
        
        </td>

      </tr>

    </table></form></td>

<!-- body_text_eof //-->

    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

<!-- right_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>

<!-- right_navigation_eof //-->

    </table></td>

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

