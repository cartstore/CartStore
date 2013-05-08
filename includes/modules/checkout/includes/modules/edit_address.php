<table cellpadding="0" cellspacing="0" border="0" width="500" align="center">
 <tr>
  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
   <tr>
    <td class="main"><b><?php echo TABLE_HEADING_EDIT_ADDRESS . tep_draw_hidden_field('action', 'saveAddress') . tep_draw_hidden_field('address_id', $address['address_book_id']); ?></b></td>
   </tr>
  </table></td>
 </tr>
 <tr>
  <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
   <tr class="infoBoxContents">
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
     <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
       <tr>
        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') {
      $gender = $address['entry_gender'];
      if (isset($gender)) {
          $male = ($gender == 'm') ? true : false;
          $female = ($gender == 'f') ? true : false;
      } else {
          $male = false;
          $female = false;
      }
?>
         <tr>
          <td class="main"><?php echo ENTRY_GENDER; ?></td>
          <td class="main"><?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
         </tr>
<?php
  }
?>
         <tr>
          <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
          <td class="main"><?php echo tep_draw_input_field('firstname', $address['entry_firstname']) . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
         </tr>
         <tr>
          <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
          <td class="main"><?php echo tep_draw_input_field('lastname', $address['entry_lastname']) . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
         </tr>
         <tr>
          <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
          <td class="main"><?php echo tep_get_country_list('country', $address['entry_country_id']) . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>
         </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
         <tr>
          <td class="main"><?php echo ENTRY_COMPANY; ?></td>
          <td class="main"><?php echo tep_draw_input_field('company', $address['entry_company']) . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
         </tr>
<?php
  }
?>
         <tr>
          <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
          <td class="main"><?php echo tep_draw_input_field('street_address', $address['entry_street_address']) . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
         </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
         <tr>
          <td class="main"><?php echo ENTRY_SUBURB; ?></td>
          <td class="main"><?php echo tep_draw_input_field('suburb', $address['entry_suburb']) . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
         </tr>
<?php
  }
?>
         <tr>
          <td class="main"><?php echo ENTRY_CITY; ?></td>
          <td class="main"><?php echo tep_draw_input_field('city', $address['entry_city']) . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
         </tr>
<?php
  if (ACCOUNT_STATE == 'true') {
      /*
      if (tep_not_null($address['entry_zone_id'])){
          $zones_array = array();
          $zones_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . $address['entry_country_id'] . "' order by zone_code");
          while ($zones_values = tep_db_fetch_array($zones_query)) {
              $zones_array[] = array('id' => $zones_values['zone_code'], 'text' => $zones_values['zone_code']);
          }
          
          $QzoneName = tep_db_query('select zone_code from ' . TABLE_ZONES . ' where zone_id = "' . $address['entry_zone_id'] . '"');
          $zoneName = tep_db_fetch_array($QzoneName);
          $input = tep_draw_pull_down_menu('state', $zones_array, $zoneName['zone_code']);
      }else{
          $input = tep_draw_input_field('state', $address['entry_state']);
      }
      */
?>
         <tr>
          <td class="main"><?php echo ENTRY_STATE; ?></td>
          <td class="main" id="stateCol"><?php 
          echo $onePageCheckout->getAjaxStateFieldAddress($address['entry_country_id'],$address['entry_zone_id'],$address['entry_state']); ?></td>
         </tr>
<?php
  }
?>
         <tr>
          <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
          <td class="main"><?php echo tep_draw_input_field('postcode', $address['entry_postcode']) . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
         </tr>
         
        </table></td>
        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
       </tr>
      </table></td>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
     </tr>
    </table></td>
   </tr>
  </table></td>
 </tr>
</table>