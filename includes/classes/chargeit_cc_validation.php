<?php
/**
* $Id: chargeit_cc_validation.php, v 1.0 2009/01/21 11:07:00 nlz Exp $
*
* Elavon a.k.a. Nova or Virtual Merchant Payment Module for osCommerce
*
*************************************************************************
* ChargeIt prepares data according to Virtual Merchant's Developer's Guide.
* Then posts via https credit card transactions to Virtual Merchant's
* process.do. Submission and referer set by cURL. Transaction results are
* returned by process.do in ASCII pairs. ChargeIt interprets errors, attempts
* to resubmit declines, or display error to user and allow user to
* resubmit information. ChargeIt also auto submits DCC opt in information
* according to admin setup. Transaction errors can also be set to email
* an administrator.
*************************************************************************
*
* @package ChargeIt
* @link http://www.joomecom.com/ Ecommerce Applications
* @copyright Copyright 2008, Teradigm, Inc. All Rights Reserved.
* @author Zelf
* @version 1.2
*/


  class chargeit_cc_validation {
    var $cc_type, $cc_number, $cc_expires_month, $cc_expires_year;

    function validate($number, $expires_m, $expires_y) {
      $this->cc_number = ereg_replace('[^0-9]', '', $number);

      if (ereg('^4[0-9]{12}([0-9]{3})?$', $this->cc_number)) {
        $this->cc_type = 'Visa';
      } elseif (ereg('^5[1-5][0-9]{14}$', $this->cc_number)) {
        $this->cc_type = 'Master Card';
      } elseif (ereg('^3[47][0-9]{13}$', $this->cc_number)) {
        $this->cc_type = 'American Express';
      } elseif (ereg('^3(0[0-5]|[68][0-9])[0-9]{11}$', $this->cc_number)) {
        $this->cc_type = 'Diners Club';
      } elseif (ereg('^6011[0-9]{12}$', $this->cc_number)) {
        $this->cc_type = 'Discover';
      } elseif (ereg('^(3[0-9]{4}|2131|1800)[0-9]{11}$', $this->cc_number)) {
        $this->cc_type = 'JCB';
      } elseif (ereg('^5610[0-9]{12}$', $this->cc_number)) {
        $this->cc_type = 'Australian BankCard';
      } else {
        return -1;
     }

      if (is_numeric($expires_m) && ($expires_m > 0) && ($expires_m < 13)) {
        $this->cc_expires_month = $expires_m;
      } else {
        return -2;
      }

      $current_year = date('Y');
      $expires_y = substr($current_year, 0, 2) . $expires_y;
      if (is_numeric($expires_y) && ($expires_y >= $current_year) && ($expires_y <= ($current_year + 10))) {
        $this->cc_expires_year = $expires_y;
      } else {
        return -3;
      }

      if ($expires_y == $current_year) {
        if ($expires_m < date('n')) {
          return -4;
        }
      }

      return $this->is_valid();
    }

    function is_valid() {
      $cardNumber = strrev($this->cc_number);
      $numSum = 0;

      for ($i=0; $i<strlen($cardNumber); $i++) {
        $currentNum = substr($cardNumber, $i, 1);

// Double every second digit
        if ($i % 2 == 1) {
          $currentNum *= 2;
        }

// Add digits of 2-digit numbers together
        if ($currentNum > 9) {
          $firstNum = $currentNum % 10;
          $secondNum = ($currentNum - $firstNum) / 10;
          $currentNum = $firstNum + $secondNum;
        }

        $numSum += $currentNum;
      }

// If the total has no remainder it's OK
      return ($numSum % 10 == 0);
    }
  }
?>