<?php
  require('includes/application_top.php');

if (isset($_POST['check-email'])){
          $check_email_query = tep_db_query("select customers_id as id, customers_paypal_ec as ec from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($_POST['email_address']) . "'");
          if (tep_db_num_rows($check_email_query) > 0) {
              $check_email = tep_db_fetch_array($check_email_query);
              if ($check_email['ec'] == '1') {
                  tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_email['id'] . "'");
                  tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$check_email['id'] . "'");
                  tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$check_email['id'] . "'");
                  tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$check_email['id'] . "'");
                  tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$check_email['id'] . "'");
                  tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$check_email['id'] . "'");
				  print "true";
				  exit();
              } else {
                  print json_encode($_POST['email_address'] . " " . ENTRY_EMAIL_ADDRESS_CREATE_EXISTS);
				  exit();
              }
          }
	print "true";
	exit();
}


  if (isset($_GET['guest']) && $cart->count_contents() < 1)
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);
  $process = false;
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
      $process = true;
      if (ACCOUNT_GENDER == 'true') {
          if (isset($_POST['gender'])) {
              $gender = tep_db_prepare_input($_POST['gender']);
          } else {
              $gender = false;
          }
      }
      $firstname = tep_db_prepare_input($_POST['firstname']);
      $lastname = tep_db_prepare_input($_POST['lastname']);
      if (ACCOUNT_DOB == 'true')
          $dob = tep_db_prepare_input($_POST['dob']);
      $email_address = tep_db_prepare_input($_POST['email_address']);
      if (ACCOUNT_COMPANY == 'true') {
          $company = tep_db_prepare_input($_POST['company']);
          $company_tax_id = tep_db_prepare_input($_POST['company_tax_id']);
      }
      $street_address = tep_db_prepare_input($_POST['street_address']);
       $street_address_2 = tep_db_prepare_input($_POST['street_address_2']);
      if (ACCOUNT_SUBURB == 'true')
          $suburb = tep_db_prepare_input($_POST['suburb']);
      $postcode = tep_db_prepare_input($_POST['postcode']);
      $city = tep_db_prepare_input($_POST['city']);
      if (ACCOUNT_STATE == 'true') {
          $state = tep_db_prepare_input($_POST['state']);
          if (isset($_POST['zone_id'])) {
              $zone_id = tep_db_prepare_input($_POST['zone_id']);
          } else {
              $zone_id = false;
          }
      }
      $country = tep_db_prepare_input($_POST['country']);
      $telephone = tep_db_prepare_input($_POST['telephone']);
      $fax = tep_db_prepare_input($_POST['fax']);
      if (isset($_POST['newsletter'])) {
          $newsletter = tep_db_prepare_input($_POST['newsletter']);
      } else {
          $newsletter = false;
      }
      $password = tep_db_prepare_input($_POST['password']);
      $confirmation = tep_db_prepare_input($_POST['confirmation']);

      $error = false;
    if (tep_db_prepare_input($HTTP_POST_VARS['TermsAgree']) != 'true' and MATC_AT_REGISTER != 'false') {
        $error = true;
        $messageStack->add('create_account', MATC_ERROR);
    }
      if (ACCOUNT_GENDER == 'true') {
          if (($gender != 'm') && ($gender != 'f')) {
              $error = true;
              $messageStack->add('create_account', ENTRY_GENDER_ERROR);
          }
      }
      if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
          $error = true;
          $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
      }
      if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
          $error = true;
          $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
      }
      if (ACCOUNT_DOB == 'true') {
          if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
              $error = true;
              $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
          }
      }
      if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
      } elseif (tep_validate_email($email_address) == false) {
          $error = true;
          $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
      } else {
          $check_email_query = tep_db_query("select customers_id as id, customers_paypal_ec as ec from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
          if (tep_db_num_rows($check_email_query) > 0) {
              $check_email = tep_db_fetch_array($check_email_query);
              if ($check_email['ec'] == '1') {
                  tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_email['id'] . "'");
                  tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$check_email['id'] . "'");
                  tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$check_email['id'] . "'");
                  tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$check_email['id'] . "'");
                  tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$check_email['id'] . "'");
                  tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$check_email['id'] . "'");
              } else {
                  $error = true;
                  $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
              }
          }
      }
      if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
          $error = true;
          $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
      }
      if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
          $error = true;
          $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
      }
      if (strlen($city) < ENTRY_CITY_MIN_LENGTH && ACCOUNT_CITY == 'true') {
          $error = true;
          $messageStack->add('create_account', ENTRY_CITY_ERROR);
      }
      if (is_numeric($country) == false) {
          $error = true;
          $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
      }
      $zone_id = 0;
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
                  $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
              }
          } else {
              if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
                  $error = true;
                  $messageStack->add('create_account', ENTRY_STATE_ERROR);
              }
          }
      }
      if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH && ACCOUNT_TELEPHONE == 'true') {
          $error = true;
          $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
      }
      if (!isset($_GET['guest']) && !isset($_POST['guest'])) {
          if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
              $error = true;
              $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
          } elseif ($password != $confirmation) {
              $error = true;
              $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
          }
      }
      if ($error == false) {
          $sql_data_array = array('customers_firstname' => $firstname, 'customers_lastname' => $lastname, 'customers_email_address' => $email_address, 'customers_telephone' => $telephone, 'customers_fax' => $fax, 'customers_newsletter' => $newsletter, 'customers_password' => tep_encrypt_password($password), 'fb_user_id' => $fbme['id']);
          if (ACCOUNT_GENDER == 'true')
              $sql_data_array['customers_gender'] = $gender;
          if (ACCOUNT_DOB == 'true')
              $sql_data_array['customers_dob'] = tep_date_raw($dob);
          if (ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id)) {
              $sql_data_array['customers_group_ra'] = '1';
          }
          $check_client_newsletter_true = tep_db_query("select count(*) as total from " . TABLE_NEWSLETTER . " where customers_email_address = '$email_address'");
          $check_client_new = tep_db_fetch_array($check_client_newsletter_true);
          if ($check_client_new['total'] > 0) {
              tep_db_query("delete from " . TABLE_NEWSLETTER . " where customers_email_address = '$email_address'");
          }
          if (isset($_GET['guest']) && PURCHASE_WITHOUT_ACCOUNT == 'yes') {
              $pwa_array_customer = $sql_data_array;
              $customer_id = 0;
              tep_session_register('pwa_array_customer');
          } else {
              tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
              $customer_id = tep_db_insert_id();
          }
          $sql_data_array = array('customers_id' => $customer_id, 'entry_firstname' => $firstname, 'entry_lastname' => $lastname, 'entry_street_address' => $street_address,  'entry_street_address_2' => $street_address_2,'entry_postcode' => $postcode, 'entry_city' => $city, 'entry_country_id' => $country);
          if (ACCOUNT_GENDER == 'true')
              $sql_data_array['entry_gender'] = $gender;
          if (ACCOUNT_COMPANY == 'true') {
              $sql_data_array['entry_company'] = $company;
              $sql_data_array['entry_company_tax_id'] = $company_tax_id;
          }
          if (ACCOUNT_SUBURB == 'true')
              $sql_data_array['entry_suburb'] = $suburb;
          if (ACCOUNT_STATE == 'true') {
              if ($zone_id > 0) {
                  $sql_data_array['entry_zone_id'] = $zone_id;
                  $sql_data_array['entry_state'] = '';
              } else {
                  $sql_data_array['entry_zone_id'] = '0';
                  $sql_data_array['entry_state'] = $state;
              }
          }
          if (isset($_GET['guest']) or isset($_POST['guest'])) {
              $pwa_array_address = $sql_data_array;
              tep_session_register('pwa_array_address');
              $address_id = 0;
          } else {
              tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
              $address_id = tep_db_insert_id();
              tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");
              tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");
          }
          if (SESSION_RECREATE == 'True') {
              tep_session_recreate();
          }
          $customer_first_name = $firstname;
          $customer_default_address_id = $address_id;
          $customer_country_id = $country;
          $customer_zone_id = $zone_id;
          tep_session_register('customer_id');
          tep_session_register('customer_first_name');
          tep_session_register('customer_default_address_id');
          tep_session_register('customer_country_id');
          tep_session_register('customer_zone_id');
          setcookie("first_name", $customer_first_name, time() + 3600, "/", ".chibakita.net");
          if (isset($_GET['guest']) or isset($_POST['guest']))
              tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING));		  
          $cart->restore_contents();
          $wishList->restore_wishlist();
          $name = $firstname . ' ' . $lastname;
          if (ACCOUNT_GENDER == 'true') {
              if ($gender == 'm') {
                  $email_text = sprintf(EMAIL_GREET_MR, $lastname);
              } else {
                  $email_text = sprintf(EMAIL_GREET_MS, $lastname);
              }
          } else {
              $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
          }
          if (NEW_SIGNUP_POINT_AMOUNT > 0) {
              tep_add_welcome_points($customer_id);
              $points_account .= '<a href="' . tep_href_link(FILENAME_MY_POINTS, '', 'SSL') . '"><b><u>' . EMAIL_POINTS_ACCOUNT . '</u></b></a>.';
              $points_faq .= '<a href="' . tep_href_link(FILENAME_MY_POINTS_HELP, '', 'NONSSL') . '"><b><u>' . EMAIL_POINTS_FAQ . '</u></b></a>.';
              $text_points = sprintf(EMAIL_WELCOME_POINTS, $points_account, number_format(NEW_SIGNUP_POINT_AMOUNT, POINTS_DECIMAL_PLACES), $currencies->format(tep_calc_shopping_pvalue(NEW_SIGNUP_POINT_AMOUNT)), $points_faq) . "\n\n";
          }
          $email_text .= EMAIL_WELCOME . EMAIL_TEXT . $text_points . EMAIL_CONTACT . EMAIL_WARNING;
