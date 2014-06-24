<table cellpadding="0" cellspacing="0" border="0" width="500" align="center">
<?php 
  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
?>
 <tr>
  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
   <tr>
    <td class="main"><b><?php echo TABLE_HEADING_NEW_ADDRESS . tep_draw_hidden_field('action', 'saveAddress'); ?></b></td>
   </tr>
  </table></td>
 </tr>
 <tr>
  <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
   <tr class="infoBoxContents">
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
     <tr>
      <td> </td>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
       <tr>
        <td width="10"> </td>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
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
         <tr>
          <td class="main"><?php echo ENTRY_GENDER; ?> <span class="inputRequirement"><?php echo ENTRY_GENDER_TEXT; ?></span></td>
          <td class="main"><?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '': ''); ?></td>
         </tr>
<?php
  }
?>
         <tr>
          <td class="main"><?php echo ENTRY_FIRST_NAME; ?> <span class="inputRequirement"><?php echo ENTRY_FIRST_NAME_TEXT; ?></span></td>
          <td class="main"><?php echo tep_draw_input_field('firstname') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '': ''); ?></td>
         </tr>
         <tr>
          <td class="main"><?php echo ENTRY_LAST_NAME; ?> <span class="inputRequirement"><?php echo ENTRY_LAST_NAME_TEXT ; ?></span></td>
          <td class="main"><?php echo tep_draw_input_field('lastname') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '': ''); ?></td>
         </tr>
         <tr>
          <td class="main"><?php echo ENTRY_COUNTRY; ?> <span class="inputRequirement"><?php echo ENTRY_COUNTRY_TEXT; ?></span></td>
          <td class="main"><?php echo tep_get_country_list('country',ONEPAGE_DEFAULT_COUNTRY) . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '': ''); ?></td>
         </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
         <tr>
          <td class="main"><?php echo ENTRY_COMPANY; ?> <span class="inputRequirement"><?php echo ENTRY_COMPANY_TEXT; ?></span></td>
          <td class="main"><?php echo tep_draw_input_field('company') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '': ''); ?></td>
         </tr>
<?php
  }
?>
         <tr>
          <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?> <span class="inputRequirement"><?php echo ENTRY_STREET_ADDRESS_TEXT ; ?></span></td>
          <td class="main"><?php echo tep_draw_input_field('street_address') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '': ''); ?></td>
         </tr>
         <tr>
          <td class="main"><?php echo ENTRY_STREET_ADDRESS_2; ?> <span class="inputRequirement"><?php echo ENTRY_STREET_ADDRESS_2_TEXT ; ?></span></td>
          <td class="main"><?php echo tep_draw_input_field('street_address_2') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_2_TEXT) ? '': ''); ?></td>
         </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
         <tr>
          <td class="main"><?php echo ENTRY_SUBURB; ?> <span class="inputRequirement"><?php echo ENTRY_SUBURB_TEXT; ?></span></td>
          <td class="main"><?php echo tep_draw_input_field('suburb') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '': ''); ?></td>
         </tr>
<?php
  }
?>
         <tr>
          <td class="main"><?php echo ENTRY_CITY; ?> <span class="inputRequirement"><?php echo ENTRY_CITY_TEXT; ?></span></td>
          <td class="main"><?php echo tep_draw_input_field('city') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '': ''); ?></td>
         </tr>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
         <tr>
          <td class="main"><?php echo ENTRY_STATE; ?></td>
          <td class="main" id="stateCol"><?php echo $onePageCheckout->getAjaxStateFieldAddress(ONEPAGE_DEFAULT_COUNTRY); ?></td>
         </tr>
<?php
  }
?>
         <tr>
          <td class="main"><?php echo ENTRY_POST_CODE; ?> <span class="inputRequirement"><?php echo ENTRY_POST_CODE_TEXT; ?></span></td>
          <td class="main"><?php echo tep_draw_input_field('postcode') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '': ''); ?></td>
         </tr>
         
        </table></td>
        <td width="10"> </td>
       </tr>
      </table></td>
      <td> </td>
     </tr>
    </table></td>
   </tr>
  </table></td>
 </tr>
<?php
  }    
?>   
</table>