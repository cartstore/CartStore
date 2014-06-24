<?php

/*

  $Id: checkout_payment_address.php,v 1.14 2003/06/09 23:03:53 hpdl Exp $



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

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT_ADDRESS);



  $error = false;

  $process = false;

  if (isset($_POST['action']) && ($_POST['action'] == 'submit')) {

// process a new billing address

    if (tep_not_null($_POST['firstname']) && tep_not_null($_POST['lastname']) && tep_not_null($_POST['street_address'])) {

      $process = true;



      if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);

      if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);

      $firstname = tep_db_prepare_input($_POST['firstname']);

      $lastname = tep_db_prepare_input($_POST['lastname']);

      $street_address = tep_db_prepare_input($_POST['street_address']);

        // Second Address Field mod:

      $street_address_2 = tep_db_prepare_input($_POST['street_address_2']);

      // Second Address Field mod:

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

                                 // Second Address Field mod:

                                'entry_street_address_2' => $street_address_2,

                                // :Second Address Field mod

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



        if (!tep_session_is_registered('billto')) tep_session_register('billto');



        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);



        $billto = tep_db_insert_id();



        if (tep_session_is_registered('payment')) tep_session_unregister('payment');



        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

      }

// process the selected billing destination

    } elseif (isset($_POST['address'])) {

      $reset_payment = false;

      if (tep_session_is_registered('billto')) {

        if ($billto != $_POST['address']) {

          if (tep_session_is_registered('payment')) {

            $reset_payment = true;

          }

        }

      } else {

        tep_session_register('billto');

      }



      $billto = $_POST['address'];



      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "' and address_book_id = '" . $billto . "'");

      $check_address = tep_db_fetch_array($check_address_query);



      if ($check_address['total'] == '1') {

        if ($reset_payment == true) tep_session_unregister('payment');

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

      } else {

        tep_session_unregister('billto');

      }

// no addresses to select from - customer decided to keep the current assigned address

    } else {

      if (!tep_session_is_registered('billto')) tep_session_register('billto');

      $billto = $customer_default_address_id;



      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

    }

  }



// if no billing destination address was selected, use their own address as default

  if (!tep_session_is_registered('billto')) {

    $billto = $customer_default_address_id;

  }



  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'));



  $addresses_count = tep_count_customer_address_book_entries();


require (DIR_WS_INCLUDES . 'form_check.js.php');
require (DIR_WS_INCLUDES . 'header.php');
require (DIR_WS_INCLUDES . 'column_left.php');
 ?>



  


<!-- body_text //-->

   
<table>

          <tr>

            <td>
            	
            <?php echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'), 'post', 'onSubmit="return check_form_optional(checkout_address);"'); ?>
            <div class="page-header"><h1><?php echo HEADING_TITLE; ?></h1></div>	


  <ul class="pagination">
                  <li><span>
                    <?php
					echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="ui-state-default ui-corner-all"><span>1. ' . CHECKOUT_BAR_DELIVERY . '</span></a></span></li>';
				?>
                  <li class="active"><a href="javascript:;" class=""><span>2.
                    <?php
					echo CHECKOUT_BAR_PAYMENT;
				?>
                    </span></a></li>
                  <li><span>3.
                    <?php
					echo CHECKOUT_BAR_CONFIRMATION;
				?></span></li>
              
                 <li><span>   4.
                    <?php
					echo CHECKOUT_BAR_FINISHED;
				?>
                    </span></li>
                </ul>
           


        
    





<?php

  if ($messageStack->size('checkout_address') > 0) {

?>

   
<?php echo $messageStack -> output('checkout_address'); ?>


   




<?php

}

if ($process == false) {
?>

<h3><?php echo TABLE_HEADING_PAYMENT_ADDRESS; ?></h3>
<p><?php echo TEXT_SELECTED_PAYMENT_DESTINATION; ?> </p>

               
<?php echo '<b>' . TITLE_PAYMENT_ADDRESS . '</b>';?>

<address><?php echo tep_address_label($customer_id, $billto, true, ' ', '<br>'); ?></address>





<?php

    if ($addresses_count > 1) {

?>

     
<h3><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES; ?></h3>

<p><?php echo TEXT_SELECT_OTHER_PAYMENT_DESTINATION; ?></p>


<?php echo '<b>' . TITLE_PLEASE_SELECT . '</b>'; ?>




<?php

    //  $radio_buttons = 0;



      $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_street_address_2 as street_address_2, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "'");

      while ($addresses = tep_db_fetch_array($addresses_query)) {

        $format_id = tep_get_address_format_id($addresses['country_id']);

?>



<?php

if ($addresses['address_book_id'] == $billto) {

//	echo '' . $radio_buttons . '</div>';

} else {

//	echo '<div class="radio">' . $radio_buttons . '</div>';

}
?>



         
<b><?php echo $addresses['firstname'] . ' ' . $addresses['lastname']; ?></b>


    
<?php echo '<div class="radio">'. tep_draw_radio_field('address', $addresses['address_book_id'], ($addresses['address_book_id'] == $billto)) . ''; ?>



<label><?php echo tep_address_format($format_id, $addresses, true, ' ', ', '); ?></label></div>




<?php

$radio_buttons++;

}
?>

         


<?php

}

}

if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
?>

 
<h3><?php echo TABLE_HEADING_NEW_PAYMENT_ADDRESS; ?></h3> 

          

<p> <?php echo TEXT_CREATE_NEW_PAYMENT_ADDRESS; ?> </p>
          
   
<?php
	require (DIR_WS_MODULES . 'checkout_new_address.php');
 ?>

        


<?php

}
?>

  




<?php echo '<p>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . ' ' . TEXT_CONTINUE_CHECKOUT_PROCEDURE .'</p>'; ?>



<?php echo tep_draw_hidden_field('action', 'submit') . tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>

 


<?php

  if ($process == true) {

?>

  

<?php echo '<a class="btn button-defualt" href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">' . IMAGE_BUTTON_BACK . '</a>'; ?>


<?php

}
?>



<div class="clear"></div>


          <ul class="pagination">
                  <li><span>
                    <?php
					echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="ui-state-default ui-corner-all"><span>1. ' . CHECKOUT_BAR_DELIVERY . '</span></a></span></li>';
				?>
                  <li class="active"><a href="javascript:;" class=""><span>2.
                    <?php
					echo CHECKOUT_BAR_PAYMENT;
				?>
                    </span></a></li>
                  <li><span>3.
                    <?php
					echo CHECKOUT_BAR_CONFIRMATION;
				?></span></li>
              
                 <li><span>   4.
                    <?php
					echo CHECKOUT_BAR_FINISHED;
				?>
                    </span></li>
                </ul>
        
        
     </form>   </td>

      </tr>

    </table> 

<!-- body_text_eof //-->

 
 
<?php
	require (DIR_WS_INCLUDES . 'column_right.php');
	require (DIR_WS_INCLUDES . 'footer.php');
	require (DIR_WS_INCLUDES . 'application_bottom.php');
 ?>