/* CCGV - BEGIN */
      if (NEW_SIGNUP_GIFT_VOUCHER_AMOUNT > 0) {
        $coupon_code = create_coupon_code();
        $insert_query = tep_db_query("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $coupon_code . "', 'G', '" . NEW_SIGNUP_GIFT_VOUCHER_AMOUNT . "', now())");
        $insert_id = tep_db_insert_id();
        $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '0', 'Admin', '" . $email_address . "', now() )");

        $email_text .= sprintf(EMAIL_GV_INCENTIVE_HEADER, $currencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) . "\n\n" .
                       sprintf(EMAIL_GV_REDEEM, $coupon_code) . "\n\n" .
                       EMAIL_GV_LINK . tep_href_link(FILENAME_GV_REDEEM, 'gv_no=' . $coupon_code,'NONSSL', false) .
                       "\n\n";
      }
      if (NEW_SIGNUP_DISCOUNT_COUPON != '') {
    		$coupon_code = NEW_SIGNUP_DISCOUNT_COUPON;
        $coupon_query = tep_db_query("select * from " . TABLE_COUPONS . " where coupon_code = '" . $coupon_code . "'");
        $coupon = tep_db_fetch_array($coupon_query);
    		$coupon_id = $coupon['coupon_id'];		
        $coupon_desc_query = tep_db_query("select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $coupon_id . "' and language_id = '" . (int)$languages_id . "'");
        $coupon_desc = tep_db_fetch_array($coupon_desc_query);
        $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $coupon_id ."', '0', 'Admin', '" . $email_address . "', now() )");
        $email_text .= EMAIL_COUPON_INCENTIVE_HEADER .  "\n" .
                       sprintf("%s", $coupon_desc['coupon_description']) ."\n\n" .
                       sprintf(EMAIL_COUPON_REDEEM, $coupon['coupon_code']) . "\n\n" .
                       "\n\n";
      }
