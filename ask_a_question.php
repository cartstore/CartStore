<?php
  require('includes/application_top.php');
  if (!tep_session_is_registered('customer_id') && (ALLOW_GUEST_TO_TELL_A_FRIEND == 'false')) {
      $navigation->set_snapshot();
      tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  $valid_product = false;
  if (isset($_GET['products_id'])) {
      $product_info_query = tep_db_query("select pd.products_name, p.products_model,  p.products_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
      if (tep_db_num_rows($product_info_query)) {
          $valid_product = true;
          $product_info = tep_db_fetch_array($product_info_query);
      }
  }
  if ($valid_product == false) {
      tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id']));
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ASK_QUESTION);
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
      $error = false;
      $to_email_address = tep_db_prepare_input($_POST['to_email_address']);
      $to_name = tep_db_prepare_input($_POST['to_name']);
      $from_email_address = tep_db_prepare_input($_POST['from_email_address']);
      $from_name = tep_db_prepare_input($_POST['from_name']);
      $message = tep_db_prepare_input($_POST['message']);
      if (empty($from_name)) {
          $error = true;
          $messageStack->add('friend', ERROR_FROM_NAME);
      }
      if (!tep_validate_email($from_email_address)) {
          $error = true;
          $messageStack->add('friend', ERROR_FROM_ADDRESS);
      }
      if (empty($to_name)) {
          $error = true;
          $messageStack->add('friend', ERROR_TO_NAME);
      }
      if (!tep_validate_email($to_email_address)) {
          $error = true;
          $messageStack->add('friend', ERROR_TO_ADDRESS);
      }
      if ($error == false) {
          $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $from_name, STORE_NAME);
          $email_body = sprintf(TEXT_EMAIL_INTRO, $to_name, $from_name, $product_info['products_name'], $product_info['products_model'], STORE_NAME) . "\n\n";
          if (tep_not_null($message)) {
              $email_body .= $message . "\n\n";
          }
          $email_body .= sprintf(TEXT_EMAIL_LINK, tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id'])) . "\n\n" . sprintf(TEXT_EMAIL_SIGNATURE, STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n");
          tep_mail($to_name, $to_email_address, $email_subject, $email_body, $from_name, $from_email_address);
          $messageStack->add_session('header', sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $product_info['products_name'], tep_output_string_protected($to_name)), 'success');
          tep_redirect(tep_href_link(FILENAME_ASK_QUESTION2, 'action=success'));
      }
  } elseif (tep_session_is_registered('customer_id')) {
      $account_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
      $account = tep_db_fetch_array($account_query);
      $from_name = $account['customers_firstname'] . ' ' . $account['customers_lastname'];
      $from_email_address = $account['customers_email_address'];
  }
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ASK_QUESTION, 'products_id=' . $_GET['products_id']));
?>


<td width="100%" valign="top"><?php
  echo tep_draw_form('email_friend', tep_href_link(FILENAME_ASK_QUESTION, 'action=process&products_id=' . $_GET['products_id']));
?>
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="smallText"><br />
              <font color="#696969"><b><i><?php
  echo sprintf(HEADING_TITLE, $product_info['products_name']);
?> - (<?php
  echo $product_info['products_model']
?>)</i></b></font></td>
            <td class="" align="center"></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td><?php
  echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
    </tr>
    <?php
  if ($messageStack->size('friend') > 0) {
?>
    <tr>
      <td><?php
      echo $messageStack->output('friend');
?></td>
    </tr>
    <tr>
      <td><?php
      echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
    </tr>
    <?php
  }
?>
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="main"><b><?php
  echo FORM_TITLE_CUSTOMER_DETAILS;
?></b></td>
                  <td class="inputRequirement" align="right"><?php
  echo FORM_REQUIRED_INFORMATION;
?></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                <tr class="infoBoxContents">
                  <td><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main"><?php
  echo FORM_FIELD_CUSTOMER_NAME;
?></td>
                        <td class="main"><?php
  echo tep_draw_input_field('from_name');
?></td>
                      </tr>
                      <tr>
                        <td class="main"><?php
  echo FORM_FIELD_CUSTOMER_EMAIL;
?></td>
                        <td class="main"><?php
  echo tep_draw_input_field('from_email_address');
?></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><?php
  echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
          </tr>
          <tr>
            <td><?php
  echo tep_draw_hidden_field('to_email_address', STORE_OWNER_EMAIL_ADDRESS) . '&nbsp;<span class="inputRequirement">' . '</span>';
?><?php
  echo tep_draw_hidden_field('to_name', STORE_OWNER) . '&nbsp;<span class="inputRequirement">' . '</span>';
?></td>
          </tr>
          <tr>
            <td><?php
  echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
          </tr>
          <tr>
            <td class="main"><b><?php
  echo FORM_TITLE_FRIEND_MESSAGE;
?></b></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                <tr class="infoBoxContents">
                  <td><?php
  echo tep_draw_textarea_field('message', 'soft', 40, 8);
?></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td><?php
  echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td width="10"><?php
  echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                  <td></td>
                  <td align="right"><?php
  echo tep_image_submit('button_send.gif', IMAGE_BUTTON_CONTINUE);
?></td>
                  <td width="10"><?php
  echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
  </table></td>
</form>

<!-- body_text_eof //-->

</tr>
</table>
</body>
</html>