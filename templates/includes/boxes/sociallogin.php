<?php
/*
  $Id: sociallogin.php 1739 2012-03-20 00:52:16Z Team LoginRadius $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/
require_once('includes/application_top.php');//phpinfo();
?>

<!-- sociallogin //-->
          <tr>
            <td>
<?php  
  
    $info_box_contents = array();
	 $title_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_TITLE'");
    $title_array = tep_db_fetch_array($title_query);
    $title = $title_array['configuration_value'];
	
    $info_box_contents[] = array('text' => $title);

    new infoBoxHeading($info_box_contents, false, false, '');

    $apikey_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_API_KEY'");
    $apikey_array = tep_db_fetch_array($apikey_query);
    $apikey = trim($apikey_array['configuration_value']);
	
		
	$apisecretkey_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_API_SECRET_KEY'");
    $apisecretkey_array = tep_db_fetch_array($apisecretkey_query);
    $apisecretkey = trim($apisecretkey_array['configuration_value']);
    
	$info_box_contents = array();
	
	$emailrequired_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_EMAIL_REQUIRED'");
    $emailrequired_array = tep_db_fetch_array($emailrequired_query);
    $emailrequired = $emailrequired_array['configuration_value'];
	
	
	    if (tep_session_is_registered('customer_id')) {
		  if(empty($customer_picture)){
		  	$info_box_contents[] = array('align' => 'center',
                                 'text' => '<div  text-align:center;">Welcome!'.' <b>'. $customer_first_name.'</b></div>' );
		  }else{
	        $info_box_contents[] = array('align' => 'center',
                                 'text' => '<div><div><img src = "'.$customer_picture.'" height = "75" width = "75" style ="border:3px solid #e7e7e7;"></div><div  text-align:center;">Welcome!'.' <b>'. $customer_first_name.'</b></div></div> ' );
		  }
        }
		
	    else{
          $info_box_contents[] = array('align' => 'center',
                                 'text' => $sociallogininterface);
	    }
    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- sociallogin_eof //-->
<?php
// Defining global variables. 
global $cart,$navigation,$messageStack,$breadcrumb,$session_started,$customer_id,$customer_first_name,$customer_default_address_id,$customer_country_id,$customer_zone_id,$lrdata,$customer_picture;
   require (DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);
   require (DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);
   require (DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT);
   require (DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
   require (DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);

	
/**
 * Function that adding a new column in the customer table.
 */
 function add_column_if_not_exist($dbtable, $column, $column_attr = "varchar( 255 ) NULL" ) {
     $exists = false;
     $columns = mysql_query("show columns from $dbtable");
     while ($c = mysql_fetch_assoc($columns)) {
       if ($c['Field'] == $column){
         $exists = true;
         break;
       }
     }      
     if (!$exists) {
       mysql_query("ALTER TABLE `$dbtable` ADD `$column`  $column_attr");
     }
   }
   