/* CCGV - END */
          if (isset($_POST['newsletter']) && defined("USE_CONSTANT_CONTACT") && USE_CONSTANT_CONTACT == 'true'){
             require_once(DIR_WS_INCLUDES . "ConstantContact/ConstantContact.php");
             $ConstantContact = new ConstantContact('basic',CONSTANT_CONTACT_API_KEY,CONSTANT_CONTACT_USER,CONSTANT_CONTACT_PW);
             $ContactLists = $ConstantContact->getLists();
               foreach ($ContactLists['lists'] as $list){
                  $parts = pathinfo($list->id);
                  if ($parts['filename'] == CONSTANT_CONTACT_LIST_ID)
                     $listID = $list->id;
               }

               $Contact = new Contact(array(
                   "emailAddress" => $email_address,
                   "firstName" => $firstname,
                   "lastName" => $lastname,
                   "lists"=>$listID
                   ));
			 $check = $ConstantContact->searchContactsByEmail($email_address);
			 $pass = false;
			 if (empty($check)){
				$pass = true;
			 } else {
				$details = $ConstantContact->getContactDetails($check[0]);
				if (empty($details->lists) || !in_array($listID,$details->lists))
				  $pass = true;
			 }
            if ($pass = true)
                $NewContact = $ConstantContact->addContact($Contact);
          }


          tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
          if (ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id)) {
              $alert_email_text = "Please note that " . $firstname . " " . $lastname . " of the company: " . $company . " has created an account.";
              tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Company account created', $alert_email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
          }
  //BOF WA State Tax Modification
  if (tep_session_is_registered('wa_dest_tax_rate')) tep_session_unregister('wa_dest_tax_rate');
  //BOF WA State Tax Modification
          tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
      }
  }
  if (tep_session_is_registered('pwa_array_customer') && tep_session_is_registered('pwa_array_address')) {
      $gender = isset($pwa_array_customer['customers_gender']) ? $pwa_array_customer['customers_gender'] : '';
      $company = isset($pwa_array_address['entry_company']) ? $pwa_array_address['entry_company'] : '';
      $firstname = isset($pwa_array_customer['customers_firstname']) ? $pwa_array_customer['customers_firstname'] : '';
      $lastname = isset($pwa_array_customer['customers_lastname']) ? $pwa_array_customer['customers_lastname'] : '';
      $dob = isset($pwa_array_customer['customers_dob']) ? substr($pwa_array_customer['customers_dob'], -2) . '.' . substr($pwa_array_customer['customers_dob'], 4, 2) . '.' . substr($pwa_array_customer['customers_dob'], 0, 4) : '';
      $email_address = isset($pwa_array_customer['customers_email_address']) ? $pwa_array_customer['customers_email_address'] : '';
      $street_address = isset($pwa_array_address['entry_street_address']) ? $pwa_array_address['entry_street_address'] : '';
      $street_address_2 = isset($pwa_array_address['entry_street_address_2']) ? $pwa_array_address['entry_street_address_2'] : '';
      $suburb = isset($pwa_array_address['entry_suburb']) ? $pwa_array_address['entry_suburb'] : '';
      $postcode = isset($pwa_array_address['entry_postcode']) ? $pwa_array_address['entry_postcode'] : '';
      $city = isset($pwa_array_address['entry_city']) ? $pwa_array_address['entry_city'] : '';
      $state = isset($pwa_array_address['entry_state']) ? $pwa_array_address['entry_state'] : '0';
      $country = isset($pwa_array_address['entry_country_id']) ? $pwa_array_address['entry_country_id'] : '';
      $telephone = isset($pwa_array_customer['customers_telephone']) ? $pwa_array_customer['customers_telephone'] : '';
      $fax = isset($pwa_array_customer['customers_fax']) ? $pwa_array_customer['customers_fax'] : '';
  }
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php
  echo HTML_PARAMS;
