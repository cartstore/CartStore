<?php
/*
  $Id: affiliate_details.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('affiliate_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_DETAILS);

  if (isset($_POST['action'])) {
    $a_gender = tep_db_prepare_input($_POST['a_gender']);
    $a_firstname = tep_db_prepare_input($_POST['a_firstname']);
    $a_lastname = tep_db_prepare_input($_POST['a_lastname']);
    $a_dob = tep_db_prepare_input($_POST['a_dob']);
    $a_email_address = tep_db_prepare_input($_POST['a_email_address']);
    $a_company = tep_db_prepare_input($_POST['a_company']);
    $a_company_taxid = tep_db_prepare_input($_POST['a_company_taxid']);
    $a_payment_check = tep_db_prepare_input($_POST['a_payment_check']);
    $a_payment_paypal = tep_db_prepare_input($_POST['a_payment_paypal']);
    $a_payment_bank_name = tep_db_prepare_input($_POST['a_payment_bank_name']);
    $a_payment_bank_branch_number = tep_db_prepare_input($_POST['a_payment_bank_branch_number']);
    $a_payment_bank_swift_code = tep_db_prepare_input($_POST['a_payment_bank_swift_code']);
    $a_payment_bank_account_name = tep_db_prepare_input($_POST['a_payment_bank_account_name']);
    $a_payment_bank_account_number = tep_db_prepare_input($_POST['a_payment_bank_account_number']);
    $a_street_address = tep_db_prepare_input($_POST['a_street_address']);
    $a_suburb = tep_db_prepare_input($_POST['a_suburb']);
    $a_postcode = tep_db_prepare_input($_POST['a_postcode']);
    $a_city = tep_db_prepare_input($_POST['a_city']);
    $a_country=tep_db_prepare_input($_POST['a_country']);
    $a_zone_id = tep_db_prepare_input($_POST['a_zone_id']);
    $a_state = tep_db_prepare_input($_POST['a_state']);
    $a_telephone = tep_db_prepare_input($_POST['a_telephone']);
    $a_fax = tep_db_prepare_input($_POST['a_fax']);
    $a_homepage = tep_db_prepare_input($_POST['a_homepage']);
    $a_password = tep_db_prepare_input($_POST['a_password']);

    $error = false; // reset error flag

    if (ACCOUNT_GENDER == 'true') {
      if (($a_gender == 'm') || ($a_gender == 'f')) {
        $entry_gender_error = false;
      } else {
        $error = true;
        $entry_gender_error = true;
      }
    }

    if (strlen($a_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;
      $entry_firstname_error = true;
    } else {
      $entry_firstname_error = false;
    }

    if (strlen($a_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;
      $entry_lastname_error = true;
    } else {
      $entry_lastname_error = false;
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(tep_date_raw($a_dob), 4, 2), substr(tep_date_raw($a_dob), 6, 2), substr(tep_date_raw($a_dob), 0, 4))) {
        $entry_date_of_birth_error = false;
      } else {
        $error = true;
        $entry_date_of_birth_error = true;
      }
    }
  
    if (strlen($a_email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;
      $entry_email_address_error = true;
    } else {
      $entry_email_address_error = false;
    }

    if (!tep_validate_email($a_email_address)) {
      $error = true;
      $entry_email_address_check_error = true;
    } else {
      $entry_email_address_check_error = false;
    }

    if (strlen($a_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;
      $entry_street_address_error = true;
    } else {
      $entry_street_address_error = false;
    }
  
    if (strlen($a_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;
      $entry_post_code_error = true;
    } else {
      $entry_post_code_error = false;
    } 

    if (strlen($a_city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;
      $entry_city_error = true;
    } else {
      $entry_city_error = false;
    }

    if (!$a_country) {
      $error = true;
      $entry_country_error = true;
    } else {
      $entry_country_error = false;
    }

    if (ACCOUNT_STATE == 'true') {
      if ($entry_country_error) {
        $entry_state_error = true;
      } else {
        $a_zone_id = 0;
        $entry_state_error = false;
        $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($a_country) . "'");
        $check_value = tep_db_fetch_array($check_query);
        $entry_state_has_zones = ($check_value['total'] > 0);
        if ($entry_state_has_zones) {
          $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($a_country) . "' and zone_name = '" . tep_db_input($a_state) . "'");
          if (tep_db_num_rows($zone_query) == 1) {
            $zone_values = tep_db_fetch_array($zone_query);
            $a_zone_id = $zone_values['zone_id'];
          } else {
            $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($a_country) . "' and zone_code = '" . tep_db_input($a_state) . "'");
            if (tep_db_num_rows($zone_query) == 1) {
              $zone_values = tep_db_fetch_array($zone_query);
              $a_zone_id = $zone_values['zone_id'];
            } else {
              $error = true;
              $entry_state_error = true;
            }
          }
        } else {
          if (!$a_state) {
            $error = true;
            $entry_state_error = true;
          }
        }
      }
    }

    if (strlen($a_telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;
      $entry_telephone_error = true;
    } else {
      $entry_telephone_error = false;
    }

    $passlen = strlen($a_password);
    if ($passlen < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;
      $entry_password_error = true;
    } else {
      $entry_password_error = false;
    }

    if ($a_password != $a_confirmation) {
      $error = true;
      $entry_password_error = true;
    }

    $check_email_query = tep_db_query("select count(*) as total from " . TABLE_AFFILIATE . " where affiliate_email_address = '" .  tep_db_input($a_email_address) . "' and affiliate_id != '" . tep_db_input($affiliate_id) . "'");
    $check_email = tep_db_fetch_array($check_email_query);
    if ($check_email['total'] > 0) {
      $error = true;
      $entry_email_address_exists = true;
    } else {
      $entry_email_address_exists = false;
    }

    // Check Suburb
    $entry_suburb_error = false;

    // Check Fax
    $entry_fax_error = false;

    if (!affiliate_check_url($a_homepage)) {
      $error = true;
      $entry_homepage_error = true;
    } else {
      $entry_homepage_error = false;
    }

    // Check Company 
    $entry_company_error = false;
    $entry_company_taxid_error = false;

    // Check Payment
    $entry_payment_check_error = false;
    $entry_payment_paypal_error = false;
    $entry_payment_bank_name_error = false;
    $entry_payment_bank_branch_number_error = false;
    $entry_payment_bank_swift_code_error = false;
    $entry_payment_bank_account_name_error = false;
    $entry_payment_bank_account_number_error = false;

    if (!$error) {

      $sql_data_array = array('affiliate_firstname' => $a_firstname,
                              'affiliate_lastname' => $a_lastname,
                              'affiliate_email_address' => $a_email_address,
                              'affiliate_payment_check' => $a_payment_check,
                              'affiliate_payment_paypal' => $a_payment_paypal,
                              'affiliate_payment_bank_name' => $a_payment_bank_name,
                              'affiliate_payment_bank_branch_number' => $a_payment_bank_branch_number,
                              'affiliate_payment_bank_swift_code' => $a_payment_bank_swift_code,
                              'affiliate_payment_bank_account_name' => $a_payment_bank_account_name,
                              'affiliate_payment_bank_account_number' => $a_payment_bank_account_number,
                              'affiliate_street_address' => $a_street_address,
                              'affiliate_postcode' => $a_postcode,
                              'affiliate_city' => $a_city,
                              'affiliate_country_id' => $a_country,
                              'affiliate_telephone' => $a_telephone,
                              'affiliate_fax' => $a_fax,
                              'affiliate_homepage' => $a_homepage,
                              'affiliate_password' => tep_encrypt_password($a_password));

      if (ACCOUNT_GENDER == 'true') $sql_data_array['affiliate_gender'] = $a_gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['affiliate_dob'] = tep_date_raw($a_dob);
      if (ACCOUNT_COMPANY == 'true') {
        $sql_data_array['affiliate_company'] = $a_company;
        $sql_data_array['affiliate_company_taxid'] = $a_company_taxid;
      }
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['affiliate_suburb'] = $a_suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($a_zone_id > 0) {
          $sql_data_array['affiliate_zone_id'] = $a_zone_id;
          $sql_data_array['affiliate_state'] = '';
        } else {
          $sql_data_array['affiliate_zone_id'] = '0';
          $sql_data_array['affiliate_state'] = $a_state;
        }
      }

      $sql_data_array['affiliate_date_account_last_modified'] = 'now()';

      tep_db_perform(TABLE_AFFILIATE, $sql_data_array, 'update', "affiliate_id = '" . tep_db_input($affiliate_id) . "'");

      tep_redirect(tep_href_link(FILENAME_AFFILIATE_DETAILS_OK, '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_AFFILIATE_DETAILS, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_AFFILIATE_DETAILS, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=480,height=360,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
<?php
	$affiliate_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate_id . "'");
	$affiliate = tep_db_fetch_array($affiliate_query);
?>
    <td width="100%" valign="top"><?php echo tep_draw_form('affiliate_details', tep_href_link(FILENAME_AFFILIATE_DETAILS, '', 'SSL'), 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td>
<?php
  require(DIR_WS_MODULES . 'affiliate_account_details.php');
?>
        </td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>