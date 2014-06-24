<?php
/*
  $Id: address_book_details.php,v 1.10 2003/06/09 22:49:56 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  if (!isset($process)) $process = false;
?>
<h3><?php echo NEW_ADDRESS_TITLE; ?></h3> 
 
    
     
<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
    } else {
      $male = ($entry['entry_gender'] == 'm') ? true : false;
    }
    $female = !$male;
?>
         <div class="input-group"><label> <?php echo ENTRY_GENDER; ?></label>
         
                 

      
<?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>

</div>

          
<?php
  }
?>
                  <div class="input-group"><label><?php echo ENTRY_FIRST_NAME; ?> *</label>
                  	
                  	<?php echo tep_draw_input_field('firstname', $entry['entry_firstname']) . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '': ''); ?>
                  	
                  	
                  </div>
                 	
                 	
                 </div>
                 
                 
                   <div class="input-group"><label><?php echo ENTRY_LAST_NAME; ?> *</label>
 
                <?php echo tep_draw_input_field('lastname', $entry['entry_lastname']) . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '': ''); ?>
                </div>
                
            

<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
     <div class="input-group"><label><?php echo ENTRY_COMPANY; ?></label>
     	<?php echo tep_draw_input_field('company', $entry['entry_company']) . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '': ''); ?>
     	
     	
     </div>
             
			  
     
<!-- BOF Separate Pricing Per Customer -->
<?php
   if (tep_not_null($entry['entry_company_tax_id'])) {
   ?>
          
                 <div class="input-group"><label><?php echo ENTRY_COMPANY_TAX_ID; ?></label>
                 	<?php echo $entry['entry_company_tax_id'] ; ?>
                 	
                 </div>
            
 <?php
   } else { // end if (tep_not_null($entry['entry_company_tax_id']))
 ?>        
                  <div class="input-group"><label><?php echo ENTRY_COMPANY_TAX_ID; ?></label><?php echo tep_draw_input_field('company_tax_id') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TAX_ID_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TAX_ID_TEXT . '</span>': ''); ?> 
                  	</div>
           
          
 <?php
   } // end else
?><!-- EOF Separate Pricing Per Customer -->
           

			


       
<?php
  }
?>
         
                <div class="input-group"><label><?php echo ENTRY_STREET_ADDRESS; ?> *</label> 
     <?php echo tep_draw_input_field('street_address', $entry['entry_street_address']) . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '': ''); ?>
     </div>
      

                <div class="input-group"><label><?php echo ENTRY_STREET_ADDRESS_2; ?></label>
     <?php echo tep_draw_input_field('street_address_2', $entry['entry_street_address_2']) . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT_2) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT_2 . '</span>': ''); ?>
     
     </div>
          
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
        
              <div class="input-group"><label><?php echo ENTRY_SUBURB; ?></label>
              	
              	<?php echo tep_draw_input_field('suburb', $entry['entry_suburb']) . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '': ''); ?>

              </div>
      
          
<?php
  }
?>
        
                 <div class="input-group"><label><?php echo ENTRY_POST_CODE; ?> *</label>
  
<?php echo tep_draw_input_field('postcode', $entry['entry_postcode']) . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '': ''); ?>

</div>
       

               <div class="input-group"><label><?php echo ENTRY_CITY; ?> *</label>
               	
               	<?php echo tep_draw_input_field('city', $entry['entry_city']) . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '': ''); ?>
               	
               </div>
           
<?php
  if (ACCOUNT_STATE == 'true') {
?>
          
                  <div class="input-group"><label><?php echo ENTRY_STATE; ?> * </label>
                  	
                 
  
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
      echo tep_draw_input_field('state', tep_get_zone_name($entry['entry_country_id'], $entry['entry_zone_id'], $entry['entry_state']));
    }

   // if (tep_not_null(ENTRY_STATE_TEXT));
?> 
</div>
<?php
  }
?>
          
                 <div class="input-group"><label><?php echo ENTRY_COUNTRY; ?> *</label><?php echo tep_get_country_list('country', $entry['entry_country_id']) . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '': ''); ?>
                 	
                 </div>
 
       

<?php
  if ((isset($_GET['edit']) && ($customer_default_address_id != $_GET['edit'])) || (isset($_GET['edit']) == false) ) {
?>
       
       <?php echo tep_draw_checkbox_field('primary', 'on', false, 'id="primary"') . ' <label>' . SET_AS_PRIMARY . '</label'; ?> 
<?php
  }
?>
        </td>
  </tr>
</table>