?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php
  echo CHARSET;
?>">
<title>
<?php
  echo TITLE;
?>
</title>
<base href="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG;
?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php
  require('includes/form_check.js.php');
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->

      <!-- left_navigation //-->
      <?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
      <!-- left_navigation_eof //-->
   
  <!-- body_text //-->
  <!-- PWA BOF -->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
  <td>
  	
  	  <?php
  	echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, (isset($_GET['guest']) ? 'guest=guest' : ''), 'SSL'), 'post', 'onSubmit="return check_form(create_account);"') . tep_draw_hidden_field('action', 'process');
?>
  <h1>
    <?php
  echo HEADING_TITLE;
?>
  </h1>
  <?php
  echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL'));
?>
  <?php
  if ($messageStack->size('create_account') > 0) {
?>
  <?php
      echo $messageStack->output('create_account');
?>
  <?php
  }

?>
<br>
<span style="float:right" class="inputRequirement">  <?php
  echo FORM_REQUIRED_INFORMATION;
?></span>
  <br>
  <br>
  <legend> <b>
  <?php
  echo CATEGORY_PERSONAL;
?>
  </b></legend><br>
  <?php
  if (ACCOUNT_GENDER == 'true') {
?>
  <label>
  <?php
      echo ENTRY_GENDER;
?>
  </label>
  <?php
      echo tep_draw_radio_field('gender', 'm') . '' . MALE . '' . tep_draw_radio_field('gender', 'f') . '' . FEMALE . '' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>' : '');
?>
  <br>
  <?php
  }