/**
 * Function that open a popup for enter email.
 */
  function sociallogin_popup($msg, $lrdata) {?>
	<style type="text/css">
	.LoginRadius_overlay {background: none no-repeat scroll 0 0 rgba(127, 127, 127, 0.6);position: absolute;top: 0;left: 0;z-index: 100001;width: 100%;height: 100%;overflow: auto;padding: 220px 20px 20px 20px;padding-bottom: 130px;position: fixed;}
	#popupouter {-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;overflow:auto;background:#f3f3f3;padding:0px 0px 0px 0px;width:370px;margin:0 auto;}
	#popupinner {-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;overflow:auto;background:#ffffff;margin:10px;padding:10px 8px 4px 8px;}
	#textmatter {margin:10px 0px 10px 0px;font-family:Arial, Helvetica, sans-serif;color:#666666;font-size:14px;}
	.inputtxt {font-family:Arial, Helvetica, sans-serif;color:#a8a8a8;font-size:11px;border:#e5e5e5 1px solid;width:280px;height:27px;margin:5px 0px 15px 0px;}
	.inputbutton {border:#dcdcdc 1px solid;-moz-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;text-decoration:none;
	color:#6e6e6e;font-family:Arial, Helvetica, sans-serif;font-size:13px;cursor:pointer;background:#f3f3f3;padding:6px 7px 6px 8px;
	margin:0px 8px 0px 0px;}
	.inputbutton:hover {border:#00ccff 1px solid;-moz-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;khtml-border-radius:2px;text-decoration:none;color:#000000;font-family:Arial, Helvetica, sans-serif;font-size:13px;cursor:pointer;padding:6px 7px 6px 8px;-moz-box-shadow: 0px 0px  4px #8a8a8a;-webkit-box-shadow: 0px 0px  4px #8a8a8a;box-shadow: 0px 0px  4px #8a8a8a;background:#f3f3f3;margin:0px 8px 0px 0px;}
	#textdiv {text-align:right;font-family:Arial, Helvetica, sans-serif;font-size:11px;color:#000000;}
	.span {font-family:Arial, Helvetica, sans-serif;font-size:11px;color:#00ccff;}
	.span1 {font-family:Arial, Helvetica, sans-serif;font-size:11px;color:#333333;}
	<!--[if IE]>
	.LoginRadius_content_IE {
	background:black;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=90)";filter: alpha(opacity=90);}
	<![endif]-->
	</style>
  <?php
  $output = '<div class="LoginRadius_overlay" class="LoginRadius_content_IE"><div id="popupouter">
             <div id="popupinner">
             <div id="textmatter">';
             if ($msg) {
  $output .= "<b>" . $msg . "</b>";
             }
  $output .= '</div>
             <form method="post" action="">
             <div><input type="text" name="email" id="email" class="inputtxt"/></div><div>
             <input type="submit" id="LoginRadiusEmailClick" name="LoginRadiusEmailClick" value="Submit" class="inputbutton">
             <input type="submit" value="Cancel" class="inputbutton" name = "cancel"/>
	         <input type="hidden" value="'.$lrdata['session'].'" name="session" />';
  $output .= '</div></form></div></div></div>';
  return $output;
 }
 
/**
 * Function that removing tmp data from configration table.
 */
  function remove_tmpuser($lrdata) {
    tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = '".$lrdata['session']."'");
  }
  
// Adding column manually.
  $dbtable = 'customers';
  $column = 'loginradiusid';
  $columnPicture = 'customer_social_avatar';
  add_column_if_not_exist($dbtable, $column, $column_attr = "varchar( 255 ) NULL" );
  add_column_if_not_exist($dbtable, $columnPicture, $column_attr = "varchar( 255 ) NULL" );   
// Start LoginRadius process.
  $lrdata = array();
  $obj = new LoginRadius();
  $userprofile = $obj->loginradius_get_data($apisecretkey);
  if ($obj->IsAuthenticated == true ) {
    $process = true;
    $lrdata = sociallogin_getuser_data($userprofile);
    $error = false;
	tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_title = 'Store tmp data'");
    if (!empty($lrdata['Email']) OR (empty($lrdata['Email']) && $emailrequired != 'True')) {
	  if (empty($lrdata['Email']) && $emailrequired != 'True') {
        $lrdata['Email'] = sociallogin_get_randomEmail($lrdata);
      }
	  $check_customer = sociallogin_get_existUser($lrdata);
	  if (!empty($check_customer) && $check_customer > 0) {
        sociallogin_logging_existUser($check_customer);
      }
	  else {
	    sociallogin_add_newUser($lrdata);
	  }
    }
    if (empty($lrdata['Email']) && $emailrequired == 'True') {
      $check_customer = sociallogin_get_existUser($lrdata);
      if (!empty($check_customer) && $check_customer > 0) {
        sociallogin_logging_existUser($check_customer);
      }
      else {
	    foreach($lrdata as $key => $value) {
	      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description) values ('Store tmp data', '".$lrdata['session']."', '".mysql_real_escape_string($value)."', '".mysql_real_escape_string($key)."')");
	    }
		$msg = "Please enter email to proceed.";
        print sociallogin_popup($msg, $lrdata);
      } 
    }
  }// Obj checking ends.

// Checking for popup button click.
  if (isset($_POST['LoginRadiusEmailClick']) && !empty($_POST['session'])) {
     $lrdata['session'] = mysql_real_escape_string($_POST['session']);
     if (tep_validate_email($_POST['email']) != true) {
       $msg = "<p style='color:red;'><b>This email already registered or invalid. Please choose another one.</b></p>";
       print sociallogin_popup($msg ,$lrdata);
     }
	 else {
	   $check_existEmail = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . mysql_real_escape_string($_POST['email']) . "'");
     $check_customer = tep_db_fetch_array($check_existEmail);
	     if($check_customer > 0) {
		     $msg = "<p style='color:red;'><b>This email already registered or invalid. Please choose another one.</b></p>";
             print sociallogin_popup($msg ,$lrdata);
		   }
         else {
			 $query = tep_db_query("select configuration_title, configuration_key, configuration_value, configuration_description from " . TABLE_CONFIGURATION . " where configuration_key = '" . mysql_real_escape_string($_POST['session']) . "'");
			  while($tmp_data = tep_db_fetch_array($query)) {
				$key = $tmp_data['configuration_description'];
				$value = $tmp_data['configuration_value'];
				$lrdata[$key] = $value;
	          }
             $lrdata['Email'] = mysql_real_escape_string($_POST['email']);
			 sociallogin_add_newUser($lrdata);
	     }
      }
   }
   else if (isset($_POST['cancel'])) {
      remove_tmpuser($lrdata);
	  define('FILENAME_DEFAULT', 'index.php');
      tep_redirect(tep_href_link(FILENAME_DEFAULT));
   }
/**
 * Function that checking exist user.
 */
  function sociallogin_get_existUser($lrdata) {
    $check_existId = tep_db_query("select customers_id, customers_firstname, customer_social_avatar, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where loginradiusid = '" . $lrdata['id'] . "'");
    $check_customer = tep_db_fetch_array($check_existId);
    if (!$check_customer && empty($check_customer) && !empty($lrdata['Email'])) {
      $check_existEmail = tep_db_query("select customers_id, customers_firstname, loginradiusid, customer_social_avatar, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $lrdata['Email'] . "'");
      $check_customer = tep_db_fetch_array($check_existEmail);
	  if (empty($check_customer['loginradiusid'])) {
	    $link_account_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_LINKACCOUNT'");
        $link_account_array = tep_db_fetch_array($link_account_query);
        $link_account = $link_account_array['configuration_value'];
		if ($link_account == 'True') {
		  tep_db_query("update " . TABLE_CUSTOMERS . " set loginradiusid = '" . $lrdata['id'] . "', customer_social_avatar = '" . $lrdata['thumbnail'] . "' where customers_id = '" . $check_customer['customers_id'] . "'");
		}
      }
	  else {
	    tep_db_query("update " . TABLE_CUSTOMERS . " set customer_social_avatar = '" . $lrdata['thumbnail'] . "' where customers_id = '" . $check_customer['customers_id'] . "'");
	  }
    }
    return $check_customer;
  }
  
/**
 * Function that logging in exist user.
 */
  function sociallogin_logging_existUser($check_customer) {
    // Defining global variables. 
    global $cart,$navigation,$messageStack,$breadcrumb,$session_started,$customer_id,$customer_first_name,$customer_default_address_id,$customer_country_id,$customer_zone_id,$lrdata,$customer_picture;
	
	// Redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
   if ($session_started == false ) {
      tep_redirect (tep_href_link(FILENAME_COOKIE_USAGE));
    }
	
	$check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
      $check_country = tep_db_fetch_array($check_country_query);
    $customer_id = $check_customer['customers_id'];
	$customer_default_address_id = $check_customer['customers_default_address_id'];
    $customer_first_name = $check_customer['customers_firstname'];
	$customer_picture = $check_customer['customer_social_avatar'];
	$customer_country_id = $check_country['entry_country_id'];
    $customer_zone_id = $check_country['entry_zone_id'];
    tep_session_register('customer_id');
    tep_session_register('customer_default_address_id');
    tep_session_register('customer_first_name');
	tep_session_register('customer_picture');
	tep_session_register('customer_country_id');
    tep_session_register('customer_zone_id');
    tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");
    $cart->restore_contents();
	if (sizeof($navigation->snapshot) > 0) {
      $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
      $navigation->clear_snapshot();
      tep_redirect($origin_href);
    } 
	else {
	  $use_redirect_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_REDIRECT'");
      $use_redirect_array = tep_db_fetch_array($use_redirect_query);
      $use_redirect = $use_redirect_array['configuration_value'];
      if ($use_redirect == 'tep_redirect') {
	    tep_redirect(tep_href_link(FILENAME_DEFAULT));
      }
	  else {?>
	    <script>window.location = '<?php echo FILENAME_DEFAULT;?>';</script><?php 
      }
	}
    $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
	 
/**
 * Function that generate a random mail.
 */
  function sociallogin_get_randomEmail($lrdata) {
    switch ($lrdata['Provider']) {
      case 'twitter':
        $lrdata['Email'] = $lrdata['id'] . '@' . $lrdata['Provider'] . '.com';
        break;
           
      case 'linkedin':
        $lrdata['Email'] = $lrdata['id'] . '@' . $lrdata['Provider'] . '.com';
        break;
           
      default:
        $Email_id = substr($lrdata['id'], 7);
        $Email_id2 = str_replace("/", "_", $Email_id);
        $lrdata['Email'] = str_replace(".", "_", $Email_id2) . '@' . $lrdata['Provider'] . '.com';
        break;
    }
    return $lrdata['Email'];
  }
  
/**
 * Function that generate a random mail.
 */
  function sociallogin_add_newUser($lrdata) {
    // Defining global variables. 
    global $cart,$navigation,$messageStack,$breadcrumb,$session_started,$customer_id,$customer_first_name,$customer_default_address_id,$customer_country_id,$customer_zone_id,$customer_picture,$lrdata;
     // Checking all data set after click.
  if (isset($lrdata['id']) && !empty($lrdata['id']) && !empty($lrdata['Email'])) {
     if (!empty($lrdata['FirstName']) && !empty($lrdata['LastName'])) {
       $lrdata['FirstName'] = $lrdata['FirstName'];
       $lrdata['LastName'] = $lrdata['LastName'];
     }
     elseif (!empty($lrdata['FullName'])) {
       $lrdata['FirstName'] = $lrdata['FullName'];
       $lrdata['LastName'] = $lrdata['FullName'];
     }
     elseif (!empty($lrdata['ProfileName'])) {
       $lrdata['FirstName'] = $lrdata['ProfileName'];
       $lrdata['LastName']  = $lrdata['ProfileName'];
     }
     elseif (!empty($lrdata['NickName'])) {
       $lrdata['FirstName'] = $lrdata['NickName'];
       $lrdata['LastName'] = $lrdata['NickName'];
     }
     elseif (!empty($email)) {
       $user_name = explode('@', $lrdata['Email']);
       $lrdata['FirstName']  = $user_name[0];
       $lrdata['LastName'] = str_replace("_", " ", $user_name[0]);
     }
     else {
       $lrdata['FirstName'] = $lrdata['id'];
       $lrdata['LastName'] = $lrdata['id'];
     }		 		 
     $sql_data_array = array('customers_firstname' => $lrdata['FirstName'],
                              'customers_lastname' => $lrdata['LastName'],
                              'customers_email_address' => $lrdata['Email'],
							  'customers_telephone' => $lrdata['telephone'],
							  'customers_gender' => $lrdata['gender'],
							  'customers_dob' => tep_date_raw($lrdata['dob']),
                              'loginradiusid' => $lrdata['id'],
							  'customer_social_avatar' => $lrdata['thumbnail'],
                              'customers_password' => tep_encrypt_password($lrdata['password']));
							  tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
   
       $customer_id = tep_db_insert_id();
	   
	   // Getting default country id.
      $country_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'STORE_COUNTRY'");
      $country_array = tep_db_fetch_array($country_query);
      $country = $country_array['configuration_value'];
	  
	  // Getting default zone id.
      $zone_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'STORE_ZONE'");
      $zone_array = tep_db_fetch_array($zone_query);
      $zone_id = $zone_array['configuration_value'];
	  
       $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $lrdata['FirstName'],
                              'entry_lastname' => $lrdata['LastName'],
							  'entry_street_address' => $lrdata['address'],
                              'entry_city' => $lrdata['city'],
							  'entry_country_id' => $country,
							  'entry_zone_id' => $zone_id,
							  'entry_gender' => $lrdata['gender'],
							  'entry_company' => $lrdata['company'],
							  'entry_state' => $lrdata['state']
							  );
        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
        $address_id = tep_db_insert_id();
        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");
        tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");
        if (SESSION_RECREATE == 'True') {
          tep_session_recreate();
        }
		$customer_country_id = $country;
        $customer_zone_id = $zone_id;
        $customer_first_name = $lrdata['FirstName'];
	    $customer_default_address_id = $address_id;
		$customer_picture = $lrdata['thumbnail'];
        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');
		tep_session_register('customer_picture');
		tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');
        $cart->restore_contents(); 
		remove_tmpuser($lrdata); 
	    $name = $lrdata['FirstName'] . ' ' . $lrdata['LastName'];
	    $email_text = sprintf(EMAIL_GREET_NONE, $lrdata['FirstName']);
        $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
        tep_mail($name, $lrdata['Email'], EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		$use_redirect_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_REDIRECT'");
        $use_redirect_array = tep_db_fetch_array($use_redirect_query);
        $use_redirect = $use_redirect_array['configuration_value'];
		if ($use_redirect == 'tep_redirect') {
		  tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
        }
		else {?>
		  <script>window.location = '<?php echo FILENAME_CREATE_ACCOUNT_SUCCESS;?>';</script><?php
		}
	}
  }
  
/**
 * Function getting social user profile data.
 *
 * @param array $userprofile
 *   An array containing all userprofile data keys:
 *
 * @return array
 */
  function sociallogin_getuser_data($userprofile) {
    $lrdata['id'] = tep_db_prepare_input((!empty($userprofile->ID) ? $userprofile->ID : ''));
	$lrdata['session'] = uniqid('LoginRadius_', true);
    $lrdata['Provider'] = tep_db_prepare_input((!empty($userprofile->Provider) ? $userprofile->Provider : ''));
    $lrdata['FirstName'] = tep_db_prepare_input((!empty($userprofile->FirstName) ? $userprofile->FirstName : ''));
    $lrdata['LastName'] = tep_db_prepare_input((!empty($userprofile->LastName) ? $userprofile->LastName : ''));
	$lrdata['NickName'] = tep_db_prepare_input((!empty($userprofile->NickName) ? $userprofile->NickName : ''));
    $lrdata['FullName'] = tep_db_prepare_input((!empty($userprofile->FullName) ? $userprofile->FullName : ''));
    $lrdata['ProfileName'] = tep_db_prepare_input((!empty($userprofile->ProfileName) ? $userprofile->ProfileName : ''));
    $lrdata['dob'] = tep_db_prepare_input((!empty($userprofile->BirthDate) ? $userprofile->BirthDate : ''));
	// Convert the birth date.
	$lrdata['dob'] = date('m-d-Y', strtotime($lrdata['dob']));
    $lrdata['telephone'] = tep_db_prepare_input($userprofile->PhoneNumbers[0]->PhoneNumber);
    if (empty($lrdata['telephone'])) {
      $lrdata['telephone'] = 'default';
    }
    $lrdata['gender'] = tep_db_prepare_input((!empty($userprofile->Gender) ? $userprofile->Gender : ''));
    $lrdata['city'] = tep_db_prepare_input((!empty($userprofile->City) ? $userprofile->City : ''));
    if (empty($lrdata['city'])) {
      $lrdata['city'] = tep_db_prepare_input((!empty($userprofile->HomeTown) ? $userprofile->HomeTown : ''));
    }
    $lrdata['state'] = $lrdata['city'];
    $lrdata['address'] = tep_db_prepare_input((!empty($userprofile->Addresses) ? $userprofile->Addresses : ''));
    if (empty($lrdata['address'])) {
      $lrdata['address'] = $lrdata['city'];
    }
    $lrdata['company'] = tep_db_prepare_input($userprofile->Positions[0]->Comapny->Name);
	if (empty($lrdata['company'])) {
      $lrdata['company'] = tep_db_prepare_input((!empty($userprofile->Industry) ? $userprofile->Industry : ''));
    }
    $lrdata['password'] = mt_rand(8, 15);
    $lrdata['Email'] = tep_db_prepare_input((sizeof($userprofile->Email) > 0 ? $userprofile->Email[0]->Value : ''));
    $lrdata['thumbnail'] = (!empty($userprofile->ImageUrl) ? trim($userprofile->ImageUrl) : '');
    if (empty($lrdata['thumbnail']) && $lrdata['provider'] == 'facebook') {
      $lrdata['thumbnail'] = "https://graph.facebook.com/" . $lrdata['id'] . "/picture?type=large";
    }
    return $lrdata;
  }	 

/**
 * Sdk page callback class for the social login module.
 * This class used only for api communication .
 */
class LoginRadius {
  public $IsAuthenticated, $JsonResponse, $UserProfile, $IsAuth, $UserAuth; 
  public function loginradius_get_data($ApiSecrete) {
    $IsAuthenticated = false;
    if (isset($_REQUEST['token'])) {
      $ValidateUrl = "https://hub.loginradius.com/userprofile.ashx?token=".$_REQUEST['token']."&apisecrete=".$ApiSecrete."";
	  $JsonResponse = $this->loginradius_call_api($ValidateUrl);
      $UserProfile = json_decode($JsonResponse);
      if (isset($UserProfile->ID) && $UserProfile->ID != ''){ 
        $this->IsAuthenticated = true;
        return $UserProfile;
      }
    }
  }

  /*public function loginradius_get_auth($ApiKey, $ApiSecrete){
    $IsAuth = false;
    if (isset($ApiKey)) {
      $ApiKey = trim($ApiKey);
      $ApiSecrete = trim($ApiSecrete);
      $ValidateUrl = "https://hub.loginradius.com/getappinfo/$ApiKey/$ApiSecrete";
	  $JsonResponse = $this->loginradius_call_api($ValidateUrl);
      $UserAuth = json_decode($JsonResponse);
      if (isset($UserAuth->IsValid)){ 
        $this->IsAuth = true;
        return $UserAuth;
      }
	  else {
	    return false;
	  }
    }
  }*/
  public function loginradius_call_api($ValidateUrl) {
    $useapi_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_USEAPI'");
    $useapi_array = tep_db_fetch_array($useapi_query);
    $useapi = $useapi_array['configuration_value'];
    
    if ($useapi == 'CURL') {
	    $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $ValidateUrl);
          curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
		  curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5);
          curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        if (ini_get('open_basedir') == '' && (ini_get('safe_mode') == 'Off' or !ini_get('safe_mode'))) 
		  {
            curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
          }
        else 
		  {
            curl_setopt($curl_handle,CURLOPT_HEADER, 1);
            $url = curl_getinfo($curl_handle,CURLINFO_EFFECTIVE_URL);
            curl_close($curl_handle);
            $curl_handle = curl_init();
            $url = str_replace('?','/?',$url);
            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
         }
		 $JsonResponse = curl_exec($curl_handle);
		 $httpCode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
			 if(in_array($httpCode, array(400, 401, 403, 404, 500, 503)) && $httpCode != 200)
			 {
				return '<div id="Error">Uh oh, looks like something went wrong. Try again in a sec!</div>';
			 }
			 else
			 {
				if(curl_errno($curl_handle) == 28)
				{
					return '<div id="Error">Uh oh, looks like something went wrong. Try again in a sec!</div>';
				}
			 }			 
     }
	 else {
        $JsonResponse = @file_get_contents($ValidateUrl);
		if(strpos(@$http_response_header[0], "400") !== false || strpos(@$http_response_header[0], "401") !== false || strpos(@$http_response_header[0], "403") !== false || strpos(@$http_response_header[0], "404") !== false || strpos(@$http_response_header[0], "500") !== false || strpos(@$http_response_header[0], "503") !== false)
		 {
				return '<div id="Error">Uh oh, looks like something went wrong. Try again in a sec!</div>';
		 }
        }
	 return $JsonResponse;
  }
}?>