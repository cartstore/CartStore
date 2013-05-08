<?php
/*
  $Id: affiliate_account_details.php,v 2.0 2002/09/29 SDK

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  if (!isset($is_read_only)) $is_read_only = false;
  if (!isset($processed)) $processed = false;
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') {
    $male = ($affiliate['affiliate_gender'] == 'm') ? true : false;
    $female = ($affiliate['affiliate_gender'] == 'f') ? true : false;
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_GENDER; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo ($affiliate['affiliate_gender'] == 'm') ? MALE : FEMALE;
    } elseif ($error == true) {
      if ($entry_gender_error == true) {
        echo tep_draw_radio_field('a_gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('a_gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
      } else {
        echo ($a_gender == 'm') ? MALE : FEMALE;
        echo tep_draw_hidden_field('a_gender');
      }
    } else {
      echo tep_draw_radio_field('a_gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('a_gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $affiliate['affiliate_firstname'];
  } elseif ($error == true) {
    if ($entry_firstname_error == true) {
      echo tep_draw_input_field('a_firstname') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $a_firstname . tep_draw_hidden_field('a_firstname');
    }
  } else {
    echo tep_draw_input_field('a_firstname', $affiliate['affiliate_firstname']) . '&nbsp;' . ENTRY_FIRST_NAME_TEXT;
  }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $affiliate['affiliate_lastname'];
  } elseif ($error == true) {
    if ($entry_lastname_error == true) {
      echo tep_draw_input_field('a_lastname') . '&nbsp;' . ENTRY_LAST_NAME_ERROR;
    } else {
      echo $a_lastname . tep_draw_hidden_field('a_lastname');
    }
  } else {
    echo tep_draw_input_field('a_lastname', $affiliate['affiliate_lastname']) . '&nbsp;' . ENTRY_FIRST_NAME_TEXT;
  }
?>
            </td>
          </tr>
<?php
  if (ACCOUNT_DOB == 'true') {
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_DATE_OF_BIRTH; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo tep_date_short($affiliate['affiliate_dob']);
    } elseif ($error == true) {
      if ($entry_date_of_birth_error == true) {
        echo tep_draw_input_field('a_dob') . '&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
      } else {
        echo $a_dob . tep_draw_hidden_field('a_dob');
      }
    } else {
      echo tep_draw_input_field('a_dob', tep_date_short($affiliate['affiliate_dob'])) . '&nbsp;' . ENTRY_DATE_OF_BIRTH_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $affiliate['affiliate_email_address'];
  } elseif ($error == true) {
    if ($entry_email_address_error == true) {
      echo tep_draw_input_field('a_email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    } elseif ($entry_email_address_check_error == true) {
      echo tep_draw_input_field('a_email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
    } elseif ($entry_email_address_exists == true) {
      echo tep_draw_input_field('a_email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
    } else {
      echo $a_email_address . tep_draw_hidden_field('a_email_address');
    }
  } else {
    echo tep_draw_input_field('a_email_address', $affiliate['affiliate_email_address']) . '&nbsp;' . ENTRY_EMAIL_ADDRESS_TEXT;
  }
?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>  
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_COMPANY; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_COMPANY; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $affiliate['affiliate_company'];
    } elseif ($error == true) {
      if ($entry_company_error == true) {
        echo tep_draw_input_field('a_company') . '&nbsp;' . ENTRY_AFFILIATE_COMPANY_ERROR;
      } else {
        echo $a_company . tep_draw_hidden_field('a_company');
      }
    } else {
      echo tep_draw_input_field('a_company', $affiliate['affiliate_company']) . '&nbsp;' . ENTRY_AFFILIATE_COMPANY_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_COMPANY_TAXID; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $affiliate['affiliate_company_taxid'];
    } elseif ($error == true) {
      if ($entry_company_taxid_error == true) {
        echo tep_draw_input_field('a_company_taxid') . '&nbsp;' . ENTRY_AFFILIATE_COMPANY_TAXID_ERROR;
      } else {
        echo $a_company_taxid . tep_draw_hidden_field('a_company_taxid');
      }
    } else {
      echo tep_draw_input_field('a_company_taxid', $affiliate['affiliate_company_taxid']) . '&nbsp;' . ENTRY_AFFILIATE_COMPANY_TAXID_TEXT;
    }
?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  }
?>  
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_PAYMENT_DETAILS; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
<?php
  if (AFFILIATE_USE_CHECK == 'true') {
?>  
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_CHECK; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $affiliate['affiliate_payment_check'];
    } elseif ($error == true) {
      if ($entry_payment_check_error == true) {
        echo tep_draw_input_field('a_payment_check') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_CHECK_ERROR;
      } else {
        echo $a_payment_check . tep_draw_hidden_field('a_payment_check');
      }
    } else {
      echo tep_draw_input_field('a_payment_check', $affiliate['affiliate_payment_check']) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_CHECK_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
  if (AFFILIATE_USE_PAYPAL == 'true') {
?>  
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_PAYPAL; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $affiliate['affiliate_payment_paypal'];
    } elseif ($error == true) {
      if ($entry_payment_paypal_error == true) {
        echo tep_draw_input_field('a_payment_paypal') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_PAYPAL_ERROR;
      } else {
        echo $a_payment_paypal . tep_draw_hidden_field('a_payment_paypal');
      }
    } else {
      echo tep_draw_input_field('a_payment_paypal', $affiliate['affiliate_payment_paypal']) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_PAYPAL_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
  if (AFFILIATE_USE_BANK == 'true') {
?>  
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_BANK_NAME; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $affiliate['affiliate_payment_bank_name'];
    } elseif ($error == true) {
      if ($entry_payment_bank_name_error == true) {
        echo tep_draw_input_field('a_payment_bank_name') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_NAME_ERROR;
      } else {
        echo $a_payment_bank_name . tep_draw_hidden_field('a_payment_bank_name');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_name', $affiliate['affiliate_payment_bank_name']) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_NAME_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $affiliate['affiliate_payment_bank_branch_number'];
    } elseif ($error == true) {
      if ($entry_payment_bank_branch_number_error == true) {
        echo tep_draw_input_field('a_payment_bank_branch_number') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER_ERROR;
      } else {
        echo $a_payment_bank_branch_number . tep_draw_hidden_field('a_payment_bank_branch_number');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_branch_number', $affiliate['affiliate_payment_bank_branch_number']) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $affiliate['affiliate_payment_bank_swift_code'];
    } elseif ($error == true) {
      if ($entry_payment_bank_swift_code_error == true) {
        echo tep_draw_input_field('a_payment_bank_swift_code') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE_ERROR;
      } else {
        echo $a_payment_bank_swift_code . tep_draw_hidden_field('a_payment_bank_swift_code');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_swift_code', $affiliate['affiliate_payment_bank_swift_code']) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $affiliate['affiliate_payment_bank_account_name'];
    } elseif ($error == true) {
      if ($entry_payment_bank_account_name_error == true) {
        echo tep_draw_input_field('a_payment_bank_account_name') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME_ERROR;
      } else {
        echo $a_payment_bank_account_name . tep_draw_hidden_field('a_payment_bank_account_name');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_account_name', $affiliate['affiliate_payment_bank_account_name']) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $affiliate['affiliate_payment_bank_account_number'];
    } elseif ($error == true) {
      if ($entry_payment_bank_account_number_error == true) {
        echo tep_draw_input_field('a_payment_bank_account_number') . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER_ERROR;
      } else {
        echo $a_payment_bank_account_number . tep_draw_hidden_field('a_payment_bank_account_number');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_account_number', $affiliate['affiliate_payment_bank_account_number']) . '&nbsp;' . ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?> 
	      </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_ADDRESS; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $affiliate['affiliate_street_address'];
  } elseif ($error == true) {
    if ($entry_street_address_error == true) {
      echo tep_draw_input_field('a_street_address') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
    } else {
      echo $a_street_address . tep_draw_hidden_field('a_street_address');
    }
  } else {
    echo tep_draw_input_field('a_street_address', $affiliate['affiliate_street_address']) . '&nbsp;' . ENTRY_STREET_ADDRESS_TEXT;
  }
?>
            </td>
          </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_SUBURB; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $affiliate['affiliate_suburb'];
    } elseif ($error == true) {
      if ($entry_suburb_error == true) {
        echo tep_draw_input_field('a_suburb') . '&nbsp;' . ENTRY_SUBURB_ERROR;
      } else {
        echo $a_suburb . tep_draw_hidden_field('a_suburb');
      }
    } else {
      echo tep_draw_input_field('a_suburb', $affiliate['affiliate_suburb']) . '&nbsp;' . ENTRY_SUBURB_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_POST_CODE; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $affiliate['affiliate_postcode'];
  } elseif ($error == true) {
    if ($entry_post_code_error == true) {
      echo tep_draw_input_field('a_postcode') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
    } else {
      echo $a_postcode . tep_draw_hidden_field('a_postcode');
    }
  } else {
    echo tep_draw_input_field('a_postcode', $affiliate['affiliate_postcode']) . '&nbsp;' . ENTRY_POST_CODE_TEXT;
  }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_CITY; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $affiliate['affiliate_city'];
  } elseif ($error == true) {
    if ($entry_city_error == true) {
      echo tep_draw_input_field('a_city') . '&nbsp;' . ENTRY_CITY_ERROR;
    } else {
      echo $a_city . tep_draw_hidden_field('a_city');
    }
  } else {
    echo tep_draw_input_field('a_city', $affiliate['affiliate_city']) . '&nbsp;' . ENTRY_CITY_TEXT;
  }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_COUNTRY; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo tep_get_country_name($affiliate['affiliate_country_id']);
  } elseif ($error == true) {
    if ($entry_country_error == true) {
      echo tep_get_country_list('a_country') . '&nbsp;' . ENTRY_COUNTRY_ERROR;
    } else {
      echo tep_get_country_name($a_country) . tep_draw_hidden_field('a_country');
    }
  } else {
    echo tep_get_country_list('a_country', $affiliate['affiliate_country_id']) . '&nbsp;' . ENTRY_COUNTRY_TEXT;
  }
?>
            </td>
          </tr>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_STATE; ?></td>
            <td class="main">&nbsp;
<?php
    $state = tep_get_zone_name($a_country, $a_zone_id, $a_state);
    if ($is_read_only == true) {
      echo tep_get_zone_name($affiliate['affiliate_country_id'], $affiliate['affiliate_zone_id'], $affiliate['affiliate_state']);
    } elseif ($error == true) {
      if ($entry_state_error == true) {
        if ($entry_state_has_zones == true) {
          $zones_array = array();
          $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($a_country) . "' order by zone_name");
          while ($zones_values = tep_db_fetch_array($zones_query)) {
            $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
          }
          echo tep_draw_pull_down_menu('a_state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
        } else {
          echo tep_draw_input_field('a_state') . '&nbsp;' . ENTRY_STATE_ERROR;
        }
      } else {
        echo $state . tep_draw_hidden_field('a_zone_id') . tep_draw_hidden_field('a_state');
      }
    } else {
      echo tep_draw_input_field('a_state', tep_get_zone_name($affiliate['affiliate_country_id'], $affiliate['affiliate_zone_id'], $affiliate['affiliate_state'])) . '&nbsp;' . ENTRY_STATE_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_CONTACT; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $affiliate['affiliate_telephone'];
  } elseif ($error == true) {
    if ($entry_telephone_error == true) {
      echo tep_draw_input_field('a_telephone') . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
    } else {
      echo $a_telephone . tep_draw_hidden_field('a_telephone');
    }
  } else {
    echo tep_draw_input_field('a_telephone', $affiliate['affiliate_telephone']) . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_TEXT;
  }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $affiliate['affiliate_fax'];
  } elseif ($error == true) {
    if ($entry_fax_error == true) {
      echo tep_draw_input_field('a_fax') . '&nbsp;' . ENTRY_FAX_NUMBER_ERROR;
    } else {
      echo $a_fax . tep_draw_hidden_field('a_fax');
    }
  } else {
    echo tep_draw_input_field('a_fax', $affiliate['affiliate_fax']) . '&nbsp;' . ENTRY_FAX_NUMBER_TEXT;
  }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_HOMEPAGE; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $affiliate['affiliate_homepage'];
  } elseif ($error == true) {
    if ($entry_homepage_error == true) {
      echo tep_draw_input_field('a_homepage') . '&nbsp;' . ENTRY_AFFILIATE_HOMEPAGE_ERROR;
    } else {
      echo $a_homepage . tep_draw_hidden_field('a_homepage');
    }
  } else {
    echo tep_draw_input_field('a_homepage', $affiliate['affiliate_homepage']) . '&nbsp;' . ENTRY_AFFILIATE_HOMEPAGE_TEXT;
  }
?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  if ($is_read_only == false) {
?>
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_PASSWORD; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_PASSWORD; ?></td>
            <td class="main">&nbsp;
<?php
    if ($error == true) {
      if ($entry_password_error == true) {
        echo tep_draw_password_field('a_password') . '&nbsp;' . ENTRY_PASSWORD_ERROR;
      } else {
        echo PASSWORD_HIDDEN . tep_draw_hidden_field('a_password') . tep_draw_hidden_field('a_confirmation');
      }
    } else {
      echo tep_draw_password_field('a_password') . '&nbsp;' . ENTRY_PASSWORD_TEXT;
    }
?>
            </td>
          </tr>
<?php
    if ( ($error == false) || ($entry_password_error == true) ) {
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
            <td class="main">&nbsp;
<?php
      echo tep_draw_password_field('a_confirmation') . '&nbsp;' . ENTRY_PASSWORD_CONFIRMATION_TEXT;
?>
            </td>
          </tr>
<?php
    }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  }
?>
</table>