?>
  <label>
  <?php
  echo ENTRY_FIRST_NAME;
?>
  </label>
  <?php
  echo tep_draw_input_field('firstname') . '' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>' : '');
?>
  <br>
  <label>
  <?php
  echo ENTRY_LAST_NAME;
?>
  </label>
  <?php
  echo tep_draw_input_field('lastname') . '' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>' : '');
?>
  <br>
  <?php
  if (ACCOUNT_DOB == 'true') {
?>
  <label>
  <?php
      echo ENTRY_DATE_OF_BIRTH;
?>
  </label>
  <?php
      echo tep_draw_input_field('dob') . '' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>' : '');
?>
  <br>
  <?php
  }
?>
  <label>
  <?php
  echo ENTRY_EMAIL_ADDRESS;
?>
  </label>
  <?php
  echo tep_draw_input_field('email_address') . '' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>' : '');
?>
  <br>
  <?php
  if (ACCOUNT_COMPANY == 'true') {
?>
  <br>
  <br>
  <legend> <b>
  <?php
      echo CATEGORY_COMPANY;
?>
  </b></legend><br>
  <label>
  <?php
      echo ENTRY_COMPANY;
?>
  </label>
  <?php
      echo tep_draw_input_field('company') . '' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>' : '');
?>
  <br>
  <label>
  <?php
      echo ENTRY_COMPANY_TAX_ID;
?>
  </label>
  <?php
      echo tep_draw_input_field('company_tax_id') . '' . (tep_not_null(ENTRY_COMPANY_TAX_ID_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TAX_ID_TEXT . '</span>' : '');
?>
  <br>
  <?php
  }
?>
  <br>
  <br>
  <legend> <b>
  <?php
  echo CATEGORY_ADDRESS;
?>
  </b></legend><br>
  <label>
  <?php
  echo ENTRY_STREET_ADDRESS;
?>
  </label>
  <?php
  echo tep_draw_input_field('street_address') . '' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>' : '');
?>
  <br>
  <label>
Street Address Line 2:
  </label>
  <?php
  echo tep_draw_input_field('street_address_2') . '' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT_2) ? '<span class="inputRequirement">*</span>' : '');
?>
  <br>
  <?php
  if (ACCOUNT_SUBURB == 'true') {
?>
  <br>
  <label>
  <?php
      echo ENTRY_SUBURB;
?>
  </label>
  <?php
      echo tep_draw_input_field('suburb') . '' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>' : '');
?>
  <br>
  <?php
  }
