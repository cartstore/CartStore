<?php
  require('includes/application_top.php');
  if (ACCOUNT_VALIDATION == 'true' && ACCOUNT_EDIT_VALIDATION == 'true') {
      require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_VALIDATION);
      include_once('includes/functions/' . FILENAME_ACCOUNT_VALIDATION);
  }
  if (!tep_session_is_registered('customer_id')) {
      $navigation->set_snapshot();
      tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT);
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
      if (ACCOUNT_GENDER == 'true')
          $gender = tep_db_prepare_input($_POST['gender']);
      $firstname = tep_db_prepare_input($_POST['firstname']);
      $lastname = tep_db_prepare_input($_POST['lastname']);
      if (ACCOUNT_DOB == 'true')
          $dob = tep_db_prepare_input($_POST['dob']);
      $email_address = tep_db_prepare_input($_POST['email_address']);
      $telephone = tep_db_prepare_input($_POST['telephone']);
      $fax = tep_db_prepare_input($_POST['fax']);
      $error = false;
      if (ACCOUNT_GENDER == 'true') {
          if (($gender != 'm') && ($gender != 'f')) {
              $error = true;
              $messageStack->add('account_edit', ENTRY_GENDER_ERROR);
          }
      }
      if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
          $error = true;
          $messageStack->add('account_edit', ENTRY_FIRST_NAME_ERROR);
      }
      if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
          $error = true;
          $messageStack->add('account_edit', ENTRY_LAST_NAME_ERROR);
      }
      if (ACCOUNT_DOB == 'true') {
          if (!checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))) {
              $error = true;
              $messageStack->add('account_edit', ENTRY_DATE_OF_BIRTH_ERROR);
          }
      }
      if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR);
      }
      if (!tep_validate_email($email_address)) {
          $error = true;
          $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
      }
      $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id != '" . (int)$customer_id . "'");
      $check_email = tep_db_fetch_array($check_email_query);
      if ($check_email['total'] > 0) {
          $error = true;
          $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
      }
      if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
          $error = true;
          $messageStack->add('account_edit', ENTRY_TELEPHONE_NUMBER_ERROR);
      }
      if (ACCOUNT_VALIDATION == 'true' && ACCOUNT_EDIT_VALIDATION == 'true') {
          $sql = "SELECT * FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE session_id = '" . tep_session_id() . "' LIMIT 1";
          if (!$result = tep_db_query($sql)) {
              $error = true;
              $entry_antirobotreg_error = true;
              $text_antirobotreg_error = ERROR_VALIDATION_1;
          } else {
              $entry_antirobotreg_error = false;
              $anti_robot_row = tep_db_fetch_array($result);
              if ((strtoupper($_POST['antirobotreg']) != $anti_robot_row['reg_key']) || ($anti_robot_row['reg_key'] == '') || (strlen($antirobotreg) != ENTRY_VALIDATION_LENGTH)) {
                  $error = true;
                  $entry_antirobotreg_error = true;
                  $text_antirobotreg_error = ERROR_VALIDATION_2;
              } else {
                  $sql = "DELETE FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE session_id = '" . tep_session_id() . "'";
                  if (!$result = tep_db_query($sql)) {
                      $error = true;
                      $entry_antirobotreg_error = true;
                      $text_antirobotreg_error = ERROR_VALIDATION_3;
                  } else {
                      $sql = "OPTIMIZE TABLE " . TABLE_ANTI_ROBOT_REGISTRATION . "";
                      if (!$result = tep_db_query($sql)) {
                          $error = true;
                          $entry_antirobotreg_error = true;
                          $text_antirobotreg_error = ERROR_VALIDATION_4;
                      } else {
                          $entry_antirobotreg_error = false;
                      }
                  }
              }
          }
          if ($entry_antirobotreg_error == true)
              $messageStack->add('account_edit', $text_antirobotreg_error);
      }
      if ($error == false) {
          $sql_data_array = array('customers_firstname' => $firstname, 'customers_lastname' => $lastname, 'customers_email_address' => $email_address, 'customers_telephone' => $telephone, 'customers_fax' => $fax);
          if (ACCOUNT_GENDER == 'true')
              $sql_data_array['customers_gender'] = $gender;
          if (ACCOUNT_DOB == 'true')
              $sql_data_array['customers_dob'] = tep_date_raw($dob);
          tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
          tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");
          $sql_data_array = array('entry_firstname' => $firstname, 'entry_lastname' => $lastname);
          tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$customer_default_address_id . "'");
          $customer_first_name = $firstname;
          $messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');
          tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
      }
  }
  $account_query = tep_db_query("select customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_telephone, customers_fax from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $account = tep_db_fetch_array($account_query);
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));
  require('includes/form_check.js.php');
  require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_INCLUDES . 'column_left.php');
  echo tep_draw_form('account_edit', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', 'onSubmit="return check_form(account_edit);"') . tep_draw_hidden_field('action', 'process');
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php
  echo HEADING_TITLE;
?></td>
          <td align="right">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <?php
  if ($messageStack->size('account_edit') > 0) {
?>
  <tr>
    <td><?php
      echo $messageStack->output('account_edit');
?></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <?php
  }
?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b>
                  <?php
  echo MY_ACCOUNT_TITLE;
?>
                  </b></td>
                <td class="inputRequirement" align="right"><?php
  echo FORM_REQUIRED_INFORMATION;
?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" cellspacing="2" cellpadding="2">
                    <?php
  if (ACCOUNT_GENDER == 'true') {
      if (isset($gender)) {
          $male = ($gender == 'm') ? true : false;
      } else {
          $male = ($account['customers_gender'] == 'm') ? true : false;
      }
      $female = !$male;
?>
                    <tr>
                      <td class="main"><?php
      echo ENTRY_GENDER;
?></td>
                      <td class="main"><?php
      echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>' : '');
?></td>
                    </tr>
                    <?php
  }
?>
                    <tr>
                      <td class="main"><?php
  echo ENTRY_FIRST_NAME;
?></td>
                      <td class="main"><?php
  echo tep_draw_input_field('firstname', $account['customers_firstname']) . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>' : '');
?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php
  echo ENTRY_LAST_NAME;
?></td>
                      <td class="main"><?php
  echo tep_draw_input_field('lastname', $account['customers_lastname']) . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>' : '');
?></td>
                    </tr>
                    <?php
  if (ACCOUNT_DOB == 'true') {
?>
                    <tr>
                      <td class="main"><?php
      echo ENTRY_DATE_OF_BIRTH;
?></td>
                      <td class="main"><?php
      echo tep_draw_input_field('dob', tep_date_short($account['customers_dob'])) . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>' : '');
?></td>
                    </tr>
                    <?php
  }
?>
                    <tr>
                      <td class="main"><?php
  echo ENTRY_EMAIL_ADDRESS;
?></td>
                      <td class="main"><?php
  echo tep_draw_input_field('email_address', $account['customers_email_address']) . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>' : '');
?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php
  echo ENTRY_TELEPHONE_NUMBER;
?></td>
                      <td class="main"><?php
  echo tep_draw_input_field('telephone', $account['customers_telephone']) . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>' : '');
?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php
  echo ENTRY_FAX_NUMBER;
?></td>
                      <td class="main"><?php
  echo tep_draw_input_field('fax', $account['customers_fax']) . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>' : '');
?></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  
  <!-- // BOF Anti Robot Registration v2.6-->
  <?php
  if (ACCOUNT_VALIDATION == 'true' && strstr($PHP_SELF, 'account_edit') && ACCOUNT_EDIT_VALIDATION == 'true') {
?>
  <tr>
    <td class="main"><b>
      <?php
      echo CATEGORY_ANTIROBOTREG;
?>
      </b></td>
  </tr>
    <tr>
  
  <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
      
      <td><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <?php
      if (ACCOUNT_VALIDATION == 'true' && strstr($PHP_SELF, 'account_edit') && ACCOUNT_EDIT_VALIDATION == 'true') {
          if ($is_read_only == false || (strstr($PHP_SELF, 'account_edit'))) {
              $sql = "DELETE FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE timestamp < '" . (time() - 3600) . "' OR session_id = '" . tep_session_id() . "'";
              if (!$result = tep_db_query($sql)) {
                  die('Could not delete validation key');
              }
              $reg_key = gen_reg_key();
              $sql = "INSERT INTO " . TABLE_ANTI_ROBOT_REGISTRATION . " VALUES ('" . tep_session_id() . "', '" . $reg_key . "', '" . time() . "')";
              if (!$result = tep_db_query($sql)) {
                  die('Could not check registration information');
              }
?>
          <tr>
            <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
                <tr>
                  <td class="main"><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main" width="100%" NOWRAP><span class="main">&nbsp;
                          <?php
              echo ENTRY_ANTIROBOTREG;
?>
                          </span></td>
                      </tr>
                      <tr>
                        <td class="main" width="100%"><?php
              $check_anti_robotreg_query = tep_db_query("select session_id, reg_key, timestamp from anti_robotreg where session_id = '" . tep_session_id() . "'");
              $new_guery_anti_robotreg = tep_db_fetch_array($check_anti_robotreg_query);
              $validation_images = tep_image('validation_png.php?rsid=' . $new_guery_anti_robotreg['session_id']);
              if ($entry_antirobotreg_error == true) {
?>
                          <span>
                          <?php
                  echo '<img src="validation_png.php?rsid=' . $new_guery_anti_robotreg['session_id'] . '" /> <br> ';
                  echo tep_draw_input_field('antirobotreg') . '&nbsp;<br><b><font color="red">' . ERROR_VALIDATION . '<br>' . $text_antirobotreg_error . '</b></font>';
              } else {
?>
                          <span>
                          <?php
                  echo '<img src="validation_png.php?rsid=' . $new_guery_anti_robotreg['session_id'] . '" /> <br> ';
                  echo tep_draw_input_field('antirobotreg', $account['entry_antirobotreg']) . '&nbsp;' . ENTRY_ANTIROBOTREG_TEXT;
              }
          }
      }
?>
                          </span></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
            </tr>
          
        </table></td>
        </tr>
      
    </table></td>
    </tr>
  
  <tr>
    <td></td>
  </tr>
  <?php
  }
?>
  <!-- // EOF Anti Robot Registration v2.6-->
  
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"></td>
                <td><?php
  echo '<a class="button" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . IMAGE_BUTTON_BACK . '</a>';
?></td>
                <td align="right"><?php
  echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE);
?></td>
                <td width="10"></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</form>
<!-- body_text_eof //-->

<?php
  require(DIR_WS_INCLUDES . 'column_right.php');
  require(DIR_WS_INCLUDES . 'footer.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>