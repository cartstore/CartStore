<?php


require('includes/application_top.php');

if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $affiliate_username = tep_db_prepare_input($_POST['affiliate_username']);
    $affiliate_password = tep_db_prepare_input($_POST['affiliate_password']);
    
    // Check if username exists
    $check_affiliate_query = tep_db_query("select affiliate_id, affiliate_firstname, affiliate_password, affiliate_email_address from " . TABLE_AFFILIATE . " where affiliate_email_address = '" . tep_db_input($affiliate_username) . "'");
    if (!tep_db_num_rows($check_affiliate_query)) {
        $_GET['login'] = 'fail';
    } else {
        $check_affiliate = tep_db_fetch_array($check_affiliate_query);
        // Check that password is good
        if (!tep_validate_password($affiliate_password, $check_affiliate['affiliate_password'])) {
            $_GET['login'] = 'fail';
        } else {
            $affiliate_id = $check_affiliate['affiliate_id'];
            tep_session_register('affiliate_id');
            
            $date_now = date('Ymd');
            
            tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_date_of_last_logon = now(), affiliate_number_of_logons = affiliate_number_of_logons + 1 where affiliate_id = '" . $affiliate_id . "'");
            
            tep_redirect(tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL'));
        }
    }
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
require(DIR_WS_INCLUDES . 'header.php');
require(DIR_WS_INCLUDES . 'column_left.php');
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td><h1>
        <?php
echo HEADING_TITLE;
?>
      </h1>
      <?php
if (isset($_GET['login']) && ($_GET['login'] == 'fail')) {
    $info_message = TEXT_LOGIN_ERROR;
} //if (isset($_GET['login']) && ($_GET['login'] == 'fail'))

if (isset($info_message)) {
?>
  <?php
    echo $info_message;
?>
      <?php
} //if (isset($info_message))
?>
      <?php
echo tep_draw_form('login', tep_href_link(FILENAME_AFFILIATE, 'action=process', 'SSL'));
?>
      <div id="module-product">
        <div class="productitem category_listing ui-widget ui-widget-content ui-corner-all">
          <h3 class="category_listing_h3 ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <?php
echo HEADING_NEW_AFFILIATE;
?>
          </h3>
          <div class="afilbox">
            <?php
echo TEXT_NEW_AFFILIATE . '<br><br>' . TEXT_NEW_AFFILIATE_INTRODUCTION;
?>
            
            <p>
            <?php
echo '<a  href="' . tep_href_link(FILENAME_AFFILIATE_TERMS, '', 'SSL') . '">' . TEXT_NEW_AFFILIATE_TERMS . '</a>';
?></p>

            <?php
echo '<a class="button" href="' . tep_href_link(FILENAME_AFFILIATE_SIGNUP, '', 'SSL') . '">Continue</a>';
?>
          </div>
        </div>
      </div>
    
        <div id="module-product">
        <div class="productitem category_listing ui-widget ui-widget-content ui-corner-all">
          <h3 class="category_listing_h3 ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
  
    
      <?php
echo HEADING_RETURNING_AFFILIATE;
?>
      </h3>
       <div class="afilbox">

     <p> <?php
echo TEXT_RETURNING_AFFILIATE;
?></p>
      
   <label>
      <?php
echo TEXT_AFFILIATE_ID;
?>
      </label>
      <?php
echo tep_draw_input_field('affiliate_username');
?>

<div class="clear"></div>
      
      <label>
      <?php
echo TEXT_AFFILIATE_PASSWORD;
?>
      </label> 
      <?php
echo tep_draw_password_field('affiliate_password');
?>  
<div class="clear"></div>
 <p> <?php
echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_AFFILIATE_PASSWORD_FORGOTTEN . '</a>';
?></p> 

<?php
echo tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN);
?>
     

      </div>
        </div>
      </div>
   
    
      </form></td>
  </tr>
</table>
<?php
require(DIR_WS_INCLUDES . 'column_right.php');
require(DIR_WS_INCLUDES . 'footer.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