?>
  <label><?php  echo ENTRY_CITY; ?></label>
  <?php
  echo tep_draw_input_field('city') . '' . (tep_not_null(ENTRY_CITY_TEXT) && ACCOUNT_CITY == 'true' ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>' : '');
?>
  <br>
  <label>
  <?php
  echo ENTRY_POST_CODE;
?>
  </label>
  <?php
  echo tep_draw_input_field('postcode') . '' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>' : '');
?>
  <br>
  <label>
  <?php
  echo ENTRY_COUNTRY;
?>
  </label>
  <?php
  echo tep_get_country_list('country', 'Please', 'onchange="loadXMLDoc(this.value);" ');
?>
  <br>
  <?php
  if (ACCOUNT_STATE == 'true') {
?>
  <label>
  <?php
      echo ENTRY_STATE;
?>
  </label>
  <?php
      if ($process == true) {
          if ($entry_state_has_zones == true) {
              $zones_array = array();
              $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
              while ($zones_values = tep_db_fetch_array($zones_query)) {
                  $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
              }
              echo '<span id="states">';
              echo tep_draw_pull_down_menu('state', $zones_array, '', ' style="width:175px" ');
              echo '</span>';
          } else {
              echo tep_draw_input_field('state');
          }
      } else {
          echo '<span id="states">';
          echo tep_draw_pull_down_menu('state', $zones_array, '', ' style="width:175px" ');
          echo '</span>';
      }
      if (tep_not_null(ENTRY_STATE_TEXT))
          echo '';
?>
  <?php
  }
?>
  <br>
  <br>
  <legend>
  <b><?php
  echo CATEGORY_CONTACT;
?></b></legend><br>
  <label>
  <?php
  echo ENTRY_TELEPHONE_NUMBER;
?>
  </label>
  <?php
  echo tep_draw_input_field('telephone') . '' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) && ACCOUNT_TELEPHONE == 'true' ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>' : '');
?>
  <br>
  <label>
  <?php
  echo ENTRY_FAX_NUMBER;
?>
  </label>
  <?php
  echo tep_draw_input_field('fax') . '' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>' : '');
?>
  <br>
  <?php
  if ((!isset($_GET['guest']) && !isset($_POST['guest'])) || (defined("USE_CONSTANT_CONTACT") && USE_CONSTANT_CONTACT == 'true')) {
?>
 
 
   <b><?php
      echo CATEGORY_OPTIONS;
?></b>
  <br>
  
<label>  <?php
      echo ENTRY_NEWSLETTER;
?>
  </label>
  
  <?php
      echo tep_draw_checkbox_field('newsletter', '1', (!$process || isset($_POST['newsletter']) ? ' checked="checked"' : '')) . '' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>' : '');
?> 





<br>
  <br>
<?php }
    if (!isset($_GET['guest']) && !isset($_POST['guest'])) {
?>
  <b><?php echo CATEGORY_PASSWORD; ?></b>
  
 <br>
  
  <label>
  <?php
      echo ENTRY_PASSWORD;
?>
  </label>
  <?php
      echo tep_draw_password_field('password',null,'id="password-field"') . '' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>' : '');
?>
  <div class="clear"></div>
  <label>
  <?php
      echo ENTRY_PASSWORD_CONFIRMATION;
?>
  </label>
  <?php
      echo tep_draw_password_field('confirmation') . '' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>' : '');
?>
  <br>
  <?php
      } else
      {
          echo tep_draw_hidden_field('guest', 'guest');
      }

      if(MATC_AT_REGISTER != 'false'){
          require(DIR_WS_MODULES . 'matc.php');
      }
?>
  <?php
      echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE,'id="TheSubmitButton"');
?>
</form>
  </td>
</tr>
</table>
<!-- body_text_eof //-->

    <!-- right_navigation //-->
    <?php
      include(DIR_WS_INCLUDES . 'column_right.php');
?>
    <!-- right_navigation_eof //-->

<!-- body_eof //-->
<!-- footer //-->
<?php
      include(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<?php
      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
