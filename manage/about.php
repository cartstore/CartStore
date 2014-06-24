<?php
  require('includes/application_top.php');
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
      
      $check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum from " . TABLE_ADMIN . " where admin_id = '" . tep_db_input($login_id) . "'");
      if (!tep_db_num_rows($check_admin_query)) {
          $_GET['login'] = 'fail';
      } else {
          $check_admin = tep_db_fetch_array($check_admin_query);
          
          $login_lognum = $check_admin['login_lognum'];
          $login_email_address = $check_admin['login_email_address'];
          $login_logdate = $check_admin['login_logdate'];
          $login_lognum = $check_admin['login_lognum'];
          $login_modified = $check_admin['login_modified'];
          
          tep_db_query("update " . TABLE_ADMIN . " set admin_logdate = now(), admin_lognum = admin_lognum+1 where admin_id = '" . $login_id . "'");
          
          
          
          
          
          if (($login_lognum == 0) || !($login_logdate) || ($login_email_address == 'admin@localhost') || ($login_modified == '0000-00-00 00:00:00')) {
              tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT));
          } else {
              tep_redirect(tep_href_link(FILENAME_ORDERS));
          }
      }
      exit;
  }
  if (!file_exists(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TERMS_CONDITIONS_CONTENT)) {
      tep_mail('CartStore admin'
      , 'start@cartstore.com', 'Missing file', 'The terms and conditions file ' . DIR_WS_LANGUAGES . $language . '/' . FILENAME_TERMS_CONDITIONS_CONTENT . ' is missing.', STORE_NAME, STORE_OWNER_EMAIL_ADDRESS);
      tep_redirect(tep_href_link(FILENAME_LOGOFF));
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
?>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<div class="page-header"><h1>  
CartStore&#8482; Cloud</h1></div>

 




 
               
    
    <div class="jumbotron">
        <h1><i class="fa fa-trophy fa-3x pull-left"></i>OpenSource shopping cart software.</h1>
  <p><a href="http://www.cartstore.com/" class="btn btn-primary btn-lg" role="button" target="_BLANK"><i class="fa fa-user"></i> Request Service</a></p>
</div>

 
<p>CarStore is a feature rich shopping cart cloud application for selling online. Its based in the Amazon AWS cloud. </p>

<p>There are no limits to how large your store can scale. </p>

<p>CartStore is available in every AWS region independently and with higher transaction global stores available in all simultaneously. </p>

<p>CartStore is able to handle large amount of products while still maintaining good speeds. </p>

<p>CartStore has been performance optimized and is the only shopping cart software that has its own built in intrusion detection system that covers known and unknown security vulnerabilities in the software indefinitely and because of that it is the most secure shopping cart software. </p>

  
      
                        	
                      
                        
       
            <?php
  require(DIR_WS_INCLUDES . 'footer.php');
?>