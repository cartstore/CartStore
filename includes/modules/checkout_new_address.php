<?php
/*
  $Id: checkout_new_address.php,v 1.4 2003/06/09 22:49:57 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  if (!isset($process)) $process = false;
?>
 <?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
      $female = ($gender == 'f') ? true : false;
    } else {
      $male = false;
      $female = false;
    }
?>
  <div class="form-group">
  	<label><?php echo ENTRY_GENDER; ?></label>
  
<?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '': ''); ?>
    	
    	

</div>
<?php
  }
?>
 <div class="form-group">
  	<label><?php echo ENTRY_FIRST_NAME; ?> *</label>
 <?php echo tep_draw_input_field('firstname') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '': ''); ?>
 </div>
 <div class="form-group">
  	<label><?php echo ENTRY_LAST_NAME; ?> *</label>

<?php echo tep_draw_input_field('lastname') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '': ''); ?></div>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
  <div class="form-group">
  	<label><?php echo ENTRY_COMPANY; ?></label>
  <?php echo tep_draw_input_field('company') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '': ''); ?></div>
<?php
  }
?>
  <div class="form-group">
  	<label><?php echo ENTRY_STREET_ADDRESS; ?> *</label>
 <?php echo tep_draw_input_field('street_address') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '': ''); ?></div>
 <div class="form-group">
  	<label><?php echo ENTRY_STREET_ADDRESS_2; ?></label>
 <?php echo tep_draw_input_field('street_address_2') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT_2) ? '': ''); ?></div>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
 <div class="form-group">
  	<label><?php echo ENTRY_SUBURB; ?></label>
   <?php echo tep_draw_input_field('suburb') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '': ''); ?></div>
<?php
  }
?>
 <div class="form-group">
  	<label><?php echo ENTRY_POST_CODE; ?> *</label>
 <?php echo tep_draw_input_field('postcode') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '': ''); ?></div>
  <div class="form-group">
  	<label><?php echo ENTRY_CITY; ?> *</label>
   <?php echo tep_draw_input_field('city') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '': ''); ?></div>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
<div class="form-group">
  	<label><?php echo ENTRY_STATE; ?> *</label>
 <?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array);
      } else {
        echo tep_draw_input_field('state');
      }
    } else {
      echo tep_draw_input_field('state');
    }

    if (tep_not_null(ENTRY_STATE_TEXT)) echo '';
?>
     </div>
<?php
  }
?>
 <div class="form-group">
  	<label><?php echo ENTRY_COUNTRY; ?> *</label>
 <?php echo tep_get_country_list('country'); ?></div>
