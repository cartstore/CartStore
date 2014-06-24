<?php
/*
  $Id: address_book.php,v 1.58 2003/06/09 23:03:52 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

require(DIR_WS_INCLUDES . 'header.php'); 
 require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- body_text //-->
<table><tr><td> 
   
<div class="page-header">  <h1><?php echo HEADING_TITLE; ?> </h1> </div>
   
   
<?php
  if ($messageStack->size('addressbook') > 0) {
?>
      <?php echo $messageStack->output('addressbook'); ?> 
       
<?php
  }
?>
      <h3><?php echo PRIMARY_ADDRESS_TITLE; ?></b></h3>
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="table table-bordered">
              <tr>
                 <td class="main" width="50%" valign="top"><?php echo PRIMARY_ADDRESS_DESCRIPTION; ?></td>
                <td align="right" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2" class="table">
                  <tr  class="success">
                    <td class="main" align="center" valign="top"><b><?php echo PRIMARY_ADDRESS_TITLE; ?></b></td>
                     <td class="main" valign="top"><?php echo tep_address_label($customer_id, $customer_default_address_id, true, ' ', ''); ?></td>
                   </tr>
                </table></td>
              </tr>
            </table> 
        
        
         <h3><?php echo ADDRESS_BOOK_TITLE; ?></h3>
          
<?php
  $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_street_address_2 as street_address_2, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' order by firstname, lastname");
  while ($addresses = tep_db_fetch_array($addresses_query)) {
    $format_id = tep_get_address_format_id($addresses['country_id']);
?>
              
                	
     <table class="table table-striped">
 
                  <tr class="moduleRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL'); ?>'">
                    <td class="main"><b><?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?></b><?php if ($addresses['address_book_id'] == $customer_default_address_id) echo '&nbsp;<small><i>' . PRIMARY_ADDRESS . '</i></small>'; ?></td>
                    <td class="main" align="right"><?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL') . '">' . SMALL_IMAGE_BUTTON_EDIT . '</a> <a class="btn btn-default" href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses['address_book_id'], 'SSL') . '">' . SMALL_IMAGE_BUTTON_DELETE . '</a>'; ?></td>
                  </tr>
               
                     <tr>
                         <td colspan="2"  class=""><address><i><?php echo tep_address_format($format_id, $addresses, true, ' ', ''); ?></i></address></td>
                       </tr>
                    </table> 
                
                
                

<?php
  }
?> 
              <span class="pull-left">  <?php echo '<a class="btn button btn-default" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' .  IMAGE_BUTTON_BACK . '</a>'; ?> </span>
<?php
  if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
?>
           <span class="pull-right">   <?php echo '<a class="btn button btn-primary" href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL') . '">' . IMAGE_BUTTON_ADD_ADDRESS . '</a>'; ?> </span>
<?php
  }
?><div class="clear"></div>
  <p> <?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></p>  </td>
      </tr>
    </table> 
<!-- body_text_eof //-->

<?php require(DIR_WS_INCLUDES . 'column_right.php'); 
 require(DIR_WS_INCLUDES . 'footer.php'); 
 require